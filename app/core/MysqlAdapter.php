<?php
namespace App\Core\Database;
use App\Core\ConnectDB;
use App\Core\Database\AdapterInterface;

class MysqliAdapter implements AdapterInterface
{
    private $db;
    public function setConnection(ConnectDB $db)
    {
        $this->db = $db->connectTo();
        return $this;
    }

    public function beginTransaction()
    {
        $this->db->begin_transaction();
        return $this;
    }
    public function commitTransaction()
    {
        $this->db->commit();
        return $this;
    }
    public function rollbackTransaction()
    {
        $this->db->rollback();
        return $this;
    }

    public function insert(string $table, array $data, string $types = "")
    {
        $types = $types ?: str_repeat("s", count($data));
        $keys = array_keys($data);
        $fields = implode(",", $keys);
        $table = $this->db->real_escape_string($table);
        $placeholders = str_repeat('?, ', count($keys) - 1) . '?';

        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...array_values($data));
        if (!$stmt->execute()) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["error" => $stmt->error]);
        }
    }
    public function select(array $column, string $table, array $data, string $types = "", string $order = "")
    {
        $types = $types ?: str_repeat("s", count($data));
        $column = $column ?: ['*'];
        $column = implode(",", $column);
        $table = $this->db->real_escape_string($table);
        $whereClause = "";

        foreach ($data as $key => $value) {
            $whereClause .= "$key = ? AND ";
        }
        $whereClause = substr_replace($whereClause, '', -5, 4);

        $query = "SELECT $column FROM $table WHERE ($whereClause) $order";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...array_values($data));
        $stmt->execute();
        return $stmt->get_result();
    }
    public function update(string $table, array $data, array $identifier, string $types = "")
    {
        $types = $types ?: str_repeat("s", count($data));
        $keys = array_keys($data);
        $table = $this->db->real_escape_string($table);
        $clause = "";
        $whereClause = "";

        foreach ($data as $key => $value) {
            $clause .= "$key = ?, ";
        }
        foreach ($identifier as $key => $value) {
            $whereClause .= "$key = ? AND ";
        }
        $clause = substr_replace($clause, '', -2, 2);
        $whereClause = substr_replace($whereClause, '', -5, 4);

        $query = "UPDATE $table SET " . $clause . " WHERE (" . $whereClause . ")";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...array_merge(array_values($data), array_values($identifier)));
        $stmt->execute();
    }
    public function delete(string $table, array $data, string $types = "")
    {
        $types = $types ?: str_repeat("s", count($data));
        $table = $this->db->real_escape_string($table);
        $whereClause = "";

        foreach ($data as $key => $value) {
            $whereClause .= "$key = ? AND ";
        }
        $whereClause = substr_replace($whereClause, '', -5, 4);

        $query = "DELETE FROM $table WHERE ($whereClause)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...array_values($data));
        if(!$stmt->execute())
        {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["error" => $stmt->error]);
        }
    }
}