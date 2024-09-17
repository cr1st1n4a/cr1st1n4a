<?php

namespace app\database\builder;

use app\database\Connection;

class SelectQuery
{
    private ?string $table = null;
    private ?string $fields = null;
    private string $order;
    private string $group;
    private int $limit = 10;
    private int $offset = 0;
    private array $where = [];
    private array $join = [];
    private array $binds = [];
    private string $limits;
    public static function execSQL(string $sql, $fetchAll = true)
    {
        $self = new self;
        try {
            $connection = Connection::open();
            $query = $connection->query($sql);
            $resp = $query->execute();
            if ($fetchAll) {
                return $fetchAll ? $query->fetchAll() : $query->fetch();
            } else {
                return $resp;
            }
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
        $this->table = '';
        $this->table = $table;
        return $this;
    }
    public function where(string $field, string $operator, string|int $value, ?string $logic = null)
    {
        $placeHolder = '';
        $placeHolder = $field;
        if (str_contains($placeHolder, '.')) {
            $placeHolder = substr($field, strpos($field, '.') + 1);
        }
        $this->where[] = "{$field} {$operator} :{$placeHolder} {$logic}";
        $this->binds[$placeHolder] = $value;
        return $this;
    }
    public function order(string $field, string $value)
    {
        $this->order = " order by {$field} {$value}";
        return $this;
    }
    public function createQuery()
    {
        if (!$this->fields) {
            throw new \Exception("A query precisa chamar o metodo select");
        }
        if (!$this->table) {
            throw new \Exception("A query precisa chamar o metodo from");
        }
        $query = '';
        $query = 'select ';
        $query .= $this->fields . ' from ';
        $query .= $this->table;
        $query .= (isset($this->join) and (count($this->join) > 0)) ? implode(' ', $this->join) : '';
        $query .= (isset($this->where) and (count($this->where) > 0)) ? ' where ' . implode(' ', $this->where) : '';
        $query .= $this->group ?? '';
        $query .= $this->order ?? '';
        $query .= $this->limits ?? '';
        return $query;
    }
    public function join(string $foreingTable, string $logic, string $type = 'inner')
    {
        $this->join[] = " {$type} join {$foreingTable} on {$logic}";
        return $this;
    }
    public function group(string $field)
    {
        $this->group = " group by {$field}";
        return $this;
    }
    public function limit(int $limit, int $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->limits = " limit {$this->limit} offset {$this->offset}";
        return $this;
    }
    public function between(string $field, string|int $value1, string|int $value2)
    {
        $placeHolder1 = $field . '_1';
        $placeHolder2 = $field . '_2';
        $this->where[] = "{$field} between :{$placeHolder1} and :{$placeHolder2}";
        $this->binds[$placeHolder1] = $value1;
        $this->binds[$placeHolder2] = $value2;
        return $this;
    }
    public function fetch()
    {
        $query = '';
        $query = $this->createQuery();
        try {
            $connection = Connection::open();
            $prepare = $connection->prepare($query);
            $prepare->execute($this->binds ?? []);
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
            $prepare->execute($this->binds ?? []);
            return $prepare->fetchAll();
        } catch (\PDOException $e) {
            throw new \Exception("Restrição: {$e->getMessage()}");
        }
    }
}
