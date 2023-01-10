<?php
namespace App\Model\Service\Delete;
use App\Model\Repository\Transaction\Repo;

interface DeleteInterface
{
    public function __construct(Repo $repo);
    public function delete(Array $list);
    public function validate(Array $list);
}
