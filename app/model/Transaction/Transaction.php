<?php
namespace App\Model;
use App\Model\Device;
use App\Model\Repository\Transaction\Repo;
use App\Model\Transaction\Exception\RecordExists;
use App\Model\Repository\User\Repo as RepoUser;
use App\Model\Transaction\Exception\InvalidValue;
use App\Model\User\User;

class Transac
{
    private $idTrx;
    private $device;
    private $download;
    private $upload;
    private $date;
    private $dateModified;
    private $dateCreated;
    private $user;

    public function setId(String $id)
    {
        if(!$this->isValidStr($id))
        {
            throw new InvalidValue();
        }
        $this->idTrx = $id;
        return $this;
    }
    public function getId()
    {
        return $this->idTrx;
    }
    public function setDevice(Device $dvc)
    {
        $this->device = $dvc;
        return $this;
    }
    public function getDevice()
    {
        return $this->device;
    }

    public function setDownload(?float $download)
    {
        if(!$this->isValidNum($download))
        {
            throw new InvalidValue();
        }
        $this->download = $download;
        return $this;
    }
    public function getDownload()
    {
        return $this->download;
    }

    public function setUpload(?float $upload)
    {
        if(!$this->isValidNum($upload))
        {
            throw new InvalidValue();
        }
        $this->upload = $upload;
        return $this;
    }
    public function getUpload()
    {
        return $this->upload;
    }

    public function setDate(?string $date)
    {
        if(!$this->isValidStr($date))
        {
            throw new InvalidValue();
        }
        $this->date = $date;
        return $this;
    }
    public function getDate()
    {
        return $this->date;
    }

    public function setDateModified(?string $date)
    {
        if(!$this->isValidStr($date))
        {
            throw new InvalidValue();
        }
        $this->dateModified = $date;
        return $this;
    }
    public function getDateModified()
    {
        return $this->dateModified;
    }

    public function setDateCreated(?string $date)
    {
        if(!$this->isValidStr($date))
        {
            throw new InvalidValue();
        }
        $this->dateCreated = $date;
        return $this;
    }
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    public function addUser(User $usr)
    {
        $this->user = $usr;
        return $this;
    }
    public function getUser()
    {
        return $this->user;
    }

    private function isValidStr(String $str)
    {
        return ($this->isAllowedChar($str) || !$this->isEmpty($str))? true : false;
    }
    private function isValidNum($num)
    {
        return ($this->isNumber($num) || !$this->isEmpty($num))? true : false;
    }
    private function isAllowedChar(String $str)
    {
        return preg_match('/^[\-a-zA-Z0-9]+$/', $str)? false : true;
    }
    private function isEmpty($any)
    {
        return empty($any)? true : false;
    }
    private function isNumber($num)
    {
        return is_numeric($num)? true : false;
    }
}

class TransacService {
    private $repo;
    private $user;

    function __construct(Repo $transac)
    {
        $this->repo = $transac;
    }
    public function setUser(RepoUser $usr)
    {
        $this->user = $usr;
        return $this;
    }
    public function getAlterForm()
    {
        $result = $this->repo->getSpreadsheetView();
        return $result;
    }
    public function delete(array $id)
    {
        foreach($id as $key => $value)
        {
            $Transac[] = $this->createTransac("", $value['idTrx']);
        }
        foreach($Transac as $key => $value)
        {
            $ids[] = ['idTrx' => $value->getId()];
        }
        return $this->mapper->deleteById($ids);
    }
    public function log(float $download, float $upload, $date, String $idDevice)
    {
        $username = "dummy";
        // $userNIK = $this->mapper->select(['userNIK'], 'user', ['username' => $username])->fetch_row()[0];
        // $groupId = $this->mapper->select(['groupId'], 'user', ['username' => $username])->fetch_row()[0];
        // print_r($this->user->findByUsername($username));
        $dateCreated = date('Y-m-d H:i:s');
        // $tr = $this->createTransac($idDevice, substr(uniqid(), 5), $download, $upload, $date, "", $dateCreated, $userNIK, $groupId);
        // if($this->mapper->exists($tr))
        // {
        //     header('HTTP/1.1 400 Bad Request');
        //     throw new RecordExists();
        // }
        // return $this->repo->store($tr);
    }
    public function update(Array $list)
    {
        foreach($list as $key => $value)
        {
            $Transac[] = $this->createTransac("", $value['idTrx'], $value['download'], $value['upload'], "", date('Y-m-d H:i:s'));
        }
        return $this->mapper->updateList($Transac);
    }

    public function createTransac(String $idDevice, String $id, float $download = 0, float $upload = 0, String $date = "1970-1-1", String $dateModified = "1970-1-1", String $dateCreated = "1970-1-1", int $userNIK = 0, Int $groupId = 0)
    {
        $Transac = new Transac;
        $Device = new Device;
        $User = new User();
        
        $User->setNIK($userNIK);
        $User->setGroupId($groupId);
        $Device->setIdDevice($idDevice);

        $Transac->setId($id);
        $Transac->setDevice($Device);
        $Transac->setDownload($download);
        $Transac->setUpload($upload);
        $Transac->setDate($date);
        $Transac->setDateModified($dateModified);
        $Transac->setDateCreated($dateCreated);
        $Transac->addUser($User);

        return $Transac;
    }
}