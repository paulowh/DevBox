<?php

namespace App\Core;

use Exception;
use PDO;
use PDOException;

class Database
{
  private static $instance = null;
  private $connection;

  private function __construct()
  {
    $config = config('database');

    $dsn = sprintf(
      "%s:host=%s;port=%s;dbname=%s;charset=%s",
      $config['connection'],
      $config['host'],
      $config['port'],
      $config['database'],
      $config['charset']
    );

    try {
      $this->connection = new PDO(
        $dsn,
        $config['username'],
        $config['password'],
        $config['options']
      );
    } catch (PDOException $e) {
      die("Erro de conexão: " . $e->getMessage());
    }
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function getConnection()
  {
    return $this->connection;
  }

  public function query($sql, $params = [])
  {
    try {
      $stmt = $this->connection->prepare($sql);
      $stmt->execute($params);
      return $stmt;
    } catch (PDOException $e) {
      if (config('app.debug')) {
        die("Erro na query: " . $e->getMessage());
      }
      die("Erro ao executar consulta.");
    }
  }

  public function fetchAll($sql, $params = [])
  {
    return $this->query($sql, $params)->fetchAll();
  }

  public function fetch($sql, $params = [])
  {
    return $this->query($sql, $params)->fetch();
  }

  public function execute($sql, $params = [])
  {
    return $this->query($sql, $params)->rowCount();
  }

  public function lastInsertId()
  {
    return $this->connection->lastInsertId();
  }

  // Previne clonagem
  private function __clone()
  {
  }

  // Previne unserialize
  public function __wakeup()
  {
    throw new Exception("Não é possível deserializar singleton");
  }
}
