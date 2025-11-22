<?php

namespace App\Controllers;

use App\Core\View;

class HomeController
{
    public function index()
    {
        return View::make('home', [
            'title' => 'Bem-vindo!'
        ]);
    }
}
