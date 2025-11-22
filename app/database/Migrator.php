<?php

namespace App\Database;

use App\Core\Database;

class Migrator
{
    private $db;
    private $migrationsPath;
    private $migrationsTable = 'migrations';

    public function __construct($migrationsPath = null)
    {
        $this->db = Database::getInstance()->getConnection();
        $this->migrationsPath = $migrationsPath ?? base_path('app/database/migrations');
        $this->createMigrationsTable();
    }

    private function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->migrationsTable}` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `migration` VARCHAR(255) NOT NULL,
            `batch` INT NOT NULL,
            `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
    }

    public function run()
    {
        $executed = $this->getExecutedMigrations();
        $migrations = $this->getMigrationFiles();
        $batch = $this->getNextBatchNumber();

        $newMigrations = array_diff($migrations, $executed);

        if (empty($newMigrations)) {
            echo "Nenhuma migration para executar.\n";
            return;
        }

        foreach ($newMigrations as $migration) {
            $this->runMigration($migration, $batch);
        }

        echo "Migrations executadas com sucesso!\n";
    }

    public function rollback($steps = 1)
    {
        $batches = $this->getExecutedBatches();

        if (empty($batches)) {
            echo "Nenhuma migration para reverter.\n";
            return;
        }

        $batchesToRollback = array_slice($batches, 0, $steps);

        foreach ($batchesToRollback as $batch) {
            $migrations = $this->getMigrationsByBatch($batch);

            foreach (array_reverse($migrations) as $migration) {
                $this->rollbackMigration($migration);
            }
        }

        echo "Rollback executado com sucesso!\n";
    }

    public function reset()
    {
        $migrations = $this->getExecutedMigrations();

        foreach (array_reverse($migrations) as $migration) {
            $this->rollbackMigration($migration);
        }

        echo "Reset executado com sucesso!\n";
    }

    public function fresh()
    {
        $this->reset();
        $this->run();
    }

    private function runMigration($migrationFile, $batch)
    {
        $migrationClass = $this->getMigrationClass($migrationFile);

        if (!class_exists($migrationClass)) {
            require_once $this->migrationsPath . '/' . $migrationFile;
        }

        $migration = new $migrationClass();
        $migration->up();

        $this->logMigration($migrationFile, $batch);
        echo "Executada: {$migrationFile}\n";
    }

    private function rollbackMigration($migrationFile)
    {
        $migrationClass = $this->getMigrationClass($migrationFile);

        if (!class_exists($migrationClass)) {
            require_once $this->migrationsPath . '/' . $migrationFile;
        }

        $migration = new $migrationClass();
        $migration->down();

        $this->removeMigration($migrationFile);
        echo "Revertida: {$migrationFile}\n";
    }

    private function getMigrationFiles()
    {
        if (!is_dir($this->migrationsPath)) {
            mkdir($this->migrationsPath, 0755, true);
            return [];
        }

        $files = scandir($this->migrationsPath);
        return array_filter($files, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'php';
        });
    }

    private function getExecutedMigrations()
    {
        $stmt = $this->db->query("SELECT migration FROM {$this->migrationsTable} ORDER BY id");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getExecutedBatches()
    {
        $stmt = $this->db->query("SELECT DISTINCT batch FROM {$this->migrationsTable} ORDER BY batch DESC");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getMigrationsByBatch($batch)
    {
        $stmt = $this->db->prepare("SELECT migration FROM {$this->migrationsTable} WHERE batch = ? ORDER BY id");
        $stmt->execute([$batch]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getNextBatchNumber()
    {
        $stmt = $this->db->query("SELECT MAX(batch) as max_batch FROM {$this->migrationsTable}");
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        return ($result->max_batch ?? 0) + 1;
    }

    private function logMigration($migration, $batch)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->migrationsTable} (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
    }

    private function removeMigration($migration)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->migrationsTable} WHERE migration = ?");
        $stmt->execute([$migration]);
    }

    private function getMigrationClass($migrationFile)
    {
        $className = pathinfo($migrationFile, PATHINFO_FILENAME);
        // Remove timestamp (assumindo formato: 2024_01_01_000000_create_users_table.php)
        $parts = explode('_', $className);
        $parts = array_slice($parts, 4); // Remove os 4 primeiros (ano_mes_dia_hora)
        $className = implode('_', $parts);
        $className = str_replace('_', '', ucwords($className, '_'));

        return 'App\\Database\\Migrations\\' . $className;
    }
}
