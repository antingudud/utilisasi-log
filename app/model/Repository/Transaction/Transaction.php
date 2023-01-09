<?php
namespace App\Model\Repository\Transaction;
use App\Model\Transac;
use App\Model\User\User;
use App\Model\Device;
use App\Model\Mapper\Transaction\Mapper;
use App\Model\Repository\Device\DeviceRepo;

class Repo
{
    private $mapper;
    private $device;

    public function setMapper(Mapper $mp)
    {
        $this->mapper = $mp;
        return $this;
    }
    public function setDeviceRepo(DeviceRepo $repo)
    {
        $this->device = $repo;
        return $this;
    }

    public function getSpreadsheetView()
    {
        return $this->mapper->getSpreadsheetView();
    }

    public function findById(String $id)
    {
        return $this->mapper->find(['idTrx' => $id], true);
    }

    public function exists(String $idDevice, String $date)
    {
        return $this->mapper->existsOnThatDay($idDevice, $date);
    }

    public function fetchSemesterChart(String $idDevice, Int $year, Int $selectedTime)
    {
        $datas = $this->mapper->fetchSemesterData($idDevice, $year, $selectedTime);
        if(!$datas){
            return[[0], 'NOT FOUND', [0], [0]];
        }
        foreach ($datas as $data){
            $date[] = $data['month'] . " " . $data['date'];
            $download[] = $data['download'];
            $upload[] = $data['upload'];
        }
        $nameDevice = $data['nameDevice'];
        return [$date, $nameDevice, $download, $upload];
    }
    public function fetchMonthChart(String $idDevice, Int $year, Int $selectedTime)
    {
        $datas = $this->mapper->fetchMonthData($idDevice, $year, $selectedTime);
        if(!$datas){
            return[[0], 'NOT FOUND', [0], [0]];
        }
        foreach ($datas as $data){
            $date[] = $data['month'] . " " . $data['date'];
            $download[] = $data['download'];
            $upload[] = $data['upload'];
        }
        $nameDevice = $data['nameDevice'];

        return [$date, $nameDevice, $download, $upload];
    }

    public function log(float $download, float $upload, String $date, String $idDevice, String $id, User $usr)
    {
        $dateCreated = date('Y-m-d H:i:s');
        $dv = $this->device->create($idDevice);

        $tr = $this->create(['idTrx' => $id]);
        $tr->setDownload($download);
        $tr->setUpload($upload);
        $tr->setDate($date);
        $tr->setDateCreated($dateCreated);
        $tr->addUser($usr);
        $tr->setDevice($dv);

        return $this->mapper->save($tr);
    }

    public function update(Array $list)
    {
        $tr = $this->createCollection($list);

        $dateModified = date('Y-m-d H:i:s');
        $this->mapper->beginTransac();
        try
        {
            foreach ($tr as $key => $value) {
                $value->setDownload($list[$key]['download']);
                $value->setUpload($list[$key]['upload']);
                $value->setDateModified($dateModified);

                $this->mapper->save($value);
            }
            return $this->mapper->commitTransac();
        }
        catch (\Throwable $th)
        {
            return $this->mapper->rollbackTransac();
        }
    }

    public function store(Transac $tr)
    {
        return $this->mapper->save($tr);
    }
    public function delete(Array $list)
    {
        $tr = $this->createCollection($list);
        $this->mapper->beginTransac();

        try {
            foreach($tr as $key => $value)
            {
                $this->mapper->remove($value);
            }

            return $this->mapper->commitTransac();

        } catch (\Throwable $th) {
            return $this->mapper->rollbackTransac();
        }
    }

    public function create(Array $rows = [])
    {
        $tr = new Transac();
        $id = substr(uniqid(), 5);
        
        $tr->setId($id);
        return $tr;
    }
    public function createCollection(Array $rows)
    {
        foreach($rows as $key => $value)
        {
            $collection[] = $this->mapper->find(['idTrx' => $value['idTrx']], true);
        }
        return $collection;
    }
}