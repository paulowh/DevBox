<?php
/**
 * Script de Instalação/Fix - DevBox
 * Acesse via navegador: https://devbox.paulowh.com/fix.php
 * Ou execute: php fix.php
 */

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Fix DevBox</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4}";
echo ".ok{color:#4ec9b0}.error{color:#f48771}.warn{color:#dcdcaa}h2{color:#569cd6}pre{background:#2d2d2d;padding:15px;border-radius:5px;overflow-x:auto;}</style></head><body>";
echo "<h1>🔧 Fix DevBox - Regenerar Autoload</h1>";

// Voltar para o diretório raiz do projeto
chdir(__DIR__ . '/..');

echo "<h2>1. Verificando Estrutura</h2>";
if (file_exists('composer.json')) {
    echo "<p class='ok'>✅ composer.json encontrado</p>";
} else {
    echo "<p class='error'>❌ composer.json não encontrado!</p>";
    echo "</body></html>";
    exit;
}

echo "<h2>2. Regenerando Autoload do Composer</h2>";
echo "<p class='warn'>⏳ Executando: composer dump-autoload --optimize</p>";

// Executar composer dump-autoload
$output = [];
$returnVar = 0;
exec('composer dump-autoload --optimize 2>&1', $output, $returnVar);

echo "<pre>";
echo htmlspecialchars(implode("\n", $output));
echo "</pre>";

if ($returnVar === 0) {
    echo "<p class='ok'>✅ Autoload regenerado com sucesso!</p>";
} else {
    echo "<p class='error'>❌ Erro ao regenerar autoload (código: $returnVar)</p>";
    echo "<p class='warn'>⚠️ Tente via SSH: composer dump-autoload --optimize</p>";
}

echo "<h2>3. Verificando Classes</h2>";
require __DIR__ . '/../vendor/autoload.php';

$classes = [
    'App\\Core\\EloquentBootstrap',
    'App\\Core\\DatabaseInitializer',
    'App\\Core\\App',
    'App\\Core\\Router',
    'App\\Core\\Database',
];

foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "<p class='ok'>✅ $class encontrada</p>";
    } else {
        echo "<p class='error'>❌ $class NÃO encontrada</p>";
    }
}

echo "<h2>4. Próximos Passos</h2>";
echo "<p class='ok'>Teste novamente:</p>";
echo "<ul>";
echo "<li><a href='teste.php' style='color:#569cd6'>teste.php</a> - Teste completo</li>";
echo "<li><a href='../index.php' style='color:#569cd6'>index.php</a> - Página inicial</li>";
echo "</ul>";

echo "<hr><p style='color:#808080;font-size:12px'>Executado em: " . date('Y-m-d H:i:s') . "</p>";
echo "</body></html>";
