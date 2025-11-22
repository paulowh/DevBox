<?php

use App\Core\Router;

// Rota principal
Router::get('/', 'HomeController@index', 'home');
Router::get('/home', 'HomeController@index', 'home.index');

// Exemplo com função anônima (closure)
// Router::get('test', function () {
//     echo '<h1>Olá do Router!</h1>';
//     echo '<p>Esta rota usa uma função anônima diretamente.</p>';
// });

// Exemplo com função e parâmetros
// Router::get('hello/{name}', function ($name) {
//     echo "<h1>Olá, {$name}!</h1>";
//     echo "<p>Você acessou a rota com parâmetro.</p>";
// });

// Exemplo retornando JSON
// Router::get('api/user/{id}', function ($id) {
//     header('Content-Type: application/json');
//     echo json_encode([
//         'id' => $id,
//         'name' => 'Usuário ' . $id,
//         'email' => 'user' . $id . '@example.com'
//     ]);
// });

// Exemplo com múltiplos parâmetros
// Router::get('posts/{category}/{slug}', function ($category, $slug) {
//     echo "<h1>Post: {$slug}</h1>";
//     echo "<p>Categoria: {$category}</p>";
// });

// ========================================
// GRUPO DE ROTAS - Sintaxe 1 (com array)
// ========================================
// Router::group(['prefix' => 'cards', 'name' => 'cards.'], function () {

//     // GET /cards
//     Router::get('/', function () {
//         echo '<h1>Lista de Cards</h1>';
//         echo '<p>Todos os cards aqui</p>';
//     }, 'index');

//     // GET /cards/create
//     Router::get('create', function () {
//         echo '<h1>Criar novo Card</h1>';
//     }, 'create');

//     // GET /cards/{id}
//     Router::get('{id}', function ($id) {
//         echo "<h1>Card #{$id}</h1>";
//         echo "<p>Detalhes do card</p>";
//     }, 'show');

//     // POST /cards
//     Router::post('/', function () {
//         echo 'Card criado!';
//     }, 'store');

//     // GET /cards/{id}/edit
//     Router::get('{id}/edit', function ($id) {
//         echo "<h1>Editar Card #{$id}</h1>";
//     }, 'edit');
// });

// ========================================
// GRUPO DE ROTAS - Sintaxe 2 (simplificada)
// ========================================
// Router::group('turmas', function () {

//     // GET /turmas
//     Router::get('/', function () {
//         echo '<h1>Lista de Turmas</h1>';
//     });

//     // GET /turmas/{id}
//     Router::get('{id}', function ($id) {
//         echo "<h1>Turma #{$id}</h1>";
//     });
// }, 'turmas.');

// ========================================
// GRUPO DE ROTAS - API
// ========================================
// Router::group('api', function () {

//     Router::get('cards', function () {
//         header('Content-Type: application/json');
//         echo json_encode(['cards' => []]);
//     });

//     Router::get('users', function () {
//         header('Content-Type: application/json');
//         echo json_encode(['users' => []]);
//     });
// }, 'api.');

// Rotas com controllers
// Router::get('users/{id}', 'UserController@show', 'users.show');

// Exemplos de rotas POST
// Router::post('users', 'UserController@store', 'users.store');
// Router::post('login', 'AuthController@login', 'auth.login');

// Exemplos de rotas PUT/DELETE
// Router::put('users/{id}', 'UserController@update', 'users.update');
// Router::delete('users/{id}', 'UserController@destroy', 'users.destroy');
