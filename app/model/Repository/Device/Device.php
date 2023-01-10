<?php
namespace App\Model\Repository\Device;
use App\Model\Mapper\Device\DvcInterface;
use App\Model\Device;

class DeviceRepo
{
    private $mapper;
    public function __construct(DvcInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    public function create(String $idDevice)
    {
        return $this->mapper->find(['idDevice' => $idDevice], true);
    }
}