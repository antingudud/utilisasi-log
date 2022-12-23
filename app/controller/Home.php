<?php
namespace App\Controller;
require_once dirname(__DIR__, 2) . "/vendor/autoload.php";
use App\View;

class Home {
    public function index(){
        return (new View('Home/index'))->render();
    }
}
?>