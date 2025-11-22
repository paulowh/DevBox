<?php

namespace App\Core;

use App\Core\Router;
use App\Core\View;

class App
{
    public function run()
    {
        Router::dispatch();
    }
}
