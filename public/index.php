<?php
require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis de ambiente usando phpdotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Inicializa o Eloquent ORM
App\Core\EloquentBootstrap::boot();

// Inicializa o banco de dados automaticamente na primeira execução
App\Core\DatabaseInitializer::init();

use App\Core\App;

$app = new App();
$app->run();
