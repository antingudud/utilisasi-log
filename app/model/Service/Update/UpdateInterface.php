<?php
namespace App\Model\Service\Update;
use App\Model\Repository\Transaction\Repo;
use App\Model\Repository\User\Repo as RepoUser;

interface UpdateInterface
{
    public function __construct(Repo $transac);
    public function update(Array $list);
    public function validate(Array $list);
}
