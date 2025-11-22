<?php
/**
 * Index com Debug Ativo
 * Acesse: https://devbox.paulowh.com/index-debug.php
 */

// Forçar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<!-- DEBUG MODE ATIVO -->\n";

try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "<!-- ✅ Autoload OK -->\n";

    // Carrega variáveis de ambiente usando phpdotenv
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    echo "<!-- ✅ .env OK -->\n";

    // Inicializa o Eloquent ORM
    App\Core\EloquentBootstrap::boot();
    echo "<!-- ✅ Eloquent OK -->\n";

    // Inicializa o banco de dados automaticamente na primeira execução
    App\Core\DatabaseInitializer::init();
    echo "<!-- ✅ Database Init OK -->\n";

    $app = new App\Core\App();
    echo "<!-- ✅ App criado -->\n";
    
    $app->run();
    echo "<!-- ✅ App executado -->\n";
    
} catch (Throwable $e) {
    echo "<!DOCTYPE html>";
    echo "<html><head><meta charset='UTF-8'><title>Erro - DevBox</title>";
    echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#f48771;}";
    echo "pre{background:#2d2d2d;padding:15px;border-radius:5px;overflow-x:auto;border-left:3px solid #f48771;}</style></head><body>";
    echo "<h1>❌ Erro Fatal</h1>";
    echo "<h2>Mensagem:</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<h2>Arquivo:</h2>";
    echo "<pre>" . htmlspecialchars($e->getFile()) . " : " . $e->getLine() . "</pre>";
    echo "<h2>Stack Trace:</h2>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</body></html>";
}
