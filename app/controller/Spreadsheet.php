<?php
namespace App\Controller;

use App\View\View;

class SpreadsheetController
{

    public function index()
    {
        $params=['data'];
        $view = new View('spreadsheet/index', $params);
        return $view->render();
    }
}