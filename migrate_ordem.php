<?php

require __DIR__ . '/vendor/autoload.php';

// Carrega variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Inicializa o Eloquent ORM
App\Core\EloquentBootstrap::boot();

use Illuminate\Database\Capsule\Manager as Capsule;

echo "→ Executando migration: add_ordem_to_cards_table\n";

try {
    // Verifica se a coluna já existe
    if (!Capsule::schema()->hasColumn('cards', 'ordem')) {
        Capsule::schema()->table('cards', function ($table) {
            $table->integer('ordem')->default(0)->after('turma_id');
            $table->index('ordem');
        });
        echo "✓ Coluna 'ordem' adicionada com sucesso!\n";
    } else {
        echo "⚠ Coluna 'ordem' já existe.\n";
    }
} catch (\Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}
