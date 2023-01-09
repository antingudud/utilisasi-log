<?php
namespace App\Model\Service\Update;

use App\Model\Repository\Transaction\Repo;
use App\Model\Repository\User\Repo as RepoUser;
use App\Model\Transaction\Exception\InvalidValue;
use App\Model\Service\Update\UpdateInterface;

class Update implements UpdateInterface
{
    private $user;
    private $repo;

    function __construct(Repo $transac)
    {
        $this->repo = $transac;
    }
    public function setUser(RepoUser $usr)
    {
        $this->user = $usr;
        return $this;
    }

    public function update(Array $list)
    {
        $this->validate($list);
        return $this->repo->update($list);
    }

    public function validate(Array $list)
    {
        
        $errors = [];
        foreach ($list as $key => $value) {
            if (empty($value['idTrx']) || strlen($value['idTrx']) > 8 || !preg_match('/^[a-zA-Z0-9]+$/', $value['idTrx'])) {
                $errors = ['error' => 'Invalid device ID'];
            }
        }
        foreach ($list as $ses => $sos) {
            if (!is_numeric($sos['download']) || preg_match('/[a-zA-Z]+/', $sos['download']) || empty($sos['download'])) {
                $errors = ['error' => 'Invalid download values'];
            }
        }
        foreach ($list as $sis => $sas) {
            if (!is_numeric($sas['upload']) || preg_match('/[a-zA-Z]+/', $sas['upload']) || empty($sas['upload'])) {
                $errors = ['error' => 'Invalid upload values'];
            }
        }
        if (!empty($errors)) {
            throw new InvalidValue();
            return;
        }
    }
}