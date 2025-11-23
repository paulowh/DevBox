<?php

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class View
{
  public static function make($view, $params = [])
  {
    $loader = new FilesystemLoader(__DIR__ . '/../Resources/views');
    $twig = new Environment($loader, [
      'cache' => config('app.env') === 'production'
        ? base_path('app/Storage/cache/views')
        : false,
      'debug' => config('app.debug', false),
    ]);

    // Adiciona funções personalizadas ao Twig
    $twig->addFunction(new TwigFunction('url', 'url'));
    $twig->addFunction(new TwigFunction('asset', 'asset'));
    $twig->addFunction(new TwigFunction('config', 'config'));
    $twig->addFunction(new TwigFunction('route', function ($name, $params = []) {
      return Router::route($name, $params);
    }));

    echo $twig->render($view . '.twig', $params);
  }
}
