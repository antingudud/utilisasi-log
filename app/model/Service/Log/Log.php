<?php
namespace App\Model\Service\Log;
use App\Model\Repository\Transaction\Repo;
use App\Model\Repository\User\Repo as RepoUser;
use App\Model\Transaction\Exception\InvalidValue;
use App\Model\Transaction\Exception\RecordExists;

class Log
{
    private $user;
    private $repo;

    function __construct(Repo $transac, RepoUser $usr)
    {
        $this->repo = $transac;
        $this->user = $usr;
    }

    /**
     * Log daily network usage to the database
     * 
     * @param Float $download
     * @param Float $upload
     * @param String $date The date of the network usage
     * @param String $idDevice The id of the device
     */
    public function log(float $download, float $upload, $date, String $idDevice)
    {
        $this->validate($download, $upload, $date, $idDevice);

        $username = "dummy";
        $id = substr(uniqid(), 5);
        $usr = $this->user->findByUsername($username);
        $dateCreated = date('Y-m-d H:i:s');
        
        if($this->repo->exists($idDevice, $date))
        {
            throw new RecordExists();
        }
        return $this->repo->log($download, $upload, $date, $idDevice, $id, $usr);
    }

    public function validate($download, $upload, $date, $idDevice)
    {
        $errors = [];
        if (preg_match('/[a-zA-Z]+/', $download) || !is_numeric($download)) {
            $errors = ['error'];
        }

        if (preg_match('/[a-zA-Z]+/', $upload) || !is_numeric($upload)) {
            $errors = ['error'];
        }

        if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $errors = ['error'];
        }

        if (empty($idDevice) || strlen($idDevice) > 8 || !preg_match('/^[a-zA-Z0-9]+$/', $idDevice)) {
            $errors = ['error'];
        }
        print_r([$download, $upload, $date, $idDevice]);

        if (!empty($errors))
        {
            throw new InvalidValue();
            return;
        }
    }
}