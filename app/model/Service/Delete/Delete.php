<?php
namespace App\Model\Service\Delete;
use App\Model\Repository\Transaction\Repo;
use App\Model\Transaction\Exception\InvalidValue;
use App\Model\Service\Delete\DeleteInterface;

class Delete implements DeleteInterface
{
    private $repo;
    public function setRepo(Repo $repo)
    {
        $this->repo = $repo;
        return $this;
    }

    public function delete(Array $list)
    {
        $this->validate($list);

        $Newid = array_map(function ($value) {
            return ['idTrx' => $value];
        }, $list);

        return $this->repo->delete($Newid);
    }
    public function validate(Array $list)
    {
        $errors = [];
        foreach ($list as $key => $value) {
            if (empty($value) || strlen($value) > 8 || !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                $errors = ['error' => 'Invalid device ID'];
            }
        }
        if (!empty($errors)) {
            throw new InvalidValue();
            return;
        }
    }
}