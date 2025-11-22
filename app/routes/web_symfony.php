<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

// Rota principal
$routes->add('home', new Route('/', [
    '_controller' => 'App\Controllers\HomeController::index'
]));

// Exemplo com parâmetros
// $routes->add('user.show', new Route('/users/{id}', [
//     '_controller' => 'App\Controllers\UserController::show'
// ], ['id' => '\d+'])); // id deve ser número

// Grupo de rotas de API
// $routes->add('api.users.index', new Route('/api/users', [
//     '_controller' => 'App\Controllers\Api\UserController::index'
// ], [], [], '', [], ['GET']));

// $routes->add('api.users.store', new Route('/api/users', [
//     '_controller' => 'App\Controllers\Api\UserController::store'
// ], [], [], '', [], ['POST']));

// $routes->add('api.users.update', new Route('/api/users/{id}', [
//     '_controller' => 'App\Controllers\Api\UserController::update'
// ], ['id' => '\d+'], [], '', [], ['PUT', 'PATCH']));

// $routes->add('api.users.delete', new Route('/api/users/{id}', [
//     '_controller' => 'App\Controllers\Api\UserController::destroy'
// ], ['id' => '\d+'], [], '', [], ['DELETE']));

return $routes;
