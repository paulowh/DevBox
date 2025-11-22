<?php

namespace App\Core;

class Router
{
    private static $routes = [];
    private static $namedRoutes = [];

    /**
     * Adiciona uma rota GET
     */
    public static function get($uri, $action, $name = null)
    {
        self::addRoute('GET', $uri, $action, $name);
    }

    /**
     * Adiciona uma rota POST
     */
    public static function post($uri, $action, $name = null)
    {
        self::addRoute('POST', $uri, $action, $name);
    }

    /**
     * Adiciona uma rota PUT
     */
    public static function put($uri, $action, $name = null)
    {
        self::addRoute('PUT', $uri, $action, $name);
    }

    /**
     * Adiciona uma rota DELETE
     */
    public static function delete($uri, $action, $name = null)
    {
        self::addRoute('DELETE', $uri, $action, $name);
    }

    /**
     * Adiciona uma rota para qualquer método
     */
    public static function any($uri, $action, $name = null)
    {
        foreach (['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            self::addRoute($method, $uri, $action, $name);
        }
    }

    /**
     * Adiciona uma rota
     */
    private static function addRoute($method, $uri, $action, $name = null)
    {
        $uri = trim($uri, '/');

        self::$routes[$method][$uri] = [
            'action' => $action,
            'name' => $name
        ];

        if ($name) {
            self::$namedRoutes[$name] = $uri;
        }
    }

    /**
     * Despacha a requisição para o controller apropriado
     */
    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = self::getUri();

        // Carrega as rotas
        self::loadRoutes();

        // Procura rota exata
        if (isset(self::$routes[$method][$uri])) {
            return self::callAction(self::$routes[$method][$uri]['action']);
        }

        // Procura rota com parâmetros
        foreach (self::$routes[$method] ?? [] as $route => $data) {
            $pattern = self::convertToPattern($route);

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove o match completo
                return self::callAction($data['action'], $matches);
            }
        }

        // Rota não encontrada
        self::handleNotFound();
    }

    /**
     * Obtém a URI da requisição
     */
    private static function getUri()
    {
        $uri = $_GET['url'] ?? '';
        return trim($uri, '/');
    }

    /**
     * Converte rota para pattern regex
     */
    private static function convertToPattern($route)
    {
        // Substitui {param} por regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route);
        return '#^' . $pattern . '$#';
    }

    /**
     * Chama a action do controller
     */
    private static function callAction($action, $params = [])
    {
        if (is_callable($action)) {
            // Se for uma função anônima
            return call_user_func_array($action, $params);
        }

        if (is_string($action)) {
            // Se for string no formato Controller@method
            [$controller, $method] = explode('@', $action);

            $controllerClass = "App\\Controllers\\{$controller}";

            if (!class_exists($controllerClass)) {
                die("Controller {$controller} não encontrado!");
            }

            $obj = new $controllerClass();

            if (!method_exists($obj, $method)) {
                die("Método {$method} não encontrado no controller {$controller}!");
            }

            return call_user_func_array([$obj, $method], $params);
        }

        if (is_array($action) && count($action) == 2) {
            // Se for array [Controller::class, 'method']
            [$controllerClass, $method] = $action;

            if (!class_exists($controllerClass)) {
                die("Controller não encontrado!");
            }

            $obj = new $controllerClass();

            if (!method_exists($obj, $method)) {
                die("Método {$method} não encontrado!");
            }

            return call_user_func_array([$obj, $method], $params);
        }
    }

    /**
     * Carrega o arquivo de rotas
     */
    private static function loadRoutes()
    {
        $routesFile = base_path('app/routes/web.php');

        if (file_exists($routesFile)) {
            require_once $routesFile;
        }
    }

    /**
     * Retorna URL de uma rota nomeada
     */
    public static function route($name, $params = [])
    {
        if (!isset(self::$namedRoutes[$name])) {
            throw new \Exception("Rota '{$name}' não encontrada");
        }

        $uri = self::$namedRoutes[$name];

        // Substitui parâmetros
        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key . '}', $value, $uri);
        }

        return url($uri);
    }

    /**
     * Tratamento de rota não encontrada
     */
    private static function handleNotFound()
    {
        http_response_code(404);

        if (config('app.debug')) {
            die("Rota não encontrada: " . self::getUri());
        }

        die("Página não encontrada.");
    }
}
