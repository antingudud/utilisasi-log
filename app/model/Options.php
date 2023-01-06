<?php
namespace App\Model;
use App\Core\ConnectDB;
class Options
{
    private $data;
    function __construct(Array $postData)
    {
        $this->data = $postData;
    }
    public function getDevices()
    {
        $Service = new OptionsService;
        $category = $this->data['category'];
        echo json_encode($Service->getDevices($category));
    }
}
class OptionsMapper
{
    private $db;

    public function __construct()
    {
        $this->db = (new ConnectDB)->connectTo();
    }

    public function insert(String $table, Array $data, String $types = "")
    {
        $types = $types?: str_repeat("s", count($data));
        $keys = array_keys($data);
        $fields = implode(",", $keys);
        $table = $this->db->real_escape_string($table);
        $placeholders = str_repeat('?', count($keys) - 1) . '?';

        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...array_values($data));
        $stmt->execute();
    }

    public function select(Array $column, String $table, Array $data, String $types = "", String $order = "")
    {
        $types = $types?: str_repeat("s", count($data));
        $column = $column ?: ['*'];
        $column = implode(",", $column);
        $table = $this->db->real_escape_string($table);
        $whereClause = "";

        foreach($data as $key => $value) {
            $whereClause .= "$key = ? AND ";
        }
        $whereClause = substr_replace($whereClause, '', -5, 4);

        $query = "SELECT $column FROM $table WHERE ($whereClause) $order";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...array_values($data));
        $stmt->execute();
        return $stmt->get_result();
    }

    public function update(String $table, Array $data, Array $identifier, String $types = "")
    {
        $types = $types?: str_repeat("s", count($data));
        $keys = array_keys($data);
        $table = $this->db->real_escape_string($table);
        $clause = "";
        $whereClause = "";

        foreach($data as $key => $value)
        {
            $clause .= "$key = ?, ";
        }
        foreach($identifier as $key => $value)
        {
            $whereClause .= "$key = ? AND ";
        }
        $clause = substr_replace($clause, '', -2, 2);
        $whereClause = substr_replace($whereClause, '', -5, 4);

        $query = "UPDATE $table SET " .$clause . " WHERE (" . $whereClause .")";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...array_merge(array_values($data), array_values($identifier)));
        $stmt->execute();
    }
    public function delete(String $table, Array $data, String $types = "")
    {
        $types = $types?: str_repeat("s", count($data));
        $table = $this->db->real_escape_string($table);
        $whereClause = "";

        foreach($data as $key => $value) {
            $whereClause .= "$key = ? AND ";
        }
        $whereClause = substr_replace($whereClause, '', -5, 4);

        $query = "DELETE FROM $table WHERE ($whereClause)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...array_values($data));
        $stmt->execute();
    }

    public function findByCategory(String $category)
    {
        return $this->select(['device.nameDevice', 'device.idDevice'], "device LEFT JOIN category ON device.idCategory = category.idCategory", ["category.nameCategory" => $category], "", "ORDER BY FIELD(nameDevice, 'CR (Indihome)', 'CP (Indihome)', 'PK (Biznet)', 'PK (Indosat)', 'CK (Orbit)', 'CK (XL)')")->fetch_all(MYSQLI_ASSOC);
    }

    function __destruct()
    {
        unset($this->db);
    }
}
class OptionsService
{
    public function getDevices(String $category)
    {
        $mapper = (new OptionsMapper());
        return $mapper->findByCategory($category);
    }
}