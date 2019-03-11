<?php

class Router {
    private $routes;
    
    public function __construct() 
    {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = include ($routesPath);
    }
    
    //Возвращает запрос (строка)
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])){
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        $uri = $this->getURI();
        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)){
                
                $intervalRoute = preg_replace("~$uriPattern~", $path, $uri);
                
                $segments = explode('/', $intervalRoute);

                $controllerName = array_shift($segments).'Controller';
                $controllerName = ucfirst($controllerName);
                
                $actoinName = 'action'.ucfirst(array_shift($segments));
                
                $parameters = $segments;
                
                $controllerFile = ROOT. '/controllers/'.
                        $controllerName. '.php';
                if (file_exists($controllerFile)){
                    include_once ($controllerFile);
                }
                
                $controllerObject = new $controllerName;
                $result = call_user_func_array(array($controllerObject,$actoinName), $parameters);
                if ($result != NULL) {
                    break;
                }
            }
        }
    }
}
