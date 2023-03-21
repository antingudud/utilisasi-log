<?php
namespace App\Controller;

use App\Core\ConnectDB;
use App\Core\Database\MysqliAdapter;
use App\View\View;
use App\Model\Repository\Transaction\Repo;
use App\Model\Repository\Device\DeviceRepo;

class SpreadsheetController
{

    public function index()
    {
        $params=['data' => [
            'devices' => ''
        ]];
        $view = new View('spreadsheet/index', $params);
        return $view->render();
    }

    public function getDeviceList()
    {
        $adapter = new MysqliAdapter(new ConnectDB);
        $deviceRepo = new DeviceRepo($adapter);
        
        $res = $deviceRepo->fetchAll();
        return $res;
    }
}