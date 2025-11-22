<?php

use App\Core\Router;

// Rota inicial
Router::get('', 'HomeController@index', 'home');
Router::get('home', 'HomeController@index', 'home.index');

// Exemplo de rota com parâmetros
// Router::get('users/{id}', 'UserController@show', 'users.show');

// Exemplo de rotas de API
// Router::post('api/users', 'UserController@store', 'api.users.store');
// Router::put('api/users/{id}', 'UserController@update', 'api.users.update');
// Router::delete('api/users/{id}', 'UserController@destroy', 'api.users.destroy');

// Exemplo com função anônima
// Router::get('test', function() {
//     echo "Teste de rota com função anônima";
// });
