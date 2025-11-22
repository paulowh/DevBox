<?php

namespace App\Core;

class App
{
    public function run()
    {

        Router::dispatch();

    }
}
