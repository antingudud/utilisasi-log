<?php
namespace App\Core\Database;

use App\Core\ConnectDB;

interface AdapterInterface
{
    public function setConnection(ConnectDB $db);

    public function beginTransaction();
    public function commitTransaction();
    public function rollbackTransaction();

    public function insert(String $table, array $data, String $types = "");
    public function select(Array $column, String $table, array $data, String $types = "", String $order = "");
    public function update(String $table, array $data, array $identifier, String $types = "");
    public function delete(String $table, array $data, String $types = "");
}
