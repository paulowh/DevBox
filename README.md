# DevBox
O DevBox é uma aplicação web projetada para o gerenciamento de conteúdo educacional. Ele fornece uma interface visual baseada em cards (semelhante a um quadro Kanban) para organizar e gerenciar unidades de aprendizado, incluindo seus conhecimentos, habilidades e atitudes associados.

## Funcionalidades
- **Quadro de Cards Visual:** Interface de arrastar e soltar (drag-and-drop) para organizar os cards educacionais.
- **Estrutura Educacional:** Gerencie Cursos, Unidades Curriculares (UCs) e Turmas.
- **Conteúdo Detalhado dos Cards:** Os cards podem ser detalhados com indicadores, conhecimentos, habilidades e atitudes específicas.
- **Arquitetura MVC Personalizada:** Construído sobre um framework PHP MVC leve e personalizado.
- **Migrações de Banco de Dados:** Facilidade para configurar e gerenciar o esquema do banco de dados.

## Tecnologias Utilizadas

### Backend

- PHP
- Framework MVC personalizado inspirado no Laravel
- [Illuminate Database (Eloquent ORM)](https://github.com/illuminate/database)
- [Twig](https://twig.symfony.com/) como Template Engine
- [Symfony Routing](https://symfony.com/doc/current/components/routing.html)
- [PHP dotenv](https://github.com/vlucas/phpdotenv) para gerenciamento de variáveis de ambiente

### Frontend

- JavaScript (ES6+)
- Twig
- CSS3
- HTML5

## Instalação e Configuração

Siga os passos abaixo para configurar o projeto em sua máquina local.

### 1. Pré-requisitos

- PHP (versão compatível com as dependências do projeto, provavelmente 8.0+)
- Composer
- Um servidor web (ex: Apache, Nginx)
- Banco de dados MySQL

### 2. Clonar o Repositório

```bash
git clone <url-do-seu-repositorio>
cd devbox
```

### 3. Instalar Dependências

Instale os pacotes PHP necessários usando o Composer.

```bash
composer install
```

### 4. Migração do Banco de Dados
Crie um banco mysql, e configure no `.env` ao exceutar a primeira vez o projeto vai fazer o migrate e a incensão de dados basicos de exemplos

### 5. Configuração do Ambiente

Crie seu arquivo de ambiente local `.env` copiando o arquivo de exemplo.

```bash
copy .env.example .env
```

Agora, abra o arquivo `.env` e configure os detalhes da sua conexão com o banco de dados (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) e a URL da aplicação (`APP_URL`).


## Estrutura do Projeto

- `app/`: Contém a lógica principal da aplicação (Controllers, Models, Views, etc.).
- `app/Core/`: Classes do núcleo do framework (Router, Database, etc.).
- `app/Models/`: Models do Eloquent para interação com o banco de dados.
- `app/Controllers/`: Lida com as requisições HTTP de entrada.
- `app/Resources/views/`: Templates Twig para o frontend.
- `app/Routes/`: Definições de rotas.
- `app/Database/migrations/`: Arquivos de migração do banco de dados.
- `public/`: Diretório público e ponto de entrada (`index.php`).
- `vendor/`: Dependências do Composer.
- `composer.json`: Dependências do projeto.
- `.env`: Arquivo de configuração do ambiente.