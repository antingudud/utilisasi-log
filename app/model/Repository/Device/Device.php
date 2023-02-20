<?php
namespace App\Model\Repository\Device;

use App\Core\Database\AdapterInterface;
use App\Model\Device;
use App\Model\Mapper\Device\DeviceMapper;

class DeviceRepo
{
    private $mapper;
    private $db;

    public function __construct(AdapterInterface $adapter)
    {
        $this->db = $adapter;
        $this->mapper = new DeviceMapper($this->db);
    }

    public function create(String $idDevice)
    {
        return $this->mapper->find(['idDevice' => $idDevice], true);
    }
}