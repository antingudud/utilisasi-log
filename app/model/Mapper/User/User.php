<?php
namespace App\Model\Mapper\User;
use App\Core\Database\AdapterInterface;
use App\Model\User\User;
use App\Model\Mapper\User\UsrMapperInterface;

class UserMapper implements UsrMapperInterface
{
    private $db;
    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    public function find(Array $filter = [], $one = FALSE)
    {
        $rows = $this->db->select(['*'], "user", $filter)->fetch_all(MYSQLI_ASSOC);

        if($one)
        {
            return $this->createUser($rows[0]);
        }

        return $this->createUserCollection($rows);
    }

    public function createUser(Array $row)
    {
        $usr = new User();

        $usr->setNIK($row['userNIK']);
        $usr->setUsername($row['username']);
        $usr->setFullname($row['fullname']);
        $usr->setPassword($row['password']);
        $usr->setRule($row['rule']);
        $usr->setGroupId($row['groupId']);

        return $usr;
    }

    public function createUserCollection(Array $rows)
    {

    }

}