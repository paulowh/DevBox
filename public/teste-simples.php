<?php
/**
 * Teste Simples - Sem Framework
 * Acesse: https://devbox.paulowh.com/teste-simples.php
 */

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Teste Simples</title>";
echo "<style>body{font-family:Arial;padding:40px;background:#f0f0f0;}h1{color:#2c3e50;}</style></head><body>";
echo "<h1>✅ PHP está funcionando!</h1>";
echo "<p>Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

echo "<hr>";
echo "<h2>Testando Acesso ao Index.php:</h2>";

// Tentar incluir o index.php e capturar erro
ob_start();
try {
    require __DIR__ . '/index.php';
    $output = ob_get_clean();
    echo "<p style='color:green'>✅ index.php carregou sem erros!</p>";
    echo "<details><summary>Saída do index.php</summary><pre>" . htmlspecialchars($output) . "</pre></details>";
} catch (Throwable $e) {
    ob_end_clean();
    echo "<p style='color:red'>❌ Erro ao carregar index.php:</p>";
    echo "<pre style='background:#fff;padding:15px;border-left:3px solid red'>";
    echo htmlspecialchars($e->getMessage()) . "\n\n";
    echo "Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo "Stack Trace:\n" . htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
}

echo "</body></html>";
