<?php

namespace App\Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class View
{
    public static function make($view, $params = [])
    {
        $loader = new FilesystemLoader(__DIR__ . '/../resources/views');
        $twig = new Environment($loader);

        echo $twig->render($view . '.twig', $params);
    }
}
