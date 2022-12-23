<?php
namespace App\Model;
use App\Model\Transaction\TransactionModel;
// require_once dirname(__DIR__, 2) . "/vendor/autoload.php";
class GraphModel extends TransactionModel {

    protected function gatherData($idDevice, $year, $desiredMonth, $range = "") {
        if($range == "semester"){
            $query = "SELECT DAYOFMONTH(dateTime) as date, IF(MONTH(dateTime) < 7, 1, 2) as semester, MONTHNAME(dateTime) as month, device.nameDevice, TRIM(download)+0 AS download, TRIM(upload)+0 AS upload FROM device RIGHT JOIN transaction ON  device.idDevice = transaction.idDevice WHERE device.idDevice = ? AND YEAR(dateTime) = ? HAVING semester = ? ORDER BY dateTime ASC";
            return json_decode($this->queryTransaction($query, [$idDevice, $year, $desiredMonth], "select"),true );
        }
        $query ="SELECT DAYOFMONTH(dateTime) as date, MONTHNAME(dateTime) as month, device.nameDevice, TRIM(download)+0 AS download, TRIM(upload)+0 AS upload FROM device RIGHT JOIN transaction ON  device.idDevice = transaction.idDevice WHERE  device.idDevice = ? AND MONTH(dateTime) = ? AND YEAR(dateTime) = ? ORDER BY dateTime ASC";
        return json_decode($this->queryTransaction($query, [$idDevice, $desiredMonth, $year], "select"),true );
    }

    protected function prepareData($idDevice, $year, $desiredMonth, $range = "") {
        $datas = $this->gatherData($idDevice, $year, $desiredMonth, $range);
        if(!$datas){
            return[[0], 'NO DATA', [0], [0]];
        }
        foreach ($datas as $data){
            $date[] = $data['month'] . " " . $data['date'];
            $download[] = $data['download'];
            $upload[] = $data['upload'];
        }
        $nameDevice = $data['nameDevice'];

        return [$date, $nameDevice, $download, $upload];
    }
}