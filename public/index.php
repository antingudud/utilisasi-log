<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

$router = new \Bramus\Router\Router();

// $router->setBasePath('/util/public');

// $router = new Router();
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include $_SERVER['DOCUMENT_ROOT'] . "/utilisasi-log/app/view/404.php";
});
$router->get('/', function() {
    include $_SERVER['DOCUMENT_ROOT'] . "/utilisasi-log/app/view/Home/index.php";
});
$router->all('/view', function () {
    $Transaction = new Transaction;
    $Transaction->index();
});
$router->get('view/new', function() {
    $Transaction = new Transaction;
    $Transaction->new();
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
