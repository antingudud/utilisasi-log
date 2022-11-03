<?php
require($_SERVER['DOCUMENT_ROOT'] . "/util/include/include.php");

$router = new Router();

if($_POST['action'] == "newEntry"){
    $contr = new Transaction;
    $download = (float)$_POST['download'];
    $upload = (float)$_POST['upload'];
    $idDevice = strval($_POST['idDevice']);
    $contr->submitIndex($download,$upload,$idDevice);
}
else {
}
?>