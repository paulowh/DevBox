# DevBox - Framework PHP MVC

Framework PHP MVC simples e poderoso com Twig, jQuery e Fomantic UI.

[devbox](https://devbox.paulowh.com)

## 🚀 Funcionalidades

- ✅ Estrutura MVC organizada
- ✅ Sistema de rotas nomeadas
- ✅ Migration de banco de dados
- ✅ Model base com CRUD
- ✅ Eloquent ORM integrado
- ✅ **Inicialização automática do banco de dados**
- ✅ View engine Twig
- ✅ jQuery e Fomantic UI
- ✅ Variáveis de ambiente (.env)
- ✅ Helper functions úteis

## 📋 Requisitos

- PHP 7.4+
- PostgreSQL ou MySQL/MariaDB
- Composer
- Node.js e NPM
- Apache (com mod_rewrite)

## 🔧 Instalação

1. Clone o repositório e instale as dependências:

```bash
composer install
npm install
```

2. Configure o arquivo `.env`:

```bash
cp .env.example .env
```

Edite o `.env` com suas configurações de banco de dados.

3. Configure o Apache para apontar para a pasta `public/`.

4. Crie o banco de dados:

```sql
CREATE DATABASE devbox;
```

5. **Acesse o projeto no navegador**

   O sistema irá automaticamente:

   - ✅ Criar todas as tabelas (migrations)
   - ✅ Inserir dados iniciais (seeders)
   - ✅ Marcar como instalado

   Isso acontece **apenas na primeira vez** que você acessar o projeto!

## 🎯 Inicialização Automática

### Como Funciona?

Quando você acessa o projeto pela primeira vez:

1. O sistema verifica se existe o arquivo `app/storage/installed.flag`
2. Se não existir, executa automaticamente:
   - Todas as migrations (cria as tabelas)
   - Todos os seeders (insere dados iniciais)
   - Cria a flag de instalação
3. Nas próximas vezes, apenas carrega normalmente (não roda migrations novamente)

### Comandos Úteis

```bash
# Ver status da instalação
php install.php status

# Forçar instalação manual
php install.php install

# Resetar tudo (remove tabelas e flag)
php install.php reset
```

**Importante**: Após usar `php install.php reset`, acesse o site no navegador para reinicializar automaticamente!

## 🗄️ Banco de Dados (Eloquent ORM)

### Models Disponíveis

O projeto já vem com models prontos:

- `Curso` - Cursos técnicos
- `Uc` - Unidades Curriculares
- `Indicador` - Indicadores de competência
- `Conhecimento` - Conhecimentos técnicos
- `Habilidade` - Habilidades práticas
- `Atitude` - Atitudes profissionais
- `Turma` - Turmas/Quadros
- `Card` - Cards de atividades

### Exemplo de Uso

```php
use App\Models\Curso;
use App\Models\Uc;
use App\Models\Card;

// Buscar todos os cursos
$cursos = Curso::all();

// Buscar curso com suas UCs
$curso = Curso::with('ucs')->find(1);

// Criar novo card
$card = Card::create([
    'titulo' => 'Nova atividade',
    'descricao' => 'Descrição da atividade',
    'turma_id' => 1,
    'uc_id' => 2
]);

// Buscar cards de uma turma com relacionamentos
$cards = Card::with(['turma', 'uc', 'indicadores', 'conhecimentos'])
    ->where('turma_id', 1)
    ->get();
```

## 🎨 Desenvolvimento

Para desenvolvimento dev

```bash
npm run dev

# Terminal 2: PHP server
php -S localhost:8000 -t public

# Navegador: acesse
http://localhost:8000

```

Para desenvolvimento com hot reload:

```bash
npm run dev
```

Para build de produção:

```bash
npm run build
```

## 📁 Estrutura de Pastas

```
devbox_/
├── app/                 # Toda a lógica da aplicação
│   ├── config/          # Arquivos de configuração
│   ├── controllers/     # Controllers
│   ├── core/            # Classes principais do framework
│   ├── database/        # Migrations e Migrator
│   ├── models/          # Models
│   ├── resources/       # Views, CSS e JS
│   ├── routes/          # Definição de rotas
│   ├── services/        # Serviços
│   └── storage/         # Cache, logs (não público)
│       ├── cache/       # Cache do Twig e aplicação
│       └── logs/        # Arquivos de log
├── public/              # Pasta pública (Document Root)
│   ├── uploads/         # Uploads públicos
│   └── index.php        # Entry point
└── vendor/              # Dependências do Composer
```

## 🛣️ Rotas

Defina suas rotas em `app/routes/web.php`:

```php
use App\Core\Router;

Router::get('', 'HomeController@index', 'home');
Router::get('users/{id}', 'UserController@show', 'users.show');
Router::post('users', 'UserController@store', 'users.store');
```

## 🗄️ Migrations

Criar nova migration:

```bash
# Crie manualmente em app/database/migrations/
# Formato: YYYY_MM_DD_HHMMSS_nome_da_tabela.php
```

Executar migrations:

```bash
php migrate migrate
```

Reverter última migration:

```bash
php migrate rollback
```

Reverter todas:

```bash
php migrate reset
```

Resetar e executar novamente:

```bash
php migrate fresh
```

### Exemplo de Migration:

```php
<?php

namespace App\Database\Migrations;

use App\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->createTable('products', function ($table) {
            $table->id();
            $table->string('name')->notNullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->notNullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->dropTable('products');
    }
}
```

## 📊 Models

Crie models estendendo a classe base:

```php
<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'description', 'price', 'stock'];

    // Seus métodos personalizados
}
```

Uso do model:

```php
$product = new Product();

// Buscar todos
$products = $product->all();

// Buscar por ID
$product = $product->find(1);

// Criar
$product->create([
    'name' => 'Produto',
    'price' => 99.90
]);

// Atualizar
$product->update(1, ['price' => 89.90]);

// Deletar
$product->delete(1);
```

## 🎨 Views (Twig)

Renderizar views:

```php
use App\Core\View;

View::make('home', [
    'title' => 'Página Inicial'
]);
```

No template Twig:

```twig
{% extends "layout/main.twig" %}

{% block content %}
    <h1>{{ title }}</h1>
    <a href="{{ route('users.show', {id: 1}) }}">Ver Usuário</a>
{% endblock %}
```

## 🛠️ Helper Functions

```php
// URLs
url('users/1')                    // Gera URL
route('users.show', ['id' => 1])  // URL de rota nomeada

// Assets
asset('img/logo.png')             // URL de asset

// Configuração
config('app.name')                // Lê configuração
env('DB_HOST', 'localhost')       // Lê variável de ambiente

// Caminhos
base_path('app/models')           // Caminho base
public_path('uploads')            // Caminho público

// Redirect
redirect('/login')                // Redireciona
```

## 📝 Licença

Este projeto é open-source.

## 👤 Autor

Paulo Santos - [@paulo.wh](https:instagram.com/paulo.wh)

```

```
