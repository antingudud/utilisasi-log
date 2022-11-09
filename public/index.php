<!DOCTYPE html>
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/util/include/include.php");

$router = new Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('view', ['controller' => 'Transaction', 'action' => 'index']);
$router->add('device/', ['controller' => 'Device', 'action' => 'index']);
$router->add('view/new', ['controller' => 'Transaction', 'action' => 'new']);
$router->add('view/edit', ['controller' => 'Transaction', 'action' => 'edit']);
$router->add('{controller}/{action}');
$url = $_SERVER['QUERY_STRING'];
$router->dispatch($_SERVER['QUERY_STRING']);
?>