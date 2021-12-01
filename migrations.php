<?php 
use app\core\Application;
include __DIR__.'/lib/test.lib.php';
include __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createMutable(__DIR__);
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'], 
        'user' => $_ENV['DB_USER'], 
        'password' => $_ENV['DB_PASSWORD'], 
    ]
]; 

$app = new Application(__DIR__, $config); 

$app->db->applyMigrations(); 
