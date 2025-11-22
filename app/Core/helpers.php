<?php

// NOTA: As funções env() e config() já existem no Illuminate\Support
// Elas são carregadas automaticamente pelo Composer

/**
 * Obtém o caminho base da aplicação
 */
if (!function_exists('base_path')) {
    function base_path($path = '')
    {
        return __DIR__ . '/../../' . ltrim($path, '/');
    }
}

/**
 * Obtém o caminho público
 */
if (!function_exists('public_path')) {
    function public_path($path = '')
    {
        return base_path('public/' . ltrim($path, '/'));
    }
}

/**
 * Obtém o caminho de storage
 */
if (!function_exists('storage_path')) {
    function storage_path($path = '')
    {
        return base_path('app/Storage/' . ltrim($path, '/'));
    }
}

/**
 * Redireciona para uma URL
 */
if (!function_exists('redirect')) {
    function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit();
    }
}

/**
 * Retorna a URL base da aplicação
 */
if (!function_exists('url')) {
    function url($path = '')
    {
        $basePath = rtrim(config('app.base_path', '/'), '/');
        $path = ltrim($path, '/');

        // Se o path estiver vazio, retorna apenas o basePath
        if (empty($path)) {
            return $basePath ?: '/';
        }

        // Retorna o caminho completo
        return $basePath . '/' . $path;
    }
}

/**
 * Obtém configuração (usa a função nativa do Laravel se disponível)
 */
if (!function_exists('config')) {
    function config($key, $default = null)
    {
        static $config = [];

        if (empty($config)) {
            $configFiles = glob(__DIR__ . '/../Config/*.php');
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
}

/**
 * Obtém o caminho de um asset compilado pelo Vite
 */
if (!function_exists('asset')) {
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
        $key = 'app/Resources/' . ltrim($path, '/');

        if (isset($manifest[$key])) {
            return url('assets/' . $manifest[$key]['file']);
        }

        // Se não encontrar no manifest, retorna o caminho direto
        return url('assets/' . ltrim($path, '/'));
    }
}

/**
 * Inclui os assets do Vite (desenvolvimento ou produção)
 */
if (!function_exists('vite')) {
    function vite($entry = 'js/app.js')
    {
        // Detecta se o Vite dev server está rodando
        $devServerUrl = 'http://localhost:5173';
        $isDev = false;

        // Tenta conectar no dev server (timeout curto)
        $context = stream_context_create([
            'http' => [
                'timeout' => 0.5,
                'ignore_errors' => true
            ]
        ]);

        $isDev = @file_get_contents($devServerUrl . '/@vite/client', false, $context) !== false;

        if ($isDev) {
            // Modo desenvolvimento - Vite dev server com HMR
            echo '<script type="module" src="' . $devServerUrl . '/@vite/client"></script>' . "\n";
            echo '<script type="module" src="' . $devServerUrl . '/app/Resources/' . $entry . '"></script>' . "\n";
        } else {
            // Modo produção - Assets compilados
            $manifestPath = public_path('assets/.vite/manifest.json');

            if (!file_exists($manifestPath)) {
                echo "<!-- ⚠️ Vite manifest não encontrado. Execute: npm run build -->" . "\n";
                return;
            }

            $manifest = json_decode(file_get_contents($manifestPath), true);

            if (!$manifest) {
                echo "<!-- ⚠️ Erro ao ler manifest.json -->" . "\n";
                return;
            }

            $key = 'app/Resources/' . $entry;

            if (!isset($manifest[$key])) {
                echo "<!-- ⚠️ Entry '$entry' não encontrado no manifest. Chave disponíveis: " . implode(', ', array_keys($manifest)) . " -->" . "\n";
                return;
            }

            $file = $manifest[$key];

            // CSS
            if (isset($file['css'])) {
                foreach ($file['css'] as $css) {
                    echo '<link rel="stylesheet" href="' . url('public/assets/' . $css) . '">' . "\n";
                }
            }

            // JS
            echo '<script type="module" src="' . url('public/assets/' . $file['file']) . '"></script>' . "\n";
        }
    }
}

/**
 * Gera URL para uma rota nomeada
 */
if (!function_exists('route')) {
    function route($name, $params = [])
    {
        return App\Core\Router::route($name, $params);
    }
}
