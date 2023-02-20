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
use App\Model\Service\Import\ImportWAN\ImportWAN;
use App\Model\Service\Update\Update;
use App\Model\Service\Upload\Upload;

$sqladapter = new MysqliAdapter(new ConnectDB);
$mapperTr = new Mapper($sqladapter);
$mapperUsr = new UserMapper($sqladapter);
$mapperDvc = new DeviceMapper($sqladapter);

$repoUser = new RepoUser($mapperUsr);
$dvcrepo = new DeviceRepo($mapperDvc);
$repoTr = new Repo($sqladapter);
$repoTr->setMapper($mapperTr);
$repoTr->setDeviceRepo($dvcrepo);
$trserv = new TransacService($repoTr);
$trserv->setUser($repoUser);
$logserv = new Log($repoTr, $repoUser);
$updateserv = new Update($repoTr);
$delserv = new Delete($repoTr);

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
$router->get('import', function() use($Home)
{
    echo $Home->import();
});
$router->get('test', function() {
    include_once("app/view/test.php");
});
$router->post('test', function()
{
    $destination = __DIR__ . "/public/uploaded";

    // print_r($_FILES['image']);

    $file = new \Aulia\ImageUpload\Upload($_FILES['files'], $_SERVER);
    $fs = new \Aulia\ImageUpload\Filesystem\Mock();
    $validator = new \Aulia\ImageUpload\Validator\Simple("2M", ['image/png', 'image/jpeg', 'application/vnd.ms-excel']);
    $pr = new \Aulia\ImageUpload\PathResolver\Simple($destination);

    $file->setFilesystem($fs);
    $file->addValidator([$validator]);
    $file->setPathResolver($pr);
    list($files, $headers) = $file->processAll();

    foreach($headers as $header => $value) {
        header($header . ': ' . $value);
    }
    echo json_encode(['files' => $files]);
    
    foreach($files as $file)
    {
        if(isset($file->completed))
        {
            echo $file->getRealPath();

            var_dump($file->isFile());
        }
    }
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
