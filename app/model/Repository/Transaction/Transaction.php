<?php
namespace App\Model\Repository\Transaction;

use App\Core\ConnectDB;
use App\Core\Database\AdapterInterface;
use App\Model\Transac;
use App\Model\User\User;
use App\Model\Device;
use App\Model\Mapper\Transaction\Mapper;
use App\Model\Repository\Device\DeviceRepo;

class Repo
{
    private $mapper;
    private $device;
    /**Deprecated */
    private $adapter;
    /**
     * @var $db Database connection
     */
    private $db;

    public function __construct(AdapterInterface $db)
    {
        $this->adapter = $db;
    }
    public function setMapper()
    {
        $this->mapper = new Mapper($this->adapter);
        return $this;
    }
    public function setDeviceRepo()
    {
        $this->device = new DeviceRepo($this->adapter);
        return $this;
    }

    public function getSpreadsheetView(Int $selectedYear, int $selectedMonth)
    {
        return $this->adapter->select(["DATE_FORMAT(dateTime, '%a, %e %b %Y') AS date", "TRIM(download_CR_Indihome)+0 AS dl_CR_Indihome", "TRIM(upload_CR_Indihome)+0 AS ul_CR_Indihome", "TRIM(download_CP_Indihome)+0 AS dl_CP_Indihome", "TRIM(upload_CP_Indihome)+0 AS ul_CP_Indihome", "TRIM(download_PK_Biznet)+0 AS dl_PK_Biznet", "TRIM(upload_PK_Biznet)+0 AS ul_PK_Biznet", "TRIM(download_PK_Indosat)+0 AS dl_PK_Indosat", "TRIM(upload_PK_Indosat)+0 AS ul_PK_Indosat", "TRIM(download_CK_Orbit)+0 AS dl_CK_Orbit", "TRIM(upload_CK_Orbit)+0 AS ul_CK_Orbit", "TRIM(download_CK_XL)+0 AS dl_CK_XL", "TRIM(upload_CK_XL)+0 AS ul_CK_XL"], 'util_pivotted', ["DATE_FORMAT(dateTime, '%c')"=>$selectedMonth, "DATE_FORMAT(dateTime, '%Y')" => $selectedYear], "", "ORDER By dateTime ASC, idTrx ASC")->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Cook up custom spreadsheet that will return as JSON
     * 
     * @param array $ids | An array of device id's
     * @param array $month | Month in numeric (0...12)
     * @param array $year | Year in four digit numeric (YYYY)
     * @return array json
     */
    public function cookSpreadsheet(Array $ids, int $month, int $year)
    {
        $this->db = new ConnectDB();
        $db = $this->db->connectTo();

        $placeholders = str_repeat('?, ', count($ids) - 1) . '?';
        $idTypes = str_repeat("s", count($ids));

        $query1 = "SELECT GROUP_CONCAT(DISTINCT
                CONCAT(
                    'ifnull(SUM(case when idDevice = ''',
                    transaction.idDevice,
                    ''' then TRIM(download)+0 end), 0) AS `',
                    transaction.idDevice, 'download', '`'
                ),
                CONCAT(
                    ', ifnull(SUM(case when idDevice = ''',
                    transaction.idDevice,
                    ''' then TRIM(upload)+0 end), 0) AS `',
                    transaction.idDevice, 'upload', '`'
                ),
                CONCAT(
                    ', ifnull(MAX(case when idDevice = ''',
                    transaction.idDevice,
                    ''' then idTrx end), 0) AS `',
                    transaction.idDevice, 'id', '`'
                )
        )
        FROM transaction LEFT JOIN device ON transaction.idDevice = device.idDevice WHERE device.idDevice in ($placeholders)";

        $stmt1 = $db->prepare($query1);
        $stmt1->bind_param($idTypes, ...$ids);
        $stmt1->execute();
        $result = $stmt1->get_result()->fetch_row()[0];

        $query = "SELECT DATE_FORMAT(dateTime, '%a, %e, %b, %Y') AS date, " . $result . " FROM transaction WHERE (DATE_FORMAT(dateTime, '%c') = ? AND DATE_FORMAT(dateTime, '%Y') = ?) GROUP BY dateTime ASC";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        $db->close();
        die();
    }

    public function findById(String $id)
    {
        return $this->mapper->find(['idTrx' => $id], true);
    }

    public function exists(String $idDevice, String $date)
    {
        return $this->adapter->select(['COUNT(*)'], 'transaction', ['dateTime' => $date, 'idDevice' => $idDevice])->fetch_row()[0] ? true : false;
    }

    public function fetchSemesterChart(String $idDevice, Int $year, Int $selectedTime)
    {
        $datas = ($this->adapter->select(["'' AS date", 'IF(MONTH(dateTime) < 7, 1,2) as semester', 'MONTHNAME(dateTime) as month', 'device.nameDevice', 'MAX(TRIM(download)+0) AS download', 'MAX(TRIM(upload)+0) AS upload'], 'device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice', ['device.idDevice' => $idDevice, 'YEAR(dateTime)' => $year, 'IF(MONTH(dateTime) < 7, 1,2)' => $selectedTime], '', "GROUP BY month ORDER BY dateTime ASC")->fetch_all(MYSQLI_ASSOC));

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
        $datas = $this->adapter->select(['DAYOFMONTH(dateTime) AS date', 'MONTHNAME(dateTime) AS month', 'device.nameDevice', 'TRIM(DOWNLOAD)+0 AS download', 'TRIM(upload)+0 AS upload'], 'device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice', ['device.idDevice' => $idDevice, 'MONTH(dateTime)' => $selectedTime, 'YEAR(dateTime)' => $year], '', 'ORDER BY dateTime ASC')->fetch_all(MYSQLI_ASSOC);

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
        $this->adapter->beginTransaction();
        try
        {
            foreach ($tr as $key => $value) {
                $value->setDownload($list[$key]['download']);
                $value->setUpload($list[$key]['upload']);
                $value->setDateModified($dateModified);

                $this->mapper->save($value);
            }
            return $this->adapter->commitTransaction();
        }
        catch (\Throwable $th)
        {
            return $this->adapter->rollbackTransaction();
        }
    }

    public function store(Transac $tr)
    {
        return $this->mapper->save($tr);
    }
    public function delete(Array $list)
    {
        $tr = $this->createCollection($list);
        $this->adapter->beginTransaction();

        try {
            foreach($tr as $key => $value)
            {
                $this->mapper->remove($value);
            }

            return $this->adapter->commitTransaction();

        } catch (\Throwable $th) {
            return $this->adapter->rollbackTransaction();
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