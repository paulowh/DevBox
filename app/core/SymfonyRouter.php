<?php

namespace App\Core;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class SymfonyRouter
{
    public static function dispatch()
    {
        // Carrega as rotas
        $routes = require base_path('app/routes/web_symfony.php');

        // Cria contexto da requisição
        $request = Request::createFromGlobals();
        $context = new RequestContext();
        $context->fromRequest($request);

        // Matcher de rotas
        $matcher = new UrlMatcher($routes, $context);

        try {
            // Tenta fazer match da URL
            $parameters = $matcher->match($request->getPathInfo());

            // Extrai controller e método
            $controller = $parameters['_controller'];
            unset($parameters['_controller'], $parameters['_route']);

            // Chama o controller
            return self::callController($controller, $parameters);
        } catch (ResourceNotFoundException $e) {
            self::handleNotFound();
        }
    }

    private static function callController($controller, $params = [])
    {
        // Se for no formato Controller::method
        if (strpos($controller, '::') !== false) {
            [$class, $method] = explode('::', $controller);

            if (!class_exists($class)) {
                die("Controller {$class} não encontrado!");
            }

            $instance = new $class();

            if (!method_exists($instance, $method)) {
                die("Método {$method} não encontrado!");
            }

            return call_user_func_array([$instance, $method], $params);
        }

        // Se for callable
        if (is_callable($controller)) {
            return call_user_func_array($controller, $params);
        }
    }

    private static function handleNotFound()
    {
        http_response_code(404);

        if (config('app.debug')) {
            die("Rota não encontrada");
        }

        die("Página não encontrada.");
    }
}
