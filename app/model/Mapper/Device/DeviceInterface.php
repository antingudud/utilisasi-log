<?php
namespace App\Model\Mapper\Device;

interface DvcInterface
{
    public function createDevice(Array $row);
    public function find(Array $filter, $one = FALSE);
}
