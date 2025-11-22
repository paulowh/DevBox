<?php

/**
 * Carrega as variáveis de ambiente do arquivo .env
 */
function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignora comentários
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse da linha KEY=VALUE
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Remove aspas do valor se existirem
        if (preg_match('/^"(.*)"$/', $value, $matches)) {
            $value = $matches[1];
        } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
            $value = $matches[1];
        }

        // Define a variável de ambiente
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

/**
 * Obtém uma variável de ambiente
 */
function env($key, $default = null)
{
    $value = $_ENV[$key] ?? getenv($key);

    if ($value === false) {
        return $default;
    }

    // Converte strings booleanas
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'null':
        case '(null)':
            return null;
        case 'empty':
        case '(empty)':
            return '';
    }

    return $value;
}

/**
 * Obtém uma configuração
 */
function config($key, $default = null)
{
    static $config = [];

    if (empty($config)) {
        $configFiles = glob(__DIR__ . '/../config/*.php');
        foreach ($configFiles as $file) {
            $name = basename($file, '.php');
            $config[$name] = require $file;
        }
    }

    $keys = explode('.', $key);
    $value = $config;

    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }

    return $value;
}

/**
 * Obtém o caminho base da aplicação
 */
function base_path($path = '')
{
    return __DIR__ . '/../../' . ltrim($path, '/');
}

/**
 * Obtém o caminho público
 */
function public_path($path = '')
{
    return base_path('public/' . ltrim($path, '/'));
}

/**
 * Redireciona para uma URL
 */
function redirect($url, $statusCode = 302)
{
    header('Location: ' . $url, true, $statusCode);
    exit();
}

/**
 * Retorna a URL base da aplicação
 */
function url($path = '')
{
    $basePath = config('app.base_path', '/');
    return rtrim($basePath, '/') . '/' . ltrim($path, '/');
}

/**
 * Obtém o caminho de um asset compilado pelo Vite
 */
function asset($path)
{
    static $manifest = null;

    if ($manifest === null) {
        $manifestPath = public_path('assets/.vite/manifest.json');

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        } else {
            $manifest = [];
        }
    }

    // Se estiver em modo dev, retorna o caminho direto
    if (empty($manifest)) {
        return url('assets/' . ltrim($path, '/'));
    }

    // Procura o arquivo no manifest
    $key = 'app/resources/' . ltrim($path, '/');

    if (isset($manifest[$key])) {
        return url('assets/' . $manifest[$key]['file']);
    }

    // Se não encontrar no manifest, retorna o caminho direto
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Inclui os assets do Vite (desenvolvimento ou produção)
 */
function vite($entry = 'js/app.js')
{
    $isDev = config('app.env') === 'development';

    if ($isDev) {
        // Modo desenvolvimento - Vite dev server
        $devServerUrl = 'http://localhost:5173';

        echo '<script type="module" src="' . $devServerUrl . '/@vite/client"></script>';
        echo '<script type="module" src="' . $devServerUrl . '/app/resources/' . $entry . '"></script>';
    } else {
        // Modo produção - Assets compilados
        $manifestPath = public_path('assets/.vite/manifest.json');

        if (!file_exists($manifestPath)) {
            return;
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $key = 'app/resources/' . $entry;

        if (!isset($manifest[$key])) {
            return;
        }

        $file = $manifest[$key];

        // CSS
        if (isset($file['css'])) {
            foreach ($file['css'] as $css) {
                echo '<link rel="stylesheet" href="' . url('assets/' . $css) . '">';
            }
        }

        // JS
        echo '<script type="module" src="' . url('assets/' . $file['file']) . '"></script>';
    }
}
