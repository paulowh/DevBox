<?php

namespace App\Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

class View
{
    public static function make($view, $params = [])
    {
        $loader = new FilesystemLoader(__DIR__ . '/../resources/views');
        $twig = new Environment($loader, [
            'cache' => config('app.env') === 'production'
                ? base_path('app/storage/cache/views')
                : false,
            'debug' => config('app.debug', false),
        ]);

        // Adiciona funções personalizadas ao Twig
        $twig->addFunction(new TwigFunction('url', 'url'));
        $twig->addFunction(new TwigFunction('asset', 'asset'));
        $twig->addFunction(new TwigFunction('vite', 'vite'));
        $twig->addFunction(new TwigFunction('config', 'config'));
        $twig->addFunction(new TwigFunction('route', function ($name, $params = []) {
            return \App\Core\Router::route($name, $params);
        }));

        echo $twig->render($view . '.twig', $params);
    }
}
