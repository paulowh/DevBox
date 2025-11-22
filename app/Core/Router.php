<?php

namespace App\Core;

class Router
{
    private static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => []
    ];

    private static $groupPrefix = '';
    private static $groupName = '';

    /** @param uri string caminho da rota
     *  @param controller string controler@nomeFunção
     *  @param name nome alternativo para a rota
     * */

    public static function get($uri, $controller, $name = null)
    {
        self::addRoute('GET', $uri, $controller, $name);
    }

    public static function post($uri, $controller, $name = null)
    {
        self::addRoute('POST', $uri, $controller, $name);
    }

    public static function put($uri, $controller, $name = null)
    {
        self::addRoute('PUT', $uri, $controller, $name);
    }

    public static function delete($uri, $controller, $name = null)
    {
        self::addRoute('DELETE', $uri, $controller, $name);
    }

    public static function patch($uri, $controller, $name = null)
    {
        self::addRoute('PATCH', $uri, $controller, $name);
    }

    /**
     * Cria um grupo de rotas com prefixo
     * 
     * Uso 1: Router::group(['prefix' => 'admin', 'name' => 'admin.'], function() {...})
     * Uso 2: Router::group('admin', function() {...}, 'admin.')
     * 
     * @param string|array $prefixOrAttributes Prefixo da rota ou array de atributos
     * @param callable $callback Função com as rotas do grupo
     * @param string|null $name Nome do grupo (opcional)
     */
    public static function group($prefixOrAttributes, $callback, $name = null)
    {
        // Se for string, converte para array
        if (is_string($prefixOrAttributes)) {
            $attributes = [
                'prefix' => $prefixOrAttributes,
                'name' => $name
            ];
        } else {
            $attributes = $prefixOrAttributes;
        }

        $previousPrefix = self::$groupPrefix;
        $previousName = self::$groupName;

        // Adiciona prefixo
        if (isset($attributes['prefix'])) {
            self::$groupPrefix .= '/' . trim($attributes['prefix'], '/');
        }

        // Adiciona nome
        if (isset($attributes['name'])) {
            self::$groupName .= $attributes['name'];
        }

        // Executa callback
        $callback();

        // Restaura valores anteriores
        self::$groupPrefix = $previousPrefix;
        self::$groupName = $previousName;
    }

    private static function addRoute($method, $uri, $controller, $name)
    {
        // Aplica prefixo do grupo
        $uri = self::$groupPrefix . '/' . trim($uri, '/');

        // Normaliza a URI
        $uri = '/' . trim($uri, '/');
        if ($uri === '/') {
            $uri = '/';
        }

        // Aplica nome do grupo
        if ($name && self::$groupName) {
            $name = self::$groupName . $name;
        }

        self::$routes[$method][$uri] = [
            'controller' => $controller,
            'name' => $name
        ];
    }

    public static function dispatch()
    {
        // Carrega as rotas
        require_once base_path('app/Routes/web.php');

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove o base path se existir
        $basePath = rtrim(config('app.base_path', ''), '/');
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Normaliza a URI
        $uri = '/' . trim($uri, '/');
        if ($uri === '/') {
            $uri = '/';
        }

        // Busca rota exata primeiro
        if (isset(self::$routes[$method][$uri])) {
            return self::executeRoute(self::$routes[$method][$uri], []);
        }

        // Busca rota com parâmetros
        foreach (self::$routes[$method] as $routeUri => $route) {
            $params = self::matchRoute($routeUri, $uri);
            if ($params !== false) {
                return self::executeRoute($route, $params);
            }
        }

        // Rota não encontrada
        http_response_code(404);
        echo "404 - Página não encontrada";
    }

    private static function matchRoute($routeUri, $uri)
    {
        // Converte {param} em regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Remove o primeiro elemento (match completo)

            // Extrai nomes dos parâmetros
            preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $routeUri, $paramNames);

            $params = [];
            foreach ($paramNames[1] as $index => $name) {
                $params[$name] = $matches[$index] ?? null;
            }

            return $params;
        }

        return false;
    }

    private static function executeRoute($route, $params)
    {
        $controller = $route['controller'];

        // Se for uma closure (função anônima)
        if ($controller instanceof \Closure) {
            return call_user_func_array($controller, $params);
        }

        // Se for string, parse controller@method
        list($controllerName, $method) = explode('@', $controller);

        // Adiciona namespace se não existir
        if (strpos($controllerName, '\\') === false) {
            $controllerName = 'App\\Controllers\\' . $controllerName;
        }

        // Verifica se controller existe
        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo "Controller não encontrado: {$controllerName}";
            error_log("Controller não encontrado: {$controllerName}");
            return;
        }

        $controller = new $controllerName();

        // Verifica se método existe
        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "Método não encontrado: {$controllerName}::{$method}";
            error_log("Método não encontrado: {$controllerName}::{$method}");
            return;
        }

        // Executa o controller
        return call_user_func_array([$controller, $method], $params);
    }

    public static function route($name, $params = [])
    {
        // Busca rota pelo nome
        foreach (self::$routes as $method => $routes) {
            foreach ($routes as $uri => $route) {
                if ($route['name'] === $name) {
                    // Substitui parâmetros na URI
                    $url = $uri;
                    foreach ($params as $key => $value) {
                        $url = str_replace('{' . $key . '}', $value, $url);
                    }

                    return url(ltrim($url, '/'));
                }
            }
        }

        return url('');
    }
}
