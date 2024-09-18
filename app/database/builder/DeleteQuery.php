<?php

namespace app\database\builder;

use app\database\Connection;
use PDO;

class DeleteQuery
{
    private static $tabela;
    private $where = [];
    private $binds = [];

    // Define a tabela onde o delete será executado
    public static function table(string $table): self
    {
        $self = new self;
        $self->tabela = $table;
        return $self;
    }

    // Adiciona uma cláusula WHERE
    public function where(string $field, string $operator, string|int $value, ?string $logic = null): self
    {
        // Gera o placeholder do campo para ser usado nos binds
        $placeHolder = '';
        $placeHolder = $field;
        if (str_contains($placeHolder, '.')) {
            $placeHolder = substr($field, strpos($field, '.') + 1);
        }

        // Adiciona a condição WHERE
        $this->where[] = "{$field} {$operator} :{$placeHolder} {$logic}";
        $this->binds[$placeHolder] = $value;

        return $this;
    }
    // Método que executa o DELETE no banco de dados
    public function delete(): bool
    {
        $connection = Connection::open();

        // Construção da query DELETE
        $sql = "DELETE FROM " .self::$tabela;

        // Adiciona as condições do WHERE, se existirem
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' ', $this->where);
        }

        var_dump($sql);
        die;
        try {
            // Prepara a query
            $stmt = $connection->prepare($sql);

            // Executa o bind dos valores
            foreach ($this->binds as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }

            // Executa a query
            $stmt->execute();

            // Retorna true se a execução foi bem-sucedida
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            // Em caso de erro, retorna false e opcionalmente registra o erro
            // Você pode implementar um log de erro aqui, se necessário
            return false;
        }
    }
}
