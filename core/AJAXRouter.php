<?php
require($_SERVER['DOCUMENT_ROOT'] . "/util/include/include.php");
if($_POST['action'] == "true"){
    $contr = new Transaction;
    $download = (float)$_POST['download'];
    $upload = (float)$_POST['upload'];
    $idDevice = strval($_POST['idDevice']);
    $contr->submitIndex($download,$upload,$idDevice);
}
else {
    echo "<script>alert('sus amogus')</script>";
}
?>