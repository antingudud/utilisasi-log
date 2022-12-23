<?php
namespace App\Controller;
require_once dirname(__DIR__, 2) . "/vendor/autoload.php";
use App\View\View;
use App\Model\Device;
use App\Model\Transac;

class Home {
    public function index(){
        return (new View('Home/index'))->render();
    }
    public function view(){
        $body = (new Device)->getTransaction();
        $params = [
            'content' => [], 
            'header' => ['Tanggal', 'Device', 'Interface', 'Download', 'Upload', 'Author', 'Tanggal dibuat', 'Tanggal diubah', '<button type="submit" id="buttonViewUpdate">Update</button><button class="button error" type="submit" id="buttonViewDelete">Delete</button>'] , 
            'body' => $body
        ];
        $View = (new View('resources/components/table', $params));
        return $View->render();
    }
    public function update(Array $id)
    {
        $deviceList = (new Device)->getEditList($id);
        $View = (new View('resources/components/update', ['deviceList' => $deviceList]));
        return $View->render();
    }
    public function alter()
    {
        $logData = (new Transac)->getAlterForm();
        $View = (new View('resources/components/alter', ['log' => $logData]));
        return $View->render();
    }
}
?>