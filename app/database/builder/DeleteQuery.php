<?php

namespace app\database\builder;

use app\database\Connection;

class DeleteQuery
{
    private static $tabela;
    private $where = [];
    private $binds = [];
    public static function table(string $table): self
    {
        $self = new self;
        $self->tabela = $table;
        return $self;
    }
    private function createQuery()
    {
        if (!$this->tabela) {
            throw new \Exception("A consulta precisa invocar o método delete.");
        }
        $query = '';
        $query = "delete from {$this->tabela} ";
        $query .= (isset($this->where) and (count($this->where) > 0)) ? ' where ' . implode(' ', $this->where) : '';
        return $query;
    }
    public function executeQuery($query)
    {
        $connection = Connection::open();
        $prepare = $connection->prepare($query);
        return $prepare->execute($this->binds ?? []);
    }
    public function where(string $field, string $operator, string|int $value, ?string $logic = null): self
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
    public function delete(): bool
    {
        $query = $this->createQuery();
        try {
            return $this->executeQuery($query);
        } catch (\PDOException $e) {
            throw new \Exception("Restrição: {$e->getMessage()}");
        }
    }
}