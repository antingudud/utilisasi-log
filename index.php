<?php
require_once 'vendor/autoload.php';

use App\Controller\Home;
use App\Controller\NewDataController;
use Josantonius\Session\Session;
use App\Controller\OptionsContr;
use App\Controller\SubmitContr;
use App\Model\Mapper\Transaction\Mapper;
use App\Model\Mapper\User\UserMapper;
use App\Model\Mapper\Device\DeviceMapper;
use App\Model\Repository\Transaction\Repo;
use App\Model\Repository\User\Repo as RepoUser;
use App\Model\Repository\Device\DeviceRepo;
use App\Model\Service\Log\Log;
use App\Core\Database\MysqliAdapter;
use App\Core\ConnectDB;
use App\Model\Service\Delete\Delete;
use App\Model\Service\Device\AddDevice;
use App\Model\Service\Import\ImportWAN\ImportWAN;
use App\Model\Service\Update\Update;
use App\Model\Service\Upload\Upload;

$sqladapter = new MysqliAdapter(new ConnectDB);
$mapperTr = new Mapper($sqladapter);
$mapperUsr = new UserMapper($sqladapter);
$mapperDvc = new DeviceMapper($sqladapter);

$repoUser = new RepoUser($sqladapter);
$dvcrepo = new DeviceRepo($sqladapter);
$repoTr = new Repo($sqladapter);
$repoTr->setMapper();
$repoTr->setDeviceRepo();
$logserv = new Log($repoTr, $repoUser);
$updateserv = new Update($repoTr);
$delserv = new Delete($repoTr);

$mapperTr->setUserMapper();
$mapperTr->setDeviceMapper();

$Home = new \App\Controller\Home();
$router = new \Bramus\Router\Router();
Session::set("username","guest");
Session::init(3600);
define('VIEW_PATH', __DIR__ . "/app/view");

$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include "app/view/404.php";
});
$router->get('/', function() use($Home) {
    echo $Home->index();
});
$router->get('/view', function () use($Home) {
    echo $Home->view();
});
$router->get('view/new', function() {
    $NewData = new NewDataController();
    echo $NewData->index();
});
$router->get('view/new/device', function() use ($Home)
{
    echo $Home->newDevice();
});
$router->get('import', function() use($Home)
{
    echo $Home->import();
});
$router->mount('/submit', function() use ($router, $Home, $logserv, $updateserv, $delserv, $sqladapter) {
    $router->post('/log', function() {
        $NewData = new NewDataController();
        return $NewData->submit($_POST);
    });
    $router->post('/update', function() use($Home) {
        echo ( $Home->update($_POST) );
    });
    $router->post('/delete', function() {
        $Home = new Home();
        return $Home->delete($_POST);
    });
    $router->post('/edit', function() use ($updateserv) {
        $submit = new SubmitContr($_POST['id']);
        $submit->setService($updateserv);
        return $submit->edit();
    });
    $router->post('/file', function()
    {
        $submit = new SubmitContr($_FILES);
        $uploadserv = new Upload;
        $submit->setService($uploadserv);
        return $submit->upload();
    });
    $router->post('/import', function() use ($logserv)
    {
        $import = new ImportWAN($logserv);
        $submit = new SubmitContr($_FILES);
        $upload = new Upload;

        $submit->setService($upload);
        $fileObj = $submit->upload();
        
        return $import->import($fileObj);
    });
    $router->post('/device', function() use ($sqladapter)
    {
        $sAddDevice = new AddDevice($sqladapter);
        $sAddDevice->add($_POST['nameDevice'], $_POST['category']);
    });
});
$router->mount('/options', function() use($router, $Home) {
    $router->post('/devices', function() {
        return (new OptionsContr)->getDevices();
    });
    $router->post('/new', function() use($Home) {
        $month = $_POST? $_POST['month']: NULL;
        $year = $_POST? $_POST['year']: NULL;
        echo $Home->alter($year, $month);
    });
});
$router->get('view/report', function() use ($Home) {
    echo $Home->report();
});
$router->run();
