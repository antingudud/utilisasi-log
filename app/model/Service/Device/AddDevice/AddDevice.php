<?php
namespace App\Model\Service\Device;

use App\Core\Database\AdapterInterface;
use App\Model\Mapper\Device\DeviceMapper;
use App\Model\Repository\Device\DeviceRepo;
use App\Model\Service\Device\Exception\InvalidDeviceValue;

class AddDevice
{
    const LAN = '2325eesc';
    const WAN = '2ae27792';
    private $mapper;
    private $repo;
    private $device;
    private $name;
    private $category;
    private $id;

    public function __construct(AdapterInterface $adapter)
    {
        $this->mapper = new DeviceMapper($adapter);
        $this->repo = new DeviceRepo($adapter);
    }

    public function add(String $name, String $category)
    {
        $this->name = $name;
        $this->category = $category;
        $this->id = $this->generateId();

        if($category === 'LAN')
        {
            $this->category = self::LAN;
        } elseif ($category === 'WAN')
        {
            $this->category = self::WAN;
        } else
        {
            throw new InvalidDeviceValue();
        }

        $this->device = $this->mapper->createDevice(['nameDevice' => $this->name, 'idDevice' => $this->id, 'idCategory' => $this->category]);
        return $this->mapper->save($this->device);
    }

    /**
     * Generates 8-character long id.
     * Checks the database for any existing id. If found, will regenerate.
     * @return string
     */
    protected function generateId(): string
    {
        $raw_id = uniqid();
        $id = substr($raw_id, 5);
        if($this->mapper->find(['idDevice' => $id], TRUE))
        {
            return $this->generateId();
        } else
        {
            return $id;
        }
    }
}