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

$router->post('view/table', function() {
    $controller = new SpreadsheetController();
    echo $controller->populateTable($_POST['data']);
});
$router->post('view/spreadsheet', function () {
    $controller = new SpreadsheetController();
    echo $controller->makeTable($_POST);
});

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

$router->mount('/options', function() use($router) {
    $router->post('/devices', function() {
        return (new OptionsContr)->getDevices();
    });

});
$router->get('view/report', function() {
    $Home = new \App\Controller\Home();
    echo $Home->report();
});
$router->run();
