<?php
namespace App\Model\User;

class User
{
    private $userNIK;
    private $username;
    private $fullname;
    private $password;
    private $rule;
    private $groupId;

    public function setNIK(Int $NIK)
    {
        $this->userNIK = $NIK;
        return $this;
    }
    public function getNIK()
    {
        return $this->userNIK;
    }

    public function setUsername(String $username)
    {
        $this->username = $username;
        return $this;
    }
    public function getUsername()
    {
        return $this->username;
    }

    public function setFullname(String $fullname)
    {
        $this->fullname = $fullname;
        return $this;
    }
    public function getFullname()
    {
        return $this->fullname;
    }

    public function setPassword(String $password)
    {
        $this->password = $password;
        return $this;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function setRule(Int $rule)
    {
        $this->rule = $rule;
        return $this;
    }
    public function getRule()
    {
        return $this->rule;
    }

    public function setGroupId(Int $id)
    {
        $this->groupId = $id;
        return $this;
    }
    public function getGroupId()
    {
        return $this->groupId;
    }
}

