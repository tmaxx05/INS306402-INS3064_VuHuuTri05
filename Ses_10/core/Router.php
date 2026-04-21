<?php
// core/Router.php
class Router {
    private $routes =[];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch($uri, $method) {
        // Check if the route exists for the given method (GET/POST)
        if (array_key_exists($uri, $this->routes[$method])) {
            $action = $this->routes[$method][$uri];
            
            // Action looks like "ProductController@index"
            $parts = explode('@', $action);
            $controllerName = $parts[0];
            $methodName = $parts[1];

            // Instantiate the controller and call the method
            // (Autoloader will automatically require the controller file)
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                } else {
                    throw new Exception("Method $methodName not found in $controllerName");
                }
            } else {
                throw new Exception("Controller $controllerName not found");
            }
        } else {
            // Route not found
            throw new Exception("404 Not Found: No route defined for $method $uri");
        }
    }
}