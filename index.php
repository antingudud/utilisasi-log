<?php
require_once 'vendor/autoload.php';
use Josantonius\Session\Session;
use App\Controller;
use App\Controller\Home;

$router = new \Bramus\Router\Router();
Session::set("username","guest");
Session::init(3600);

define('VIEW_PATH', __DIR__ . "/app/view");

// $router = new Router();
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include "app/view/404.php";
});
$router->get('/', function() {
    (new Home)->index();
});
$router->get('/view', function () {
    $Transaction = new Transaction;
    $Transaction->view('Transaction/index.php');
});
$router->get('view/new', function() {
    $Transaction = new Transaction;
    $Transaction->view('Transaction/new.php');
});
$router->get('view/report', function() {
    $Transaction = new Transaction;
    $Transaction->view('Transaction/report.php');
});

// $router->add('', ['controller' => 'Home', 'action' => 'index']);
// $router->add('view', ['controller' => 'Transaction', 'action' => 'index']);
// $router->add('device/', ['controller' => 'Device', 'action' => 'index']);
// $router->add('view/new', ['controller' => 'Transaction', 'action' => 'new']);
// $router->add('view/edit', ['controller' => 'Transaction', 'action' => 'edit']);
// $router->add('{controller}/{action}');
// $url = $_SERVER['QUERY_STRING'];
// $router->dispatch($_SERVER['QUERY_STRING']);
$router->run();
