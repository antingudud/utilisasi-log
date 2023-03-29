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

    /**
     * @param array $GET $_GET device = id
     */
    public function detail(Array $GET)
    {
        $id = $GET['device'];
        // $idvalidate = $this->validateId($id);
        // if(is_array($idvalidate))
        // {
        //     header('Content-Type: application/json; charset=utf-8');
        //     echo json_encode($idvalidate);
        // }

        $adapter = new MysqliAdapter(new ConnectDB); $repo = new DeviceRepo($adapter);
        
        $params = ["data" => [
            "devices" => $repo->fetchById($id)
        ]];
        $view = new View('Device/device', $params);
        return $view->render();
    }

    /**
     * Validate if id.
     * 
     * * Check if id > 8 or id < 7
     * 
     * @param mixed $id
     * @return array|bool true or error message array
     */
    protected function validateId($id)
    {
        $error["status"] = "error";
        $error["action"] = "validation";
        $error["message"] = "";

        if(count($id) > 8 || count($id) < 7)
        {
            $error["message"] = "Too long.";
            return $error;
        }
        return true;
    }
}