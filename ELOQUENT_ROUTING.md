# DevBox - Usando Eloquent ORM e Symfony Routing

## 📦 Bibliotecas Instaladas

- ✅ **Eloquent ORM** (Laravel) - Para trabalhar com banco de dados
- ✅ **Symfony Routing** - Para rotas com grupos, métodos HTTP, etc

---

## 🗄️ **Eloquent ORM - Exemplos de Uso**

### Model (usando Eloquent):

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];
}
```

### Usando no Controller:

```php
use App\Models\User;

// Buscar todos
$users = User::all();

// Buscar por ID
$user = User::find(1);

// Criar
$user = User::create([
    'name' => 'Paulo',
    'email' => 'paulo@example.com',
    'password' => bcrypt('senha123')
]);

// Atualizar
$user = User::find(1);
$user->name = 'Paulo Santos';
$user->save();

// Deletar
User::destroy(1);

// Query Builder
$users = User::where('email', 'like', '%@gmail.com')
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();

// Relacionamentos
class Post extends Model {
    public function user() {
        return $this->belongsTo(User::class);
    }
}

class User extends Model {
    public function posts() {
        return $this->hasMany(Post::class);
    }
}

// Usar relacionamento
$user = User::with('posts')->find(1);
foreach ($user->posts as $post) {
    echo $post->title;
}
```

---

## 🛣️ **Symfony Routing - Exemplos de Uso**

### Definindo rotas em `app/routes/web_symfony.php`:

```php
<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

// Rota simples
$routes->add('home', new Route('/', [
    '_controller' => 'App\Controllers\HomeController::index'
]));

// Rota com parâmetro obrigatório
$routes->add('user.show', new Route('/users/{id}', [
    '_controller' => 'App\Controllers\UserController::show'
], ['id' => '\d+'])); // id deve ser número

// Rota com parâmetro opcional
$routes->add('posts.show', new Route('/posts/{slug}/{id}', [
    '_controller' => 'App\Controllers\PostController::show',
    'id' => null // opcional
]));

// Rotas com métodos HTTP específicos
$routes->add('api.users.index', new Route('/api/users', [
    '_controller' => 'App\Controllers\Api\UserController::index'
], [], [], '', [], ['GET']));

$routes->add('api.users.store', new Route('/api/users', [
    '_controller' => 'App\Controllers\Api\UserController::store'
], [], [], '', [], ['POST']));

$routes->add('api.users.update', new Route('/api/users/{id}', [
    '_controller' => 'App\Controllers\Api\UserController::update'
], ['id' => '\d+'], [], '', [], ['PUT', 'PATCH']));

$routes->add('api.users.delete', new Route('/api/users/{id}', [
    '_controller' => 'App\Controllers\Api\UserController::destroy'
], ['id' => '\d+'], [], '', [], ['DELETE']));

// Prefixo em grupo (simulando grupos)
$apiRoutes = new RouteCollection();
$apiRoutes->add('users.index', new Route('/users', ['_controller' => 'App\Controllers\Api\UserController::index']));
$apiRoutes->add('users.store', new Route('/users', ['_controller' => 'App\Controllers\Api\UserController::store'], [], [], '', [], ['POST']));
$apiRoutes->addPrefix('/api');
$routes->addCollection($apiRoutes);

return $routes;
```

### Controller recebendo parâmetros:

```php
<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class UserController
{
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            http_response_code(404);
            return View::make('errors/404');
        }

        return View::make('users/show', [
            'user' => $user
        ]);
    }
}
```

---

## 🔄 **Escolhendo qual Router usar**

No arquivo `app/core/App.php`:

```php
public function run()
{
    // Opção 1: Router customizado (simples)
    // Router::dispatch();

    // Opção 2: Symfony Router (robusto, com grupos)
    SymfonyRouter::dispatch();
}
```

---

## 📚 **Documentação Oficial**

- **Eloquent ORM:** https://laravel.com/docs/eloquent
- **Symfony Routing:** https://symfony.com/doc/current/routing.html

---

Agora você tem o poder do Eloquent e Symfony Routing! 🚀
