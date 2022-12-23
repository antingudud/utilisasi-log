<?php
namespace App\Model;
use App\Core\ConnectDB;
class DataMapper
{
    protected static $db;
    function __construct()
    {
        self::$db = (new ConnectDB)->connectTo();
    }

    public static function insert(String $table, array $data, String $types = "")
    {
        $types = $types ?: str_repeat("s", count($data));
        $keys = array_keys($data);
        $fields = implode(",", $keys);
        $table = self::$db->real_escape_string($table);
        $placeholders = str_repeat('?, ', count($keys) - 1) . '?';

        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param($types, ...array_values($data));
        if (!$stmt->execute()) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["error" => $stmt->error]);
        }
        return;
    }

    public static function select(array $column, String $table, array $data, String $types = "", String $order = "")
    {
        $types = $types ?: str_repeat("s", count($data));
        $column = $column ?: ['*'];
        $column = implode(",", $column);
        $table = self::$db->real_escape_string($table);
        $whereClause = "";

        foreach ($data as $key => $value) {
            $whereClause .= "$key = ? AND ";
        }
        $whereClause = substr_replace($whereClause, '', -5, 4);

        $query = "SELECT $column FROM $table WHERE ($whereClause) $order";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param($types, ...array_values($data));
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function update(String $table, array $data, array $identifier, String $types = "")
    {
        $types = $types ?: str_repeat("s", count($data));
        $keys = array_keys($data);
        $table = self::$db->real_escape_string($table);
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
        $stmt = self::$db->prepare($query);
        $stmt->bind_param($types, ...array_merge(array_values($data), array_values($identifier)));
        $stmt->execute();
    }
    public static function delete(String $table, array $data, String $types = "")
    {
        $types = $types ?: str_repeat("s", count($data));
        $table = self::$db->real_escape_string($table);
        $whereClause = "";

        foreach ($data as $key => $value) {
            $whereClause .= "$key = ? AND ";
        }
        $whereClause = substr_replace($whereClause, '', -5, 4);

        $query = "DELETE FROM $table WHERE ($whereClause)";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param($types, ...array_values($data));
        if(!$stmt->execute())
        {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["error" => $stmt->error]);
        }
        return;
    }
}