<?php

namespace App\Model;

use App\Core\ConnectDB;

class Device
{
    public function getTransaction()
    {
        return (new DeviceService)->getTransaction();
    }

    public function log(Int $download, Int $upload, $date, String $idDevice)
    {
        if (empty($download) || !is_numeric($download)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'invalid download value']);
            return;
        }

        if (empty($upload) || !is_numeric($upload)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'invalid upload value']);
            return;
        }

        if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'invalid date value']);
            return;
        }

        if (empty($idDevice) || strlen($idDevice) > 8 || !preg_match('/^[a-zA-Z0-9]+$/', $idDevice)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid device ID']);
            return;
        }
        return (new DeviceService)->log($download, $upload, $date, $idDevice);
    }

    public function delete(array $id)
    {
        $errors = [];
        foreach ($id as $key => $value) {
            if (empty($value) || strlen($value) > 8 || !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                $errors = ['error' => 'Invalid device ID'];
            }
        }
        if (!empty($errors)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid device ID']);
            return;
        }
        $Newid = array_map(function ($value) {
            return ['idTrx' => $value];
        }, $id);
        return (new DeviceService)->delete($Newid);
    }

    public function update(Array $list)
    {
        $errors = [];
        foreach ($list as $key => $value) {
            if (empty($value['idTrx']) || strlen($value['idTrx']) > 8 || !preg_match('/^[a-zA-Z0-9]+$/', $value['idTrx'])) {
                $errors = ['error' => 'Invalid device ID'];
            }
        }
        foreach ($list as $ses => $sos) {
            if (!is_numeric($sos['download']) || empty($sos['download'])) {
                $errors = ['error' => 'Invalid download values'];
            }
        }
        foreach ($list as $sis => $sas) {
            if (!is_numeric($sas['upload']) || empty($sas['upload'])) {
                $errors = ['error' => 'Invalid upload values'];
            }
        }
        if (!empty($errors)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode($errors);
            return;
        }
        return (new DeviceService)->update($list);
    }

    public function getEditList(array $id)
    {
        $errors = [];
        foreach ($id['id'] as $key => $value) {
            if (empty($value) || strlen($value) > 8 || !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                $errors = ['error' => 'Invalid device ID'];
            }
        }
        if (!empty($errors)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid device ID']);
            return;
        }
        return (new DeviceService)->getEditList($id['id']);
    }
}


class DeviceService
{
    private $mapper;

    function __construct()
    {
        $this->mapper = (new DeviceMapper);
    }

    public function delete(array $id)
    {
        return $this->mapper->deleteById($id);
    }

    public function log(Int $download, Int $upload, $date, String $idDevice)
    {
        $idTrx = substr(uniqid(), 5);
        $username = "dummy";
        $userNIK = $this->mapper->select(['userNIK'], 'user', ['username' => $username])->fetch_row()[0];
        $groupId = $this->mapper->select(['groupId'], 'user', ['username' => $username])->fetch_row()[0];
        $dateCreated = date('Y-m-d H:i:s');
        return $this->mapper->insert("transaction", ['idTrx' => $idTrx, 'dateTime' => $date, 'download' => $download, 'upload' => $upload, 'userNIK' => $userNIK, 'dateCreated' => $dateCreated, 'dateModified' => '', 'groupId' => $groupId, 'idDevice' => $idDevice]);
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
        return $this->mapper->getEditList($id);
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
