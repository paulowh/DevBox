<?php

namespace App\Core;

use App\Core\Database;

abstract class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Busca todos os registros
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }

    /**
     * Busca um registro por ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Busca registros com condições
     */
    public function where($conditions = [], $operator = 'AND')
    {
        $where = [];
        $params = [];

        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = ?";
            $params[] = $value;
        }

        $whereClause = implode(" {$operator} ", $where);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Busca um registro com condições
     */
    public function findWhere($conditions = [])
    {
        $where = [];
        $params = [];

        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = ?";
            $params[] = $value;
        }

        $whereClause = implode(" AND ", $where);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause} LIMIT 1";

        return $this->db->fetch($sql, $params);
    }

    /**
     * Cria um novo registro
     */
    public function create($data)
    {
        $data = $this->filterFillable($data);

        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($values), '?');

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";

        $this->db->execute($sql, $values);

        return $this->find($this->db->lastInsertId());
    }

    /**
     * Atualiza um registro
     */
    public function update($id, $data)
    {
        $data = $this->filterFillable($data);

        $set = [];
        $values = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = ?";
            $values[] = $value;
        }

        $values[] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) .
            " WHERE {$this->primaryKey} = ?";

        $this->db->execute($sql, $values);

        return $this->find($id);
    }

    /**
     * Deleta um registro
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->execute($sql, [$id]) > 0;
    }

    /**
     * Conta registros
     */
    public function count($conditions = [])
    {
        if (empty($conditions)) {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $result = $this->db->fetch($sql);
        } else {
            $where = [];
            $params = [];

            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = ?";
                $params[] = $value;
            }

            $whereClause = implode(" AND ", $where);
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$whereClause}";
            $result = $this->db->fetch($sql, $params);
        }

        return $result->total ?? 0;
    }

    /**
     * Paginação
     */
    public function paginate($page = 1, $perPage = 15, $conditions = [])
    {
        $offset = ($page - 1) * $perPage;

        if (empty($conditions)) {
            $sql = "SELECT * FROM {$this->table} LIMIT ? OFFSET ?";
            $params = [$perPage, $offset];
        } else {
            $where = [];
            $params = [];

            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = ?";
                $params[] = $value;
            }

            $whereClause = implode(" AND ", $where);
            $sql = "SELECT * FROM {$this->table} WHERE {$whereClause} LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;
        }

        $items = $this->db->fetchAll($sql, $params);
        $total = $this->count($conditions);

        return [
            'data' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Filtra apenas os campos permitidos (fillable)
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Remove campos ocultos (hidden)
     */
    public function hideAttributes($data)
    {
        if (empty($this->hidden)) {
            return $data;
        }

        foreach ($this->hidden as $attribute) {
            unset($data->$attribute);
        }

        return $data;
    }

    /**
     * Query personalizada
     */
    public function query($sql, $params = [])
    {
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Executa query personalizada
     */
    public function execute($sql, $params = [])
    {
        return $this->db->execute($sql, $params);
    }
}
