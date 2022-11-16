<?php
require($_SERVER['DOCUMENT_ROOT'] . "/util/include/include.php");

$router = new Router();
$contr = new Transaction;

switch ($_POST['action']) {
    case "newEntry":
        $download = (float)$_POST['download'];
        $upload = (float)$_POST['upload'];
        $idDevice = strval($_POST['idDevice']);
        $contr->submitIndex($download, $upload, $idDevice);
        break;

    case "deleteEntry":
        $id = $_POST['id'];
        $contr->deleteIndex($id);
        break;

    case "updateEntry":
        $action = "select";
        $params = $_POST['id'];
        $query = "SELECT idTrx, UNIX_TIMESTAMP(dateTime), category.nameCategory, device.nameDevice, download, upload FROM device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice LEFT JOIN category ON category.idCategory = device.idCategory WHERE idTrx";
        if(count($params) > 1){
            $placeholders = str_repeat('?,', count($params) - 1) . '?';
            $query .= " IN ( {$placeholders} ) ORDER BY dateTime ASC";
            $contr->getTransaction($query, $params, $action);
        } else {
            $query .= " = ?";
            $contr->getTransaction($query, $params, $action);
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
        $query = "SELECT * FROM util_pivotted WHERE ?";
        $params = [1];
        $contr->getTransaction($query, $params, "select");
        break;

    default:
        print "No such action: {$_POST['action']}";
        return false;
}
