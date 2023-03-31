<?php
namespace App\Controller;
use App\Model\Options;
use App\Model\DeviceService;

class OptionsContr
{
    public function getDevices()
    {
        return (new Options($_POST))->getDevices();
    }
}