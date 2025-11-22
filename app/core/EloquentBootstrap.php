<?php

namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class EloquentBootstrap
{
    public static function boot()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => config('database.connection'),
            'host' => config('database.host'),
            'port' => config('database.port'),
            'database' => config('database.database'),
            'username' => config('database.username'),
            'password' => config('database.password'),
            'charset' => config('database.charset'),
            'collation' => config('database.collation'),
            'prefix' => '',
        ]);

        // Torna o Eloquent disponível globalmente
        $capsule->setAsGlobal();

        // Inicia o Eloquent
        $capsule->bootEloquent();
    }
}
