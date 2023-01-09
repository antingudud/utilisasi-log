<?php
require_once 'vendor/autoload.php';
use Josantonius\Session\Session;
use App\Controller\OptionsContr;
use App\Controller\SubmitContr;
use App\Model\TransacService;
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
use App\Model\Service\Update\Update;

$sqladapter = new MysqliAdapter();
$sqladapter->setConnection(new ConnectDB);

$mapperTr = new Mapper();
$mapperTr->setAdapter($sqladapter);
$mapperUsr = new UserMapper();
$mapperUsr->setAdapter($sqladapter);
$mapperDvc = new DeviceMapper();
$mapperDvc->setAdapter($sqladapter);

$repoUser = new RepoUser();
$dvcrepo = new DeviceRepo();
$repoTr = new Repo();
$logserv = new Log();
$updateserv = new Update();
$delserv = new Delete;

$mapperTr->setUserMapper($mapperUsr);
$mapperTr->setDeviceMapper($mapperDvc);

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
    $Home->index();
});
$router->get('/view', function () use($Home) {
    echo $Home->view();
});
$router->get('view/new', function() use($Home) {
    echo $Home->new();
});
$router->mount('/submit', function() use ($router, $Home, $logserv, $updateserv, $delserv, $mapperTr, $mapperDvc, $mapperUsr, $repoTr, $repoUser, $dvcrepo) {
    $repoTr->setMapper($mapperTr);
    $repoUser->setMapper($mapperUsr);
    $dvcrepo->setMapper($mapperDvc);
    $repoTr->setDeviceRepo($dvcrepo);

    $router->post('/log', function() use ($logserv, $repoTr, $repoUser) {
        $submit = new SubmitContr($_POST);
        $logserv->setRepo($repoTr);
        $logserv->setUser($repoUser);
        $submit->setService($logserv);
        return $submit->log();
    });
    $router->post('/update', function() use($Home) {
        echo ( $Home->update($_POST) );
    });
    $router->post('/delete', function() use($delserv, $repoTr) {
        $submit = new SubmitContr($_POST);
        $delserv->setRepo($repoTr);
        $submit->setService($delserv);
        return $submit->delete();
    });
    $router->post('/edit', function() use ($updateserv, $repoTr, $repoUser) {
        $submit = new SubmitContr($_POST['id']);
        $updateserv->setRepo($repoTr)->setUser($repoUser);
        $submit->setService($updateserv);
        return $submit->edit();
    });
});
$router->mount('/options', function() use($router, $Home) {
    $router->post('/devices', function() {
        return (new OptionsContr)->getDevices();
    });
    $router->post('/new', function() use($Home) {
        echo $Home->alter();
    });
});
$router->get('view/report', function() use ($Home) {
    echo $Home->report();
});
$router->run();
