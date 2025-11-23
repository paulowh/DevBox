<?php

namespace App\Core;

use DatabaseSeeder;
use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;

class DatabaseInitializer
{
  private static $flagFile = null;

  public static function init()
  {
    self::$flagFile = storage_path('installed.flag');

    // Se já foi instalado, não faz nada
    if (self::isInstalled()) {
      return;
    }

    try {
      // Verifica se consegue conectar ao banco
      self::checkDatabaseConnection();

      // Roda as migrations
      self::runMigrations();

      // Roda os seeders
      self::runSeeders();

      // Marca como instalado
      self::markAsInstalled();

      error_log("✓ Sistema inicializado com sucesso!");
    } catch (Exception $e) {
      error_log("✗ Erro ao inicializar sistema: " . $e->getMessage());
      // Não marca como instalado se houver erro
    }
  }

  private static function isInstalled()
  {
    return file_exists(self::$flagFile);
  }

  private static function checkDatabaseConnection()
  {
    try {
      Capsule::connection()->getPdo();
    } catch (Exception $e) {
      throw new Exception("Não foi possível conectar ao banco de dados. Verifique as configurações.");
    }
  }

  private static function runMigrations()
  {
    error_log("→ Executando migrations...");

    $migrationsPath = base_path('app/Database/migrations');
    $migrationFiles = self::getMigrationFiles($migrationsPath);

    foreach ($migrationFiles as $file) {
      require_once $migrationsPath . '/' . $file;

      $className = self::getMigrationClassName($file);

      if (class_exists($className)) {
        $migration = new $className();
        if (method_exists($migration, 'up')) {
          $migration->up();
          error_log("  ✓ Executada: {$file}");
        }
      }
    }
  }

  private static function runSeeders()
  {
    error_log("→ Executando seeders...");

    $seederFile = base_path('app/Database/DatabaseSeeder.php');

    if (file_exists($seederFile)) {
      require_once $seederFile;

      if (class_exists('DatabaseSeeder')) {
        $seeder = new DatabaseSeeder();
        if (method_exists($seeder, 'run')) {
          $seeder->run();
          error_log("  ✓ Seeders executados com sucesso!");
        }
      }
    }
  }

  private static function markAsInstalled()
  {
    $dir = dirname(self::$flagFile);
    if (!is_dir($dir)) {
      mkdir($dir, 0755, true);
    }

    file_put_contents(self::$flagFile, date('Y-m-d H:i:s'));
  }

  private static function getMigrationFiles($path)
  {
    if (!is_dir($path)) {
      return [];
    }

    $files = scandir($path);
    $phpFiles = array_filter($files, function ($file) {
      return pathinfo($file, PATHINFO_EXTENSION) === 'php';
    });

    sort($phpFiles);
    return $phpFiles;
  }

  private static function getMigrationClassName($file)
  {
    // Remove .php
    $name = pathinfo($file, PATHINFO_FILENAME);

    // Remove timestamp (formato: 2024_11_22_000002_create_cursos_table)
    $parts = explode('_', $name);

    // Pega tudo após a data (ignora ano_mes_dia_numero)
    if (count($parts) > 4) {
      $parts = array_slice($parts, 4);
    }

    // Converte para PascalCase
    $className = str_replace('_', '', ucwords(implode('_', $parts), '_'));

    return $className;
  }

  /**
   * Remove a flag de instalação (útil para desenvolvimento)
   */
  public static function reset()
  {
    if (file_exists(self::$flagFile)) {
      unlink(self::$flagFile);
      error_log("✓ Flag de instalação removida. O sistema será reinicializado no próximo acesso.");
    }
  }
}
