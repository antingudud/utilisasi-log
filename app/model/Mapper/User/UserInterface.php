<?php
namespace App\Model\Mapper\User;
use App\Core\Database\AdapterInterface;
interface UsrMapperInterface
{
    public function setAdapter(AdapterInterface $db);
    public function find(Array $filter = [], $one = FALSE);
    public function createUser(Array $row);
    public function createUserCollection(Array $rows);
}
