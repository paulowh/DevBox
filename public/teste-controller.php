<?php
/**
 * Teste Direto do Controller
 * Acesse: https://devbox.paulowh.com/teste-controller.php
 */

// Forçar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Debug: Verificar variáveis de ambiente
$dbConnection = env('DB_CONNECTION', 'não definido');
$dbHost = env('DB_HOST', 'não definido');
$dbDatabase = env('DB_DATABASE', 'não definido');

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Teste Controller</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4}";
echo ".ok{color:#4ec9b0}.error{color:#f48771}h2{color:#569cd6}pre{background:#2d2d2d;padding:15px;border-radius:5px;overflow-x:auto;}</style></head><body>";
echo "<h1>🧪 Teste Direto do HomeController</h1>";

echo "<h2>0. Variáveis de Ambiente</h2>";
echo "<p>DB_CONNECTION: <span class='" . ($dbConnection !== 'não definido' ? 'ok' : 'error') . "'>{$dbConnection}</span></p>";
echo "<p>DB_HOST: <span class='" . ($dbHost !== 'não definido' ? 'ok' : 'error') . "'>{$dbHost}</span></p>";
echo "<p>DB_DATABASE: <span class='" . ($dbDatabase !== 'não definido' ? 'ok' : 'error') . "'>{$dbDatabase}</span></p>";

// Inicializa o Eloquent ORM
App\Core\EloquentBootstrap::boot();

try {
    echo "<h2>1. Instanciando Controller</h2>";
    $controller = new App\Controllers\HomeController();
    echo "<p class='ok'>✅ HomeController instanciado</p>";

    echo "<h2>2. Executando método index()</h2>";
    ob_start();
    $result = $controller->index();
    $output = ob_get_clean();
    
    echo "<p class='ok'>✅ Método index() executado</p>";
    
    echo "<h2>3. Saída do Controller</h2>";
    if (!empty($output)) {
        echo "<p class='ok'>✅ Controller gerou saída (" . strlen($output) . " bytes)</p>";
        echo "<details><summary>Ver primeiros 500 caracteres</summary>";
        echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "...</pre>";
        echo "</details>";
    } else {
        echo "<p class='error'>❌ Controller não gerou saída!</p>";
        echo "<p>Retorno: <pre>" . var_export($result, true) . "</pre></p>";
    }
    
} catch (Throwable $e) {
    echo "<h2>❌ Erro ao executar controller</h2>";
    echo "<p class='error'>Mensagem: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p class='error'>Arquivo: " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
    echo "<pre class='error'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
