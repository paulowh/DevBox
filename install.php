<?php

require __DIR__ . '/vendor/autoload.php';

// Carrega variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Inicializa o Eloquent ORM
App\Core\EloquentBootstrap::boot();

use App\Core\DatabaseInitializer;
use Illuminate\Database\Capsule\Manager as Capsule;

$command = $argv[1] ?? 'help';

switch ($command) {
    case 'reset':
        echo "→ Removendo todas as tabelas...\n";

        // Drop todas as tabelas na ordem correta (inversa)
        $tables = [
            'card_atitudes',
            'card_habilidades',
            'card_conhecimentos',
            'card_indicadores',
            'cards',
            'turmas',
            'atitudes',
            'habilidades',
            'conhecimentos',
            'indicadores',
            'ucs',
            'cursos'
        ];

        Capsule::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $table) {
            try {
                Capsule::schema()->dropIfExists($table);
                echo "  ✓ Tabela '{$table}' removida\n";
            } catch (\Exception $e) {
                echo "  ✗ Erro ao remover '{$table}': " . $e->getMessage() . "\n";
            }
        }
        Capsule::statement('SET FOREIGN_KEY_CHECKS=1');

        // Remove a flag de instalação
        DatabaseInitializer::reset();

        echo "\n✓ Sistema resetado! Acesse o projeto no navegador para reinicializar.\n";
        break;

    case 'install':
        echo "→ Forçando instalação...\n";
        DatabaseInitializer::init();
        echo "\n✓ Instalação concluída!\n";
        break;

    case 'status':
        $flagFile = storage_path('installed.flag');
        if (file_exists($flagFile)) {
            $date = file_get_contents($flagFile);
            echo "✓ Sistema instalado em: {$date}\n";
        } else {
            echo "✗ Sistema não instalado. Acesse o projeto no navegador ou execute: php install.php install\n";
        }
        break;

    default:
        echo "Comandos disponíveis:\n";
        echo "  php install.php install  - Força a instalação do banco de dados\n";
        echo "  php install.php reset    - Remove todas as tabelas e a flag de instalação\n";
        echo "  php install.php status   - Verifica se o sistema está instalado\n";
        break;
}
