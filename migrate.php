#!/usr/bin/env php
<?php
/**
 * Script de Migração do Banco de Dados
 * Execute: php migrate.php
 */

require __DIR__ . '/vendor/autoload.php';

// Carrega variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Inicializa o Eloquent ORM
App\Core\EloquentBootstrap::boot();

use Illuminate\Database\Capsule\Manager as Capsule;

echo "🚀 Iniciando Migrations\n";
echo str_repeat("=", 50) . "\n\n";

// Verifica conexão
try {
    Capsule::connection()->getPdo();
    echo "✅ Conexão com banco de dados OK\n";
    echo "   Database: " . env('DB_DATABASE') . "\n";
    echo "   Host: " . env('DB_HOST') . "\n\n";
} catch (\Exception $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "\n";
    exit(1);
}

// Carrega e executa migrations
$migrationsPath = __DIR__ . '/app/Database/migrations';
$migrations = glob($migrationsPath . '/*.php');

if (empty($migrations)) {
    echo "⚠️  Nenhuma migration encontrada em {$migrationsPath}\n";
    exit(1);
}

sort($migrations); // Executa em ordem alfabética (timestamp)

$success = 0;
$skipped = 0;
$errors = 0;

foreach ($migrations as $file) {
    $migrationName = basename($file, '.php');
    echo "→ {$migrationName}\n";

    try {
        require_once $file;
        
        // Extrai o nome da classe do arquivo
        // Formato: 2024_11_22_000001_create_users_table.php
        // Classe: CreateUsersTable
        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', substr($migrationName, 18))));
        
        if (!class_exists($className)) {
            echo "  ⚠️  Classe {$className} não encontrada\n";
            $skipped++;
            continue;
        }

        $migration = new $className();
        
        if (method_exists($migration, 'up')) {
            $migration->up();
            echo "  ✅ Executada com sucesso\n";
            $success++;
        } else {
            echo "  ⚠️  Método up() não encontrado\n";
            $skipped++;
        }
        
    } catch (\Exception $e) {
        // Se a tabela já existe, considera como sucesso
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "  ⏭️  Tabela já existe (pulada)\n";
            $skipped++;
        } else {
            echo "  ❌ Erro: " . $e->getMessage() . "\n";
            $errors++;
        }
    }
    
    echo "\n";
}

echo str_repeat("=", 50) . "\n";
echo "📊 Resumo:\n";
echo "   ✅ Sucesso: {$success}\n";
echo "   ⏭️  Puladas: {$skipped}\n";
echo "   ❌ Erros: {$errors}\n";
echo str_repeat("=", 50) . "\n";

if ($errors > 0) {
    echo "\n⚠️  Algumas migrations falharam. Verifique os erros acima.\n";
    exit(1);
} else {
    echo "\n🎉 Migrations concluídas!\n";
    exit(0);
}
