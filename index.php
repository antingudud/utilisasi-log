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

$sqladapter = new MysqliAdapter(new ConnectDB);
$mapperTr = new Mapper($sqladapter);
$mapperUsr = new UserMapper($sqladapter);
$mapperDvc = new DeviceMapper($sqladapter);

$repoUser = new RepoUser($mapperUsr);
$dvcrepo = new DeviceRepo($mapperDvc);
$repoTr = new Repo($mapperTr);
$repoTr->setDeviceRepo($dvcrepo);
$trserv = new TransacService($repoTr);
$trserv->setUser($repoUser);
$logserv = new Log($repoTr);
$logserv->setUser($repoUser);
$updateserv = new Update($repoTr);
$delserv = new Delete;
$delserv->setRepo($repoTr);

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
$router->mount('/submit', function() use ($router, $Home, $trserv, $logserv, $updateserv, $delserv) {
    $router->post('/log', function() use ($logserv) {
        $submit = new SubmitContr($_POST);
        $submit->setService($logserv);
        return $submit->log();
    });
    $router->post('/update', function() use($Home) {
        echo ( $Home->update($_POST) );
    });
    $router->post('/delete', function() use($delserv) {
        $submit = new SubmitContr($_POST);
        $submit->setService($delserv);
        return $submit->delete();
    });
    $router->post('/edit', function() use ($updateserv) {
        $submit = new SubmitContr($_POST['id']);
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
