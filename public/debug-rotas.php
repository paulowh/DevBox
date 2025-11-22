<?php
/**
 * Debug de Rotas
 * Acesse: https://devbox.paulowh.com/debug-rotas.php
 */

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Inicializa o Eloquent ORM
App\Core\EloquentBootstrap::boot();

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Debug Rotas</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4}";
echo ".ok{color:#4ec9b0}.error{color:#f48771}.warn{color:#dcdcaa}h2{color:#569cd6}pre{background:#2d2d2d;padding:15px;border-radius:5px;overflow-x:auto;}</style></head><body>";
echo "<h1>🔍 Debug de Rotas - DevBox</h1>";

// 1. Verificar arquivo de rotas
echo "<h2>1. Verificando Arquivo de Rotas</h2>";
$routesFile = base_path('app/Routes/web.php');
echo "<p>Caminho: <code>" . $routesFile . "</code></p>";

if (file_exists($routesFile)) {
    echo "<p class='ok'>✅ Arquivo app/Routes/web.php existe</p>";
} else {
    echo "<p class='error'>❌ Arquivo app/Routes/web.php NÃO existe!</p>";
    
    // Tentar com minúscula
    $routesFileLower = base_path('app/routes/web.php');
    if (file_exists($routesFileLower)) {
        echo "<p class='warn'>⚠️ Encontrado em: app/routes/web.php (minúscula)</p>";
        echo "<p class='warn'>⚠️ O servidor é case-sensitive! Renomeie a pasta para Routes</p>";
        $routesFile = $routesFileLower;
    }
}

// 2. Carregar rotas
echo "<h2>2. Carregando Rotas</h2>";
if (file_exists($routesFile)) {
    require_once $routesFile;
    echo "<p class='ok'>✅ Arquivo de rotas carregado</p>";
} else {
    echo "<p class='error'>❌ Não foi possível carregar o arquivo de rotas</p>";
    echo "</body></html>";
    exit;
}

// 3. Verificar rotas registradas
echo "<h2>3. Rotas Registradas</h2>";

// Usar reflection para acessar rotas privadas
$reflection = new ReflectionClass('App\Core\Router');
$property = $reflection->getProperty('routes');
$property->setAccessible(true);
$routes = $property->getValue();

echo "<p>Total de rotas por método:</p>";
echo "<ul>";
foreach ($routes as $method => $methodRoutes) {
    $count = count($methodRoutes);
    $color = $count > 0 ? 'ok' : 'warn';
    echo "<li class='$color'>$method: $count rota(s)</li>";
}
echo "</ul>";

// 4. Listar rotas GET
echo "<h2>4. Rotas GET Detalhadas</h2>";
if (!empty($routes['GET'])) {
    echo "<table style='width:100%;border-collapse:collapse;'>";
    echo "<tr style='background:#2d2d2d;'><th style='padding:10px;text-align:left;'>URI</th><th style='padding:10px;text-align:left;'>Controller/Action</th><th style='padding:10px;text-align:left;'>Nome</th></tr>";
    
    foreach ($routes['GET'] as $uri => $route) {
        $controller = is_callable($route['action']) ? 'Closure' : $route['action'];
        $name = $route['name'] ?? '-';
        echo "<tr style='border-bottom:1px solid #444;'>";
        echo "<td style='padding:10px;color:#4ec9b0;'>" . htmlspecialchars($uri) . "</td>";
        echo "<td style='padding:10px;'>" . htmlspecialchars($controller) . "</td>";
        echo "<td style='padding:10px;color:#dcdcaa;'>" . htmlspecialchars($name) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p class='error'>❌ Nenhuma rota GET registrada!</p>";
}

// 5. Testar rota raiz
echo "<h2>5. Testando Rota Raiz (/)</h2>";
if (isset($routes['GET']['/'])) {
    echo "<p class='ok'>✅ Rota '/' está registrada!</p>";
    echo "<pre>";
    print_r($routes['GET']['/']);
    echo "</pre>";
} else {
    echo "<p class='error'>❌ Rota '/' NÃO está registrada!</p>";
}

// 6. Request atual
echo "<h2>6. Request Atual</h2>";
echo "<p>URI: <code>" . $_SERVER['REQUEST_URI'] . "</code></p>";
echo "<p>Método: <code>" . $_SERVER['REQUEST_METHOD'] . "</code></p>";
echo "<p>Base Path: <code>" . config('app.base_path', '(vazio)') . "</code></p>";

echo "<hr>";
echo "<p><a href='/' style='color:#569cd6'>← Tentar acessar a página inicial</a></p>";
echo "<p><a href='teste.php' style='color:#569cd6'>→ Teste completo</a></p>";

echo "</body></html>";
