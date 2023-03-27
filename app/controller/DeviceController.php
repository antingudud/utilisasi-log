<?php
namespace App\Controller;

use App\View\View;

class DeviceController
{
    public function index()
    {
        $view = new View('Device/index');
        return $view->render();
    }
}