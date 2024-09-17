<?php

namespace app\database\builder;

use app\database\Connection;

class UpdateQuery
{
    private string $table;
    private array $fieldsAndValues = [];
    private array $where = [];
    private array $binds = [];
    public function executeQuery($query)
    {
        $connection = Connection::open();
        $prepare = $connection->prepare($query);
        return $prepare->execute($this->binds ?? []);
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
    private function createQuery()
    {
        if (!$this->table) {
            throw new \Exception("A consulta precisa invocar o método table.");
        }
        if (!$this->fieldsAndValues) {
            throw new \Exception("A consulta precisa dos dados para realizar a atualização.");
        }
        $query = '';
        $query = "update {$this->table} set ";
        foreach ($this->fieldsAndValues as $field => $value) {
            $query .= "{$field} = :{$field},";
            $this->binds[$field] = $value;
        }
        $query = rtrim($query, ',');
        $query .= (isset($this->where) and (count($this->where) > 0)) ? ' where ' . implode(' ', $this->where) : '';
        return $query;
    }
    public function update()
    {
        $query = $this->createQuery();
        try {
            return $this->executeQuery($query);
        } catch (\PDOException $e) {
            throw new \Exception("Restrição: {$e->getMessage()}, SQL: " . $query);
        }
    }
    public static function table(string $table)
    {
        $self = new self;
        $self->table = $table;
        return $self;
    }
    public function set(array $fieldsAndValues)
    {
        $this->fieldsAndValues = $fieldsAndValues;
        return $this;
    }
}