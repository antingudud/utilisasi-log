<?php

namespace App\Model\Mapper\Transaction;

use App\Model\Transac;
use App\Core\Database\AdapterInterface;
use App\Model\Mapper\Device\DvcInterface;
use App\Model\Mapper\User\UsrMapperInterface;

class Mapper
{
    private $db;
    private $user;
    private $device;
    private $transactionCollection;

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    public function setUserMapper(UsrMapperInterface $usr)
    {
        $this->user = $usr;
        return $this;
    }

    public function setDeviceMapper(DvcInterface $dvc)
    {
        $this->device = $dvc;
        return $this;
    }

    public function beginTransac()
    {
        return $this->db->beginTransaction();
    }
    public function commitTransac()
    {
        return $this->db->commitTransaction();
    }
    public function rollbackTransac()
    {
        return $this->db->rollbackTransaction();
    }

    public function find(Array $filter = [], $one = FALSE)
    {
        $rows = $this->db->select(['*'], "transaction", $filter)->fetch_all(MYSQLI_ASSOC);
        if($one)
        {
            return $this->createTransaction($rows[0]);
        }

        return $this->createTransactionCollection($rows);
    }

    public function save(Transac $tr)
    {
        $modified = $tr->getDateModified()?: '';

        if($this->exists($tr))
        {
            return $this->db->update(
                "transaction",
                ['download' => $tr->getDownload(), 'upload' => $tr->getUpload(), 'dateModified' => $modified],
                ['idTrx' => $tr->getId()],
                "ddss"
            );
        }

        return $this->db->insert
        (
            "transaction",
            [
                'idTrx' => $tr->getId(),
                'dateTime' => $tr->getDate(),
                'download' => $tr->getDownload(),
                'upload' => $tr->getUpload(),
                'userNIK' => $tr->getUser()->getNIK(),
                'dateCreated' => $tr->getDateCreated(),
                'dateModified' => $modified,
                'groupId' => $tr->getUser()->getGroupId(),
                'idDevice' => $tr->getDevice()->getIdDevice()
            ]
        );
    }
    public function remove(Transac $tr)
    {
        return $this->db->delete("transaction", ['idTrx' => $tr->getId()]);
    }
    
    public function exists(Transac $tr)
    {
        return $this->db->select(['COUNT(*)'], 'transaction', ['idTrx' => $tr->getId()])->fetch_row()[0]? true : false;
    }
    public function existsOnThatDay(String $idDevice, String $date)
    {
        return $this->db->select(['COUNT(*)'], 'transaction', ['dateTime' => $date, 'idDevice' => $idDevice])->fetch_row()[0] ? true : false;
    }

    public function fetchSemesterData(String $idDevice, int $year, int $selectedTIme)
    {
        return ($this->db->select(["'' AS date", 'IF(MONTH(dateTime) < 7, 1,2) as semester', 'MONTHNAME(dateTime) as month', 'device.nameDevice', 'TRIM(download)+0 AS download', 'TRIM(upload)+0 AS upload'], 'device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice', ['device.idDevice' => $idDevice, 'YEAR(dateTime)' => $year, 'IF(MONTH(dateTime) < 7, 1,2)' => $selectedTIme], '', "GROUP BY month ORDER BY dateTime ASC")->fetch_all(MYSQLI_ASSOC));
    }
    public function fetchMonthData(String $idDevice, int $year, int $selectedTime)
    {
        return $this->db->select(['DAYOFMONTH(dateTime) AS date', 'MONTHNAME(dateTime) AS month', 'device.nameDevice', 'TRIM(DOWNLOAD)+0 AS download', 'TRIM(upload)+0 AS upload'], 'device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice', ['device.idDevice' => $idDevice, 'MONTH(dateTime)' => $selectedTime, 'YEAR(dateTime)' => $year], '', 'ORDER BY dateTime ASC')->fetch_all(MYSQLI_ASSOC);
    }

    public function getSpreadsheetView()
    {
        return $this->db->select(["DATE_FORMAT(dateTime, '%a, %e %b %Y') AS date", "TRIM(download_CR_Indihome)+0 AS dl_CR_Indihome", "TRIM(upload_CR_Indihome)+0 AS ul_CR_Indihome", "TRIM(download_CP_Indihome)+0 AS dl_CP_Indihome", "TRIM(upload_CP_Indihome)+0 AS ul_CP_Indihome", "TRIM(download_PK_Biznet)+0 AS dl_PK_Biznet", "TRIM(upload_PK_Biznet)+0 AS ul_PK_Biznet", "TRIM(download_PK_Indosat)+0 AS dl_PK_Indosat", "TRIM(upload_PK_Indosat)+0 AS ul_PK_Indosat", "TRIM(download_CK_Orbit)+0 AS dl_CK_Orbit", "TRIM(upload_CK_Orbit)+0 AS ul_CK_Orbit", "TRIM(download_CK_XL)+0 AS dl_CK_XL", "TRIM(upload_CK_XL)+0 AS ul_CK_XL"], 'util_pivotted', [1=>1], "", "ORDER By dateTime ASC")->fetch_all(MYSQLI_ASSOC);
    }
    public function createTransaction(array $row = [])
    {
        $tr = new Transac;
        
        $user = $this->user->find(['userNIK' => $row['userNIK']], true);
        $device = $this->device->find(['idDevice' => $row['idDevice']], true);

        $tr->setId($row['idTrx']);
        $tr->setDate($row['dateTime']);
        $tr->setDownload($row['download']);
        $tr->setUpload($row['upload']);
        $tr->addUser($user);
        $tr->setDateCreated($row['dateCreated']);
        $tr->setDateModified($row['dateModified']);
        $tr->setDevice($device);

        return $tr;
    }
    public function createTransactionCollection(array $rows)
    {
        /*
        * Iterate through rows.
        * Create a Transaction object for each rows, with column names/values as properties.
        * Push Transaction object to collection.
        * Return collections's content.
        */
        return $this->transactionCollection;
    }
}
