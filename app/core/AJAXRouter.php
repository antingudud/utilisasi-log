<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
use App\Model\Transaction\TransactionService;
$router = new Router();
$contr = new Transaction;

switch ($_POST['action']) {
    case "newEntry":
        $download = (float)$_POST['download'];
        $upload = (float)$_POST['upload'];
        $date = $_POST['date'];
        $idDevice = strval($_POST['idDevice']);
        // $contr->submitIndex(["download" => $download, "upload" => $upload, "date" => $date, "idDevice" => $idDevice]);
        $contr->submitIndex($download, $upload, $date, $idDevice);
        break;

    case "deleteEntry":
        $id = $_POST['id'];
        $contr->deleteIndex($id);
        break;

    case "updateEntry":
        $contr->showUpdateView($_POST['id']);
        break;

    case "submitEditEntry":
        $contr->updateIndex($_POST);
        break;

    case "showView":
        $contr->selectAlterForm();
        break;

    default:
        print "No such action: {$_POST['action']}";
        return false;
}
