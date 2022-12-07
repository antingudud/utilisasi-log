<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$router = new Router();
$contr = new Transaction;

switch ($_POST['action']) {
    case "newEntry":
        $download = (float)$_POST['download'];
        $upload = (float)$_POST['upload'];
        $date = $_POST['date'];
        $idDevice = strval($_POST['idDevice']);
        $contr->submitIndex(["download" => $download, "upload" => $upload, "date" => $date, "idDevice" => $idDevice]);
        break;

    case "deleteEntry":
        $id = $_POST['id'];
        $contr->deleteIndex($id);
        break;

    case "updateEntry":
        $params = $_POST['id'];
        $query = "SELECT idTrx, UNIX_TIMESTAMP(dateTime), category.nameCategory, device.nameDevice, download, upload FROM device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice LEFT JOIN category ON category.idCategory = device.idCategory WHERE idTrx";
        if(count($params) > 1){
            $placeholders = str_repeat('?,', count($params) - 1) . '?';
            $query .= " IN ( {$placeholders} ) ORDER BY dateTime ASC";
            $contr->select_query($query, $params);
        } else {
            $query .= " = ?";
            $contr->select_query($query, $params);
        }
        break;

    case "submitEditEntry":
        $action = "other";
        $idTrx = $_POST['idTrx'];
        $download = array_map("floatval", $_POST['download']);
        $upload = array_map("floatval", $_POST['upload']);
        $dl = [];
        $ul = [];
        
        $params = ['idTrx' => $idTrx, 'download' => $download, 'upload' => $upload];
        $dlulPlaceholder = "";
        $types = str_repeat('i', count($download) * 2) . str_repeat('s', count($idTrx));

        foreach($idTrx as $index=>$id){
            $dlulPlaceholder .= "WHEN idTrx = ? THEN ? ";
            $idTrx[$index] = substr($idTrx[$index], 0, 8);
            array_push($dl, $idTrx[$index]);
            array_push($dl, $download[$index]);
            
            array_push($ul, $idTrx[$index]);
            array_push($ul, $upload[$index]);
            print_r($idTrx[$index] . "<br>");
        }
        $query = "UPDATE transaction SET download = CASE ". $dlulPlaceholder . "END, upload = CASE " . $dlulPlaceholder . "END, dateModified = now() WHERE idTrx IN ( " . str_repeat('?,', count($idTrx) - 1) . '?' . " )";
        $contr->getTransaction($query, array_merge($dl, $ul, $idTrx), $action, $types);
        break;

    case "showView":
        $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(dateTime), '%a, %e %b %Y') AS date, TRIM(download_CR_Indihome)+0 AS dl_CR_Indihome, TRIM(upload_CR_Indihome)+0 AS ul_CR_Indihome, TRIM(download_CP_Indihome)+0 AS dl_CP_Indihome, TRIM(upload_CP_Indihome)+0 AS ul_CP_Indihome, TRIM(download_PK_Biznet)+0 AS dl_PK_Biznet, TRIM(upload_PK_Biznet)+0 AS ul_PK_Biznet, TRIM(download_PK_Indosat)+0 AS dl_PK_Indosat, TRIM(upload_PK_Indosat)+0 AS ul_PK_Indosat, TRIM(download_CK_Orbit)+0 AS dl_CK_Orbit, TRIM(upload_CK_Orbit)+0 AS ul_CK_Orbit, TRIM(download_CK_XL)+0 AS dl_CK_XL, TRIM(upload_CK_XL)+0 AS ul_CK_XL FROM `util_pivotted` WHERE ? ORDER By dateTime ASC";
        $params = [1];
        $contr->select_query($query, $params);
        break;

    default:
        print "No such action: {$_POST['action']}";
        return false;
}