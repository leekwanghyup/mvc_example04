<?php
namespace app\core;

class Router
{
    public array $routes = []; 
    public Request $request; 
    public Response $reponse;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback; 
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback; 
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false; 
    
        if($callback === false){
            http_response_code(404);
            return $this->renderView('_404');
        }

        if(is_array($callback)){
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;  
        }
        return call_user_func($callback,$this->request);
    }

    public function renderView(string $view, $params=[])
    {
        $layout = $this->renderLayout(); 
        $viewContets = $this->renderViewContents($view, $params);
        return str_replace('{{contents}}', $viewContets, $layout);
    }

    public function renderLayout()
    {
        $layout = Application::$app->controller->layout;
        ob_start();
        include Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    public function renderViewContents(string $view, $params=[])
    {
        foreach ($params as $key => $value)
        {
            $$key = $value;
        }
        ob_start();
        include Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}