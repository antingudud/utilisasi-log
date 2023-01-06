<?php

namespace App\Model;

use App\Core\ConnectDB;

class Device
{
    private $idDevice;
    private $idCategory;
    private $nameDevice;

    public function setIdDevice(String $id)
    {
        $this->idDevice = $id;
        return $this;
    }
    public function getIdDevice()
    {
        return $this->idDevice;
    }

    public function setIdCategory(String $id)
    {
        $this->idCategory = $id;
        return $this;
    }
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    public function setName(String $name)
    {
        $this->nameDevice = $name;
        return $this;
    }
    public function getName()
    {
        return $this->nameDevice;
    }


    public function getTransaction()
    {
        return (new DeviceService)->getTransaction();
    }

    public function update(Array $list)
    {
        return (new DeviceService)->update($list);
    }
}

use App\Model\Transac;
class DeviceService
{
    private $mapper;

    function __construct()
    {
        $this->mapper = (new DeviceMapper);
    }

    public function update(Array $list)
    {
        return $this->mapper->updateList($list);
    }

    public function getTransaction()
    {
        return $this->mapper->select(['idTrx', 'DATE_FORMAT(dateTime, "%a, %e %b %Y") AS date', 'device.nameDevice', 'category.nameCategory', 'TRIM(transaction.download)+0 as download', 'TRIM(transaction.upload)+0 as upload', 'user.fullname', 'transaction.dateCreated', 'transaction.dateModified'], 'device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice LEFT JOIN category ON category.idCategory = device.idCategory LEFT JOIN user ON user.userNIK = transaction.userNIK', [1 => 1], "", "ORDER BY dateTime, device.nameDevice ASC")->fetch_all(MYSQLI_ASSOC);
    }
    public function getEditList(array $id)
    {
        return $this->mapper->getEditList($id['id']);
    }
}


use App\Model\DataMapper;


class DeviceMapper extends DataMapper
{
    public static function updateList(Array $list)
    {
        $db = parent::$db;
        $db->begin_transaction();
        foreach($list as $key => $value)
        {    
            $today = date('Y-m-d H:i:s');
            parent::update('transaction', ['download' => $value['download'], 'upload' => $value['upload'], 'dateModified' => $today], ['idTrx' => $value['idTrx']], "ddss");
        }
        $db->commit();
    }

    public static function deleteById(array $id)
    {
        parent::$db->begin_transaction();
        foreach ($id as $key => $value) {
            parent::delete('transaction', $value);
        }
        return parent::$db->commit();
    }

    public static function getEditList(array $id)
    {
        $query = "SELECT idTrx, DATE_FORMAT(dateTime, '%a, %e %b %Y') as date, category.nameCategory, device.nameDevice, TRIM(download)+0 as download, TRIM(upload)+0 as upload FROM device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice LEFT JOIN category ON category.idCategory = device.idCategory WHERE idTrx";
        $types = str_repeat("s", count($id));
        if (count($id) > 1) {
            $placeholders = str_repeat('?,', count($id) - 1) . '?';
            $query .= " IN ( {$placeholders} ) ORDER BY dateTime ASC";
            $stmt = parent::$db->prepare($query);
            $stmt->bind_param($types, ...$id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            $query .= " = ?";
            $stmt = parent::$db->prepare($query);
            $stmt->bind_param($types, ...$id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
    }
}
