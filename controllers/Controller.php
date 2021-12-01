<?php 
namespace app\controllers;

use app\core\Application;
use app\core\Router;

class Controller 
{
    public string $layout = 'main';
    public Router $router;
    
    public function __construct()
    {
        $this->router = Application::$app->router; 
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $params=[])
    {
        return $this->router->renderView($view, $params);
    }
}