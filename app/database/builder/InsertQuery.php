<?php

namespace app\database\builder;

use app\database\Connection;

class InsertQuery
{
    private string $table;

    public static function table(string $tableName): self
    {
        $instance = new self;
        $instance->table = $tableName;
        return $instance;
    }

    public function save(array $valores): bool
    {
        $campos = implode(',', array_keys($valores));
        $parametros = ':' . implode(',:', array_keys($valores));
        $sql = "INSERT INTO {$this->table} ($campos) VALUES ($parametros)";
        $connection = Connection::open();
        $prepare = $connection->prepare($sql);
        return $prepare->execute($valores);
    }
}
