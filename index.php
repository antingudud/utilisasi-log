<?php
require_once 'vendor/autoload.php';
use Josantonius\Session\Session;
use App\Controller\Home;
use App\Controller\OptionsContr;
use App\Controller\SubmitContr;
use App\View\View;

$router = new \Bramus\Router\Router();
Session::set("username","guest");
Session::init(3600);
define('VIEW_PATH', __DIR__ . "/app/view");

$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include "app/view/404.php";
});
$router->get('/', function() {
    (new Home)->index();
});
$router->get('/view', function () {
    echo (new Home)->view();
});
$router->get('view/new', function() {
    $View = (new View("resources/components/new"));
    echo $View->render();
});
$router->mount('/submit', function() use ($router) {
    $router->post('/log', function() {
        return (new SubmitContr($_POST))->log();
    });
    $router->post('/update', function() {
        echo ( (new Home)->update($_POST) );
    });
    $router->post('/delete', function() {
        return (new SubmitContr($_POST))->delete();
    });
    $router->post('/edit', function() {
        return (new SubmitContr($_POST['id']))->edit();
    });
});
$router->mount('/options', function() use($router) {
    $router->post('/devices', function() {
        return (new OptionsContr)->getDevices();
    });
    $router->post('/new', function() {
        echo (new Home)->alter();
    });
});
$router->get('view/report', function() {
    $View = (new View('Transaction/report'));
    echo $View->render();
});
$router->run();
