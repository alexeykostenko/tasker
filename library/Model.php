<?php 

namespace Library;

use \PDO;

abstract class Model
{
    private $connection;
    private $model;
    private $select = '*';
    private $where = '1';
    private $stmt;
    private $column;
    private $operator;
    private $value;
    private $orderBy;
    protected $timestamps = true;

    public function __construct(PDO $connection = null)
    {
        $this->connection = $connection;
        if ($this->connection === null) {
            $this->connection = new PDO(
                'mysql:host=' . config('host') . ';dbname=' . config('database'), 
                config('username'), 
                config('password')
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE, 
                PDO::ERRMODE_EXCEPTION
            );
        }

        $this->model = get_called_class();
    }

    public function find($id)
    {
        $stmt = $this->connection->prepare("
            SELECT * 
             FROM $this->table
             WHERE id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Set the fetchmode to populate an instance of 'Model'
        // This enables us to use the following:
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        return $stmt->fetch();
    }

    public function all()
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM $this->table
        ");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);

        // fetchAll() will do the same as above, but we'll have an array. ie:
        return $stmt->fetchAll();
    }

    public function count()
    {
        $this->stmt = $this->connection->prepare("
            SELECT count(*)
            FROM $this->table
            WHERE $this->where
        ");

        $this->stmt->bindParam(":$this->column", $this->value);

        $this->stmt->execute();
        return $this->stmt->fetchColumn();
    }

    public function select($select)
    {
        $this->select = $select;
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $this->where = "$column $operator :$column";
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;

        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->orderBy = "$column $direction";

        return $this;
    }

    public function whereIn($column, array $values)
    {
        $inQuery = implode(',', array_fill(0, count($values), '?'));

        $this->stmt = $this->connection->prepare("
            SELECT $this->select
            FROM $this->table
            WHERE $column in ($inQuery)
        ");

        foreach ($values as $key => $value) {
            $this->stmt->bindValue($key + 1, $value);
        }

        return $this;
    }

    public function get()
    {
        $query = "
            SELECT $this->select
            FROM $this->table
            WHERE $this->where
        ";

        if ($this->orderBy) {
            $query .= " ORDER BY $this->orderBy";
        }

        $this->stmt = $this->connection->prepare($query);

        $this->stmt->bindParam(":$this->column", $this->value);
        $this->stmt->execute();
        $this->stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);

        return $this->stmt->fetchAll();
    }

    public function create(array $data)
    {
        if ($this->timestamps) {
            $currentTime = date('Y-m-d h:i:s');
            $data['created_at'] = $currentTime;
            $data['updated_at'] = $currentTime;
        }

        $columns = array_keys($data);

        if (isset($this->fillable)) {
            $columns = array_intersect($this->fillable, $columns);
        }

        $stmt = $this->connection->prepare("
            INSERT INTO $this->table
                (" . implode(',', $columns) . ")
            VALUES
                (:" . implode(', :', $columns) . ")
        ");

        foreach ($columns as $column) {
            $stmt->bindParam(':' . $column, $data[$column]);
        }

        $stmt->execute();

        return (int) $this->connection->lastInsertId();
    }

    public function update(array $data, $id)
    {
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d h:i:s');
        }

        $columns = array_keys($data);

        if (isset($this->fillable)) {
            $columns = array_intersect($this->fillable, $columns);
        }

        $set = '';

        foreach ($columns as $column) {
            $set .= "$column = :$column, ";
        }

        $set = rtrim($set, ', ');

        $stmt = $this->connection->prepare("
            UPDATE $this->table
            SET $set
            WHERE id = :id
        ");

        foreach ($columns as $column) {
            $stmt->bindParam(':' . $column, $data[$column]);
        }

        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("
            DELETE
             FROM $this->table
             WHERE id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->execute();
    }
}
