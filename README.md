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

### 4. Configuração do Ambiente

Crie seu arquivo de ambiente local `.env` copiando o arquivo de exemplo.

```bash
copy .env.example .env
```

Agora, abra o arquivo `.env` e configure os detalhes da sua conexão com o banco de dados (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) e a URL da aplicação (`APP_URL`).

### 5. Migração do Banco de Dados

O projeto utiliza um sistema de migrações para configurar o esquema do banco de dados. Execute as migrações para criar todas as tabelas necessárias.

*(Nota: O comando para executar as migrações depende da implementação do framework personalizado. Pode ser necessário inspecionar `app/Database/Migrator.php` ou outros arquivos de inicialização para encontrar o comando ou script exato a ser executado.)*

Uma abordagem comum seria ter um script que você possa executar, por exemplo:

```bash
php vendor/bin/migrate
```
*(Você pode precisar criar ou identificar o script correto para isso)*

### 6. Configuração do Servidor Web

Configure seu servidor web (ex: Apache) para usar o diretório `public/` como a raiz dos documentos (DocumentRoot). O arquivo `.htaccess` incluído no diretório `public` deve cuidar da reescrita de URL para você se estiver usando o Apache.

Exemplo de configuração de Virtual Host no Apache:

```apache
<VirtualHost *:80>
    ServerName devbox.local
    DocumentRoot "c:/xampp/htdocs/devbox_/public"
    <Directory "c:/xampp/htdocs/devbox_/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 7. Acessar a Aplicação

Com o servidor configurado, você pode acessar a aplicação em seu navegador na URL que especificou no arquivo `.env` (ex: `http://devbox.local`).

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