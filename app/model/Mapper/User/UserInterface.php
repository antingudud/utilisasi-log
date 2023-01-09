<?php
namespace App\Model\Mapper\User;
interface UsrMapperInterface
{
    public function find(Array $filter = [], $one = FALSE);
    public function createUser(Array $row);
    public function createUserCollection(Array $rows);
}
