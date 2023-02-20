<?php
namespace App\Model\Repository\User;

use App\Core\Database\AdapterInterface;
use App\Model\User\User;
use App\Model\Mapper\User\UserMapper;

class Repo
{
    private $mapper;
    private $db;

    public function __construct(AdapterInterface $adapter)
    {
        $this->db = $adapter;
        $this->mapper = new UserMapper($this->db);
    }

    public function findByUsername(String $username)
    {
        return $this->mapper->find(['username' => $username], true);
    }
}