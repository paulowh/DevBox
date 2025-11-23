<?php

return [
  'connection' => env('DB_CONNECTION', 'mysql'),
  'host' => env('DB_HOST', 'localhost'),
  'port' => env('DB_PORT', 3306),
  'database' => env('DB_DATABASE', 'devbox'),
  'username' => env('DB_USERNAME', 'root'),
  'password' => env('DB_PASSWORD', ''),
  'charset' => 'utf8mb4',
  'collation' => 'utf8mb4_unicode_ci',
  'options' => [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES => false,
  ],
];
