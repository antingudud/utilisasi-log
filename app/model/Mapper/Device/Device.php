<?php
namespace App\Model\Mapper\Device;
use App\Core\Database\AdapterInterface;
use App\Model\Device;
use App\Model\Mapper\Device\DvcInterface;

class DeviceMapper implements DvcInterface
{
    private $db;

    public function setAdapter(AdapterInterface $adapter)
    {
        $this->db = $adapter;
        return $this;
    }

    public function find(Array $filter, $one = FALSE)
    {
        $rows = $this->db->select(['*'], "device", $filter)->fetch_all(MYSQLI_ASSOC);

        if($one)
        {
            return $this->createDevice($rows[0]);
        }

        return $this->createDeviceCollection($rows);
    }

    public function createDevice(Array $row)
    {
        $dv = new Device();

        $dv->setIdDevice($row['idDevice']);
        $dv->setIdCategory($row['idCategory']);
        $dv->setName($row['nameDevice']);

        return $dv;
    }
    public function createDeviceCollection(Array $row)
    {

    }
}