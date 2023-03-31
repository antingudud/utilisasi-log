<?php
namespace App\Controller;
require_once dirname(__DIR__, 2) . "/vendor/autoload.php";
use App\View\View;

class Home {
    public function index(){
        return (new View('Home/index'))->render();
    }
    public function report()
    {
        $content = "";
        $View = (new View('resources/components/report', ['content' => $content, 'month' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], 'semester' => ['Semester 1', 'Semester 2']]));
        return $View->render();
    }
}
?>