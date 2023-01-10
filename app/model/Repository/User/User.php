<?php
namespace App\Model\Repository\User;
use App\Model\User\User;
use App\Model\Mapper\User\UserMapper;

class Repo
{
    private $mapper;
    public function __construct(UserMapper $usr)
    {
        $this->mapper = $usr;
    }

    public function findByUsername(String $username)
    {
        return $this->mapper->find(['username' => $username], true);
    }
}