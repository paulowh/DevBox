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
 * Gera URL para uma rota nomeada
 */
if (!function_exists('route')) {
  function route($name, $params = [])
  {
    return App\Core\Router::route($name, $params);
  }
}
