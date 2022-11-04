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
        include "../php_util/table.php";
        break;
    // case "refreshTable":
    //     $contr->showTransac();
    //     document.getElementById("");
    //     break;
    default:
        print "No action specified." . $_POST['action'];
        include "../php_util/table.php";
}
