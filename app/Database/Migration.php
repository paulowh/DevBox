<?php

namespace App\Database;

use App\Core\Database;

abstract class Migration
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    abstract public function up();
    abstract public function down();

    protected function createTable($tableName, callable $callback)
    {
        $schema = new Schema();
        $callback($schema);

        $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (";
        $sql .= implode(', ', $schema->getColumns());

        if (!empty($schema->getPrimaryKey())) {
            $sql .= ', PRIMARY KEY (' . implode(', ', $schema->getPrimaryKey()) . ')';
        }

        if (!empty($schema->getForeignKeys())) {
            foreach ($schema->getForeignKeys() as $fk) {
                $sql .= ", FOREIGN KEY ({$fk['column']}) REFERENCES {$fk['references']}({$fk['on']})";
                if (isset($fk['onDelete'])) {
                    $sql .= " ON DELETE {$fk['onDelete']}";
                }
                if (isset($fk['onUpdate'])) {
                    $sql .= " ON UPDATE {$fk['onUpdate']}";
                }
            }
        }

        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $this->db->exec($sql);
    }

    protected function dropTable($tableName)
    {
        $sql = "DROP TABLE IF EXISTS `{$tableName}`";
        $this->db->exec($sql);
    }

    protected function addColumn($tableName, $columnName, $type)
    {
        $sql = "ALTER TABLE `{$tableName}` ADD COLUMN `{$columnName}` {$type}";
        $this->db->exec($sql);
    }

    protected function dropColumn($tableName, $columnName)
    {
        $sql = "ALTER TABLE `{$tableName}` DROP COLUMN `{$columnName}`";
        $this->db->exec($sql);
    }
}

class Schema
{
    private $columns = [];
    private $primaryKey = [];
    private $foreignKeys = [];

    public function id($name = 'id')
    {
        $this->columns[] = "`{$name}` BIGINT UNSIGNED AUTO_INCREMENT";
        $this->primaryKey[] = "`{$name}`";
        return $this;
    }

    public function string($name, $length = 255)
    {
        $this->columns[] = "`{$name}` VARCHAR({$length})";
        return $this;
    }

    public function text($name)
    {
        $this->columns[] = "`{$name}` TEXT";
        return $this;
    }

    public function integer($name)
    {
        $this->columns[] = "`{$name}` INT";
        return $this;
    }

    public function bigInteger($name)
    {
        $this->columns[] = "`{$name}` BIGINT";
        return $this;
    }

    public function decimal($name, $precision = 8, $scale = 2)
    {
        $this->columns[] = "`{$name}` DECIMAL({$precision}, {$scale})";
        return $this;
    }

    public function boolean($name)
    {
        $this->columns[] = "`{$name}` TINYINT(1) DEFAULT 0";
        return $this;
    }

    public function date($name)
    {
        $this->columns[] = "`{$name}` DATE";
        return $this;
    }

    public function datetime($name)
    {
        $this->columns[] = "`{$name}` DATETIME";
        return $this;
    }

    public function timestamp($name)
    {
        $this->columns[] = "`{$name}` TIMESTAMP";
        return $this;
    }

    public function timestamps()
    {
        $this->columns[] = "`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] = "`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }

    public function nullable()
    {
        $lastIndex = count($this->columns) - 1;
        $this->columns[$lastIndex] .= " NULL";
        return $this;
    }

    public function notNullable()
    {
        $lastIndex = count($this->columns) - 1;
        $this->columns[$lastIndex] .= " NOT NULL";
        return $this;
    }

    public function unique()
    {
        $lastIndex = count($this->columns) - 1;
        $this->columns[$lastIndex] .= " UNIQUE";
        return $this;
    }

    public function default($value)
    {
        $lastIndex = count($this->columns) - 1;
        if (is_string($value)) {
            $this->columns[$lastIndex] .= " DEFAULT '{$value}'";
        } else {
            $this->columns[$lastIndex] .= " DEFAULT {$value}";
        }
        return $this;
    }

    public function foreign($column)
    {
        return new ForeignKey($this, $column);
    }

    public function addForeignKey($column, $references, $on, $onDelete = null, $onUpdate = null)
    {
        $this->foreignKeys[] = [
            'column' => $column,
            'references' => $references,
            'on' => $on,
            'onDelete' => $onDelete,
            'onUpdate' => $onUpdate
        ];
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getForeignKeys()
    {
        return $this->foreignKeys;
    }
}

class ForeignKey
{
    private $schema;
    private $column;
    private $references;
    private $on;
    private $onDelete;
    private $onUpdate;

    public function __construct($schema, $column)
    {
        $this->schema = $schema;
        $this->column = $column;
    }

    public function references($references)
    {
        $this->references = $references;
        return $this;
    }

    public function on($on)
    {
        $this->on = $on;
        return $this;
    }

    public function onDelete($action)
    {
        $this->onDelete = $action;
        return $this;
    }

    public function onUpdate($action)
    {
        $this->onUpdate = $action;
        $this->schema->addForeignKey(
            $this->column,
            $this->references,
            $this->on,
            $this->onDelete,
            $this->onUpdate
        );
        return $this->schema;
    }

    public function __destruct()
    {
        if ($this->references && $this->on) {
            $this->schema->addForeignKey(
                $this->column,
                $this->references,
                $this->on,
                $this->onDelete,
                $this->onUpdate
            );
        }
    }
}
