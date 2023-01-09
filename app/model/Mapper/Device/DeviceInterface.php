<?php
namespace App\Model\Mapper\Device;
use App\Core\Database\AdapterInterface;

interface DvcInterface
{
    public function setAdapter(AdapterInterface $db);
    public function createDevice(Array $row);
    public function find(Array $filter, $one = FALSE);
}
