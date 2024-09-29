<?php

namespace app\database\builder;

use app\database\Connection;

class SelectQuery
{
    private ?string $table = null;
    private ?string $fields = '*';
    private ?string $order = null;
    private ?string $group = null;
    private int $limit = 10;
    private int $offset = 0;
    private array $where = [];
    private array $join = [];
    private array $binds = [];
    private string $limits = '';

    public static function execSQL(string $sql, $fetchAll = true)
    {
        try {
            $connection = Connection::open();
            $query = $connection->query($sql);
            $resp = $query->execute();
            return $fetchAll ? $query->fetchAll() : $query->fetch();
        } catch (\PDOException $e) {
            throw new \Exception("Restrição: {$e->getMessage()}");
        }
    }

    public static function select(string $fields = '*')
    {
        $self = new self;
        $self->fields = $fields;
        return $self;
    }

    public function from(string $table)
    {
        $this->table = $table;
        return $this;
    }

    public function where(string $field, string $operator, string|int $value)
    {
        $placeHolder = $field;
        if (str_contains($placeHolder, '.')) {
            $placeHolder = substr($field, strpos($field, '.') + 1);
        }
        $this->where[] = "{$field} {$operator} :{$placeHolder}";
        $this->binds[$placeHolder] = $value;
        return $this;
    }

    public function andWhere(string $field, string $operator, string|int $value)
    {
        // Usar "AND" para concatenar a cláusula WHERE
        $this->where[] = "AND {$field} {$operator} :{$field}";
        $this->binds[$field] = $value;
        return $this;
    }

    public function order(string $field, string $value)
    {
        $this->order = " ORDER BY {$field} {$value}";
        return $this;
    }

    public function createQuery()
    {
        if (!$this->fields) {
            throw new \Exception("A query precisa chamar o método select");
        }
        if (!$this->table) {
            throw new \Exception("A query precisa chamar o método from");
        }
        $query = 'SELECT ';
        $query .= $this->fields . ' FROM ';
        $query .= $this->table;
        $query .= !empty($this->join) ? implode(' ', $this->join) : '';
        $query .= !empty($this->where) ? ' WHERE ' . ltrim(implode(' ', $this->where), 'AND ') : '';
        $query .= $this->group ?? '';
        $query .= $this->order ?? '';
        $query .= $this->limits;
        return $query;
    }

    public function join(string $foreingTable, string $logic, string $type = 'INNER')
    {
        $this->join[] = " {$type} JOIN {$foreingTable} ON {$logic}";
        return $this;
    }

    public function group(string $field)
    {
        $this->group = " GROUP BY {$field}";
        return $this;
    }

    public function limit(int $limit, int $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->limits = " LIMIT {$this->limit} OFFSET {$this->offset}";
        return $this;
    }

    public function between(string $field, string|int $value1, string|int $value2)
    {
        $placeHolder1 = $field . '_1';
        $placeHolder2 = $field . '_2';
        $this->where[] = "{$field} BETWEEN :{$placeHolder1} AND :{$placeHolder2}";
        $this->binds[$placeHolder1] = $value1;
        $this->binds[$placeHolder2] = $value2;
        return $this;
    }

    public function fetch()
    {
        $query = $this->createQuery();
        try {
            $connection = Connection::open();
            $prepare = $connection->prepare($query);
            $prepare->execute($this->binds);
            return $prepare->fetchObject();
        } catch (\PDOException $e) {
            throw new \Exception("Restrição: {$e->getMessage()}");
        }
    }

    public function fetchAll()
    {
        $query = $this->createQuery();
        try {
            $connection = Connection::open();
            $prepare = $connection->prepare($query);
            $prepare->execute($this->binds);
            return $prepare->fetchAll();
        } catch (\PDOException $e) {
            throw new \Exception("Restrição: {$e->getMessage()}");
        }
    }
}
