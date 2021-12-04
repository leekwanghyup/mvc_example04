<?php 
namespace app\core;

use PDO;

class Database
{
    public PDO $pdo; 

    public function __construct(array $config)
    {   
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationTable(); 
        $appliedMigrations =  $this->getAppliedMigrations();  // 적용된 migration
    
        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR.'/migrations');  // 모든 migration
        $toApplyMigrations = array_diff($files, $appliedMigrations); // 아직 적용되지 않은 migration
        // dumping($toApplyMigrations);
        foreach ($toApplyMigrations as $migration){
            if($migration === '.' || $migration === '..'){
                continue; 
            }
            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            
            $className = pathinfo($migration, PATHINFO_FILENAME); // m0001_initial , m0002_something
            $instance = new $className(); 
            $this->log("Applying migration $migration");
            $instance->up(); 
            $this->log("Applyied migration $migration"); 
            $newMigrations[] = $migration; 
        }
        if(!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migration are applied");
        }
    }

    public function saveMigrations(array $migrations)
    {
        $migrations =  array_map(fn($m)=> "('$m')", $migrations);
        $str = implode(",", $migrations);
        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str"); 
        $stmt->execute(); 
    }

    public function createMigrationTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
            id INT auto_increment PRIMARY KEY, 
            migration VARCHAR(255), 
            create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations"); 
        $statement->execute(); 
        return $statement->fetchAll(PDO::FETCH_COLUMN);         
    }

    public function log(string $message)
    {
        echo '[ '.date('Y-m-d H:i:s'). ' ] - '.$message.PHP_EOL; 
    }
    
}
