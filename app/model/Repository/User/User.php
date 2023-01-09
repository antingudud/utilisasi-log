<?php
namespace App\Model\Repository\User;
use App\Model\User\User;
use App\Model\Mapper\User\UsrMapperInterface;

class Repo
{
    private $mapper;
    public function setMapper(UsrMapperInterface $usr)
    {
        $this->mapper = $usr;
        return $this;
    }

    public function findByUsername(String $username)
    {
        return $this->mapper->find(['username' => $username], true);
    }
}