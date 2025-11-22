<?php

/**
 * Arquivo de Diagnóstico - DevBox
 * Acesse: https://devbox.paulowh.com/diagnostico.php
 */

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Diagnóstico DevBox</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4}";
echo ".ok{color:#4ec9b0}.error{color:#f48771}.warn{color:#dcdcaa}h2{color:#569cd6}</style></head><body>";
echo "<h1>🔍 Diagnóstico DevBox</h1>";

// 1. Versão PHP
echo "<h2>1. Versão do PHP</h2>";
echo "<p class='ok'>PHP Version: " . phpversion() . "</p>";

// 2. Diretório Atual
echo "<h2>2. Diretório Atual</h2>";
echo "<p class='ok'>__DIR__: " . __DIR__ . "</p>";
echo "<p class='ok'>getcwd(): " . getcwd() . "</p>";

// 3. Verificar Arquivo .env
echo "<h2>3. Arquivo .env</h2>";
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    echo "<p class='ok'>✅ Arquivo .env existe</p>";
    echo "<p class='ok'>Localização: $envPath</p>";
    if (is_readable($envPath)) {
        echo "<p class='ok'>✅ Arquivo .env é legível</p>";
    } else {
        echo "<p class='error'>❌ Arquivo .env NÃO é legível (permissão negada)</p>";
    }
} else {
    echo "<p class='error'>❌ Arquivo .env NÃO existe em: $envPath</p>";
    echo "<p class='warn'>⚠️ Copie .env.hostinger para .env e configure!</p>";
}

// 4. Verificar vendor/autoload.php
echo "<h2>4. Composer (vendor/autoload.php)</h2>";
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    echo "<p class='ok'>✅ vendor/autoload.php existe</p>";
    if (is_readable($autoloadPath)) {
        echo "<p class='ok'>✅ vendor/autoload.php é legível</p>";
    } else {
        echo "<p class='error'>❌ vendor/autoload.php NÃO é legível</p>";
    }
} else {
    echo "<p class='error'>❌ vendor/autoload.php NÃO existe</p>";
    echo "<p class='warn'>⚠️ Execute: composer install --no-dev</p>";
}

// 5. Permissões de Pastas
echo "<h2>5. Permissões de Pastas</h2>";
$folders = [
    'app/Storage/cache' => __DIR__ . '/../app/Storage/cache',
    'app/Storage/logs' => __DIR__ . '/../app/Storage/logs',
    'public/uploads' => __DIR__ . '/uploads',
];

foreach ($folders as $name => $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $writable = is_writable($path) ? '✅' : '❌';
        $color = is_writable($path) ? 'ok' : 'error';
        echo "<p class='$color'>$writable $name: $perms " . (is_writable($path) ? '(gravável)' : '(NÃO gravável)') . "</p>";
    } else {
        echo "<p class='error'>❌ $name: Pasta não existe!</p>";
    }
}

// 6. Módulos PHP Necessários
echo "<h2>6. Extensões PHP</h2>";
$required = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'curl'];
foreach ($required as $ext) {
    $loaded = extension_loaded($ext);
    $color = $loaded ? 'ok' : 'error';
    $icon = $loaded ? '✅' : '❌';
    echo "<p class='$color'>$icon $ext</p>";
}

// 7. mod_rewrite (Apache)
echo "<h2>7. Servidor Web</h2>";
echo "<p class='ok'>SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Desconhecido') . "</p>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    $rewrite = in_array('mod_rewrite', $modules) ? '✅ Habilitado' : '❌ Desabilitado';
    echo "<p class='" . (in_array('mod_rewrite', $modules) ? 'ok' : 'error') . "'>mod_rewrite: $rewrite</p>";
} else {
    echo "<p class='warn'>⚠️ Não é possível verificar módulos Apache (pode ser LiteSpeed)</p>";
}

// 8. Variáveis de Ambiente Carregadas
echo "<h2>8. Variáveis de Ambiente (.env)</h2>";
if (file_exists($envPath) && is_readable($envPath)) {
    try {
        // Tentar carregar com Composer autoload
        if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
            require_once __DIR__ . '/../vendor/autoload.php';
        }

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        echo "<p class='ok'>✅ Arquivo .env carregado com sucesso!</p>";

        $envVars = ['APP_NAME', 'APP_ENV', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
        foreach ($envVars as $var) {
            $value = $_ENV[$var] ?? getenv($var) ?? 'NÃO DEFINIDO';
            if ($var == 'DB_PASSWORD') {
                $value = ($_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD')) ? '****** (definido)' : 'NÃO DEFINIDO';
            }
            $color = ($value && $value != 'NÃO DEFINIDO') ? 'ok' : 'error';
            echo "<p class='$color'>$var: " . htmlspecialchars($value) . "</p>";
        }

        // Mostrar conteúdo do .env (sem senhas)
        echo "<h3>Conteúdo do .env (primeiras linhas):</h3>";
        $envContent = file_get_contents($envPath);
        $envLines = explode("\n", $envContent);
        echo "<pre style='background:#2d2d2d;padding:10px;border-radius:5px;overflow-x:auto'>";
        foreach (array_slice($envLines, 0, 15) as $line) {
            if (strpos($line, 'PASSWORD') !== false) {
                echo "DB_PASSWORD=****** (oculto)\n";
            } else {
                echo htmlspecialchars($line) . "\n";
            }
        }
        echo "</pre>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Erro ao carregar .env: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p class='warn'>Detalhes do erro:</p>";
        echo "<pre style='background:#2d2d2d;padding:10px;color:#f48771'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
} else {
    echo "<p class='error'>❌ Arquivo .env não encontrado ou não legível</p>";
}

// 9. Teste de Escrita
echo "<h2>9. Teste de Escrita (Storage)</h2>";
$testFile = __DIR__ . '/../app/Storage/cache/diagnostico_test.txt';
try {
    file_put_contents($testFile, 'teste');
    if (file_exists($testFile)) {
        echo "<p class='ok'>✅ Consegue escrever em app/Storage/cache/</p>";
        unlink($testFile);
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ NÃO consegue escrever em app/Storage/cache/: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// 10. Resumo
echo "<h2>10. Resumo e Próximos Passos</h2>";
$issues = [];

if (!file_exists($envPath)) {
    $issues[] = "Criar arquivo .env (copiar de .env.hostinger)";
}
if (!file_exists($autoloadPath)) {
    $issues[] = "Executar: composer install --no-dev";
}
if (!is_writable(__DIR__ . '/../app/Storage/cache')) {
    $issues[] = "Ajustar permissões: chmod -R 775 app/Storage/";
}

if (empty($issues)) {
    echo "<p class='ok'>✅ Nenhum problema crítico detectado!</p>";
    echo "<p class='warn'>⚠️ Se ainda der erro 500, verifique os logs em app/Storage/logs/</p>";
} else {
    echo "<p class='error'>❌ Problemas encontrados:</p><ul>";
    foreach ($issues as $issue) {
        echo "<li class='error'>$issue</li>";
    }
    echo "</ul>";
}

echo "<hr><p style='color:#808080;font-size:12px'>Gerado em: " . date('Y-m-d H:i:s') . "</p>";
echo "</body></html>";
