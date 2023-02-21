<?php
namespace App\Controller;
require_once dirname(__DIR__, 2) . "/vendor/autoload.php";
use App\View\View;
use App\Core\ConnectDB;
use App\Core\Database\MysqliAdapter;
use App\Model\DeviceService;
use App\Model\Device;
use App\Model\Mapper\Transaction\Mapper;
use App\Model\Repository\Transaction\Repo;
use App\Model\Service\SheetPerMonth\SheetPerMonth;
use App\Model\TransacService;

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
    public function new()
    {
        $View = (new View('resources/components/new'));
        return $View->render();
    }
    public function update(Array $id)
    {
        $errors = [];
        foreach ($id['id'] as $key => $value) {
            if (empty($value) || strlen($value) > 8 || !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                $errors = ['error' => 'Invalid device ID'];
            }
        }
        if (!empty($errors)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid device ID']);
            return;
        }

        $deviceList = (new DeviceService)->getEditList($id);
        $View = (new View('resources/components/update', ['deviceList' => $deviceList]));
        return $View->render();
    }
    public function alter(?Int $selectedYear ,?Int $selectedMonth)
    {
        $adapter = new MysqliAdapter(new ConnectDB);
        $service = new SheetPerMonth($selectedYear, $selectedMonth, $adapter);
        $logData = $service->getPrettySheet();
        $View = (new View('resources/components/alter', ['log' => $logData, 'month' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']]));
        return $View->render();
    }
    public function report()
    {
        $content = "";
        $View = (new View('resources/components/report', ['content' => $content, 'month' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], 'semester' => ['Semester 1', 'Semester 2']]));
        return $View->render();
    }
    public function import()
    {
        $View = new View('resources/components/import');
        return $View->render();
    }
}
?>