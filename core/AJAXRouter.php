<?php
require($_SERVER['DOCUMENT_ROOT'] . "/util/include/include.php");

if($_POST['action'] == "true"){
    $contr = new Transaction;
    $download = $_POST['download'];
    $upload = $_POST['upload'];
    $idDevice = $_POST['idDevice'];
    $contr->submitIndex($download,$upload,$idDevice);
}
else {
    echo "<script>alert('sus amogus')</script>";
}
?>