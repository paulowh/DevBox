<?php

namespace App\Core;

class Router
{
    public static function dispatch()
    {
        $url = $_GET['url'] ?? 'home/index';
        $url = explode('/', $url);

        $controller = ucfirst($url[0]) . 'Controller';
        $method = $url[1] ?? 'index';

        $controllerPath = "App\\Controllers\\{$controller}";

        if (!class_exists($controllerPath)) {
            die("Controller {$controller} não encontrado!");
        }

        $obj = new $controllerPath();

        if (!method_exists($obj, $method)) {
            die("Método {$method} não encontrado!");
        }

        return $obj->$method();
    }
}
