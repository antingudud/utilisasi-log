<?php
namespace App\Controller;

use App\Core\ConnectDB;
use App\Core\Database\MysqliAdapter;
use App\Model\Repository\Device\DeviceRepo;
use App\View\View;

class DeviceController
{
    public function index()
    {
        $adapter = new MysqliAdapter(new ConnectDB); $repo = new DeviceRepo($adapter);
        // $devices = ;
        $params = ["data" => [
            "devices" => $repo->fetchAll()
        ]];
        $view = new View('Device/index', $params);
        return $view->render();
    }
}