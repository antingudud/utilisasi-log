<?php
namespace App\Model\Service\Update;
use App\Model\Repository\Transaction\Repo;
use App\Model\Repository\User\Repo as RepoUser;

interface UpdateInterface
{
    public function setRepo(Repo $transac);
    public function setUser(RepoUser $usr);
    public function update(Array $list);
    public function validate(Array $list);
}
