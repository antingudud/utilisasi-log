<?php
require_once 'vendor/autoload.php';

use App\Controller\DeviceController;
use Josantonius\Session\Session;
use App\Controller\OptionsContr;
use App\Controller\SpreadsheetController;

$router = new \Bramus\Router\Router();
Session::set("username","guest");
Session::init(3600);
define('VIEW_PATH', __DIR__ . "/app/view");

$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include "app/view/404.php";
});
$router->get('/', function() {
    $Home = new \App\Controller\Home();
    echo $Home->index();
});
// $router->get('/view', function () use($Home) {
//     echo $Home->view();
// });
// $router->get('view/new', function() {
//     $NewData = new NewDataController();
//     echo $NewData->index();
// });
// $router->get('view/new/device', function() use ($Home)
// {
//     echo $Home->newDevice();
// });
$router->post('view/table', function() {
    $controller = new SpreadsheetController();
    echo $controller->populateTable($_POST['data']);
});
$router->post('view/spreadsheet', function () {
    $controller = new SpreadsheetController();
    echo $controller->makeTable($_POST);
});
// $router->post('input-test', function() {
//     print_r($_POST);
// });
// $router->get('import', function() use($Home)
// {
//     echo $Home->import();
// });
$router->get('/devices', function () {
    $Device = new DeviceController();
    echo $Device->index();
});
$router->get('/device', function () {
    $Device = new DeviceController();
    echo $Device->detail($_GET);
});
$router->post('/get-devices', function () {
    $Device = new DeviceController();
    echo $Device->getAll();
});
$router->post('device/remove', function () {
    $Device = new DeviceController();
    echo $Device->remove($_POST);
});
$router->post('/device/edit', function() {
    $Device = new DeviceController();
    return $Device->edit($_POST);
});
$router->post('/devices/new', function() {
    $Device = new DeviceController();
    return $Device->add($_POST);
});
$router->mount('/spreadsheet', function() use ($router){
    $router->get('/', function() {
        $SpreadsheetController = new SpreadsheetController();
        echo $SpreadsheetController->index();
    });
    $router->post('/devices', function() {
        $spreadsheet = new SpreadsheetController();
        return $spreadsheet->getDeviceList();
    });
    $router->post('edit', function() {
        $spreadsheet = new SpreadsheetController();
        return $spreadsheet->edit($_POST);
    });
});
// $router->mount('/submit', function() use ($router, $Home, $logserv, $updateserv, $delserv, $sqladapter) {
    // $router->post('/log', function() {
    //     $NewData = new NewDataController();
    //     return $NewData->submit($_POST);
    // });
    // $router->post('/update', function() use($Home) {
    //     echo ( $Home->update($_POST) );
    // });
    // $router->post('/delete', function() {
    //     $Home = new Home();
    //     return $Home->delete($_POST);
    // });
    // $router->post('/edit', function() use ($updateserv) {
    //     $submit = new SubmitContr($_POST['id']);
    //     $submit->setService($updateserv);
    //     return $submit->edit();
    // });
    // $router->post('/file', function()
    // {
    //     $submit = new SubmitContr($_FILES);
    //     $uploadserv = new Upload;
    //     $submit->setService($uploadserv);
    //     return $submit->upload();
    // });
    // $router->post('/import', function() use ($logserv)
    // {
    //     $import = new ImportWAN($logserv);
    //     $submit = new SubmitContr($_FILES);
    //     $upload = new Upload;

    //     $submit->setService($upload);
    //     $fileObj = $submit->upload();
        
    //     return $import->import($fileObj);
    // });
    // $router->post('/device', function() use ($sqladapter)
    // {
    //     $sAddDevice = new AddDevice($sqladapter);
    //     $data = $_POST['data'];
    //     $sAddDevice->add($data['device'], $data['category']);
    // });
// });
$router->mount('/options', function() use($router) {
    $router->post('/devices', function() {
        return (new OptionsContr)->getDevices();
    });
    // $router->post('/new', function() use($Home) {
    //     $month = $_POST? $_POST['month']: NULL;
    //     $year = $_POST? $_POST['year']: NULL;
    //     echo $Home->alter($year, $month);
    // });
});
$router->get('view/report', function() {
    $Home = new \App\Controller\Home();
    echo $Home->report();
});
$router->run();
