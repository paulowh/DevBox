<?php

/**
 * Teste de Carregamento - DevBox
 * Este arquivo tenta carregar o sistema e captura erros
 * Acesse: https://devbox.paulowh.com/teste.php
 */

// Habilitar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Teste DevBox</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4}";
echo ".ok{color:#4ec9b0}.error{color:#f48771}.warn{color:#dcdcaa}h2{color:#569cd6}pre{background:#2d2d2d;padding:15px;border-radius:5px;overflow-x:auto;}</style></head><body>";
echo "<h1>🧪 Teste de Carregamento DevBox</h1>";

// Passo 1: Testar Autoload
echo "<h2>1. Testando Composer Autoload</h2>";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "<p class='ok'>✅ Autoload carregado com sucesso!</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Erro no autoload: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</body></html>";
    exit;
}

// Passo 2: Testar .env
echo "<h2>2. Testando Carregamento do .env</h2>";
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
    echo "<p class='ok'>✅ .env carregado com sucesso!</p>";

    // Verificar variáveis
    $required = ['APP_NAME', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
    $missing = [];
    foreach ($required as $var) {
        $value = $_ENV[$var] ?? getenv($var) ?? null;
        if (!$value) {
            $missing[] = $var;
        }
    }

    if (empty($missing)) {
        echo "<p class='ok'>✅ Todas as variáveis necessárias estão definidas!</p>";
    } else {
        echo "<p class='error'>❌ Variáveis faltando no .env: " . implode(', ', $missing) . "</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Erro ao carregar .env: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre class='error'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

// Passo 3: Testar EloquentBootstrap
echo "<h2>3. Testando Eloquent Bootstrap</h2>";
try {
    App\Core\EloquentBootstrap::boot();
    echo "<p class='ok'>✅ Eloquent inicializado com sucesso!</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Erro no Eloquent: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre class='error'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "<p class='warn'>⚠️ Possível erro de conexão com banco de dados. Verifique credenciais no .env</p>";
}

// Passo 4: Testar DatabaseInitializer
echo "<h2>4. Testando Database Initializer</h2>";
try {
    App\Core\DatabaseInitializer::init();
    echo "<p class='ok'>✅ Database Initializer executado!</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Erro no Database Initializer: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre class='error'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

// Passo 5: Testar App
echo "<h2>5. Testando App Core</h2>";
try {
    $app = new App\Core\App();
    echo "<p class='ok'>✅ App instanciado com sucesso!</p>";
    echo "<p class='warn'>⚠️ Não vou executar \$app->run() para evitar redirecionamentos</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Erro ao criar App: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre class='error'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

// Resumo
echo "<h2>6. Resumo</h2>";
echo "<p class='ok'>Se todos os passos acima passaram com ✅, o problema pode estar no Router ou nas rotas.</p>";
echo "<p class='warn'>Se algum passo falhou, corrija o erro mostrado acima.</p>";

echo "<hr>";
echo "<p><a href='diagnostico.php' style='color:#569cd6'>← Voltar para Diagnóstico Completo</a></p>";
echo "<p><a href='index.php' style='color:#569cd6'>→ Tentar acessar o site</a></p>";

echo "<hr><p style='color:#808080;font-size:12px'>Teste executado em: " . date('Y-m-d H:i:s') . "</p>";
echo "</body></html>";
