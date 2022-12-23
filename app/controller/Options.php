<?php
namespace App\Controller;
use App\Model\Options;
use App\Model\Device;

class OptionsContr
{
    public function getDevices()
    {
        return (new Options($_POST))->getDevices();
    }

    public function showEditList()
    {
        echo json_encode( (new Device)->getEditList($_POST) );
    }
}