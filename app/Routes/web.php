<?php

use App\Core\Router;

// Rota principal
Router::get('/', 'HomeController@index', 'home');

// API para reordenar cards (Drag and Drop)
Router::post('/cards/reorder', 'CardController@reorder', 'cards.reorder');

// Rotas para Cards
Router::get('/cards/create', 'CardController@create', 'cards.create');
Router::post('/cards/store', 'CardController@store', 'cards.store');
Router::get('/cards/show/{id}', 'CardController@show', 'card.show');
Router::get('/cards/details/{id}', 'CardController@details', 'card.details');
Router::post('/cards/update/{id}', 'CardController@update', 'cards.update');

// Rotas para UCs
Router::get('/uc/related/{id}', 'CardController@ucRelated', 'api.uc.related');
Router::get('/uc', 'UcController@index', 'uc.index');
Router::get('/uc/cadastro', 'UcController@create', 'uc.criar');
Router::get('/uc/editar/{id}', 'UcController@create', 'uc.editar'); // mudar controller para editar


// Rotas de Exemplos
// Router::get('users/{id}', 'UserController@show', 'users.show');
// Router::post('users', 'UserController@store', 'users.store');
// Router::put('users/{id}', 'UserController@update', 'users.update');
// Router::delete('users/{id}', 'UserController@destroy', 'users.destroy');
