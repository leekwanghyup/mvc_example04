<?php 
namespace app\core;

use app\controllers\Controller;

class Application
{
    public static $ROOT_DIR;
    public static Application $app; 
    public Controller $controller; 
    public Router $router; 
    public Request $request; 
    public Response $response; 
    public Database $db; 

    public function __construct(string $ROOT_DIR, array $config)
    {
        self::$ROOT_DIR = $ROOT_DIR;
        self::$app = $this; 
        $this->request = new Request(); 
        $this->response = new Response(); 
        $this->router = new Router($this->request, $this->response); 
        $this->db = new Database($config['db']);
    }

    public function run()
    {
        echo $this->router->resolve(); 
    }
}