<?php
namespace App\Model\Mapper\Device;

use App\Model\Device\Device;

interface DvcInterface
{
    public function createDevice(Array $row);
    public function find(Array $filter, $one = FALSE);
    public function save(Device $device);
    public function remove(Device $device);
}
