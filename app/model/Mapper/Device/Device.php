<?php
namespace App\Model\Mapper\Device;
use App\Core\Database\AdapterInterface;
use App\Model\Device\Device;
use App\Model\Mapper\Device\DvcInterface;
use App\Model\Service\Device\Exception\InvalidDeviceValue;
use App\Model\Transaction\Exception\RecordExists;

class DeviceMapper implements DvcInterface
{
    private $db;

    public function __construct(AdapterInterface $adapter)
    {
        $this->db = $adapter;
    }

    /**
     * Find from table device.
     * Create a Device object or object collection if found.
     * If not returns false.
     * 
     * @param array $filter
     * @param bool $one FALSE|TRUE
     * @return Device|array|bool
     */
    public function find(Array $filter, $one = FALSE)
    {
        $rows = $this->db->select(['*'], "device", $filter)->fetch_all(MYSQLI_ASSOC);
        if(!$rows)
        {
            return false;
        }
        if($one)
        {
            return $this->createDevice($rows[0]);
        }

        return $this->createDeviceCollection($rows);
    }

    /**
     * Save object to the database
     * @param Device $device
     * @return bool true|false
     */
    public function save(Device $device)
    {
        if($this->nameExists($device) && $this->hasSameCategory($device))
        {
            throw new RecordExists();
        }
        return $this->db->insert('device', ['idDevice' => $device->getIdDevice(), 'nameDevice' => $device->getName(), 'idCategory' => $device->getIdCategory()]);
    }

    /**
     * Remove device record using device id
     * @return bool true|false
     */
    public function remove(Device $device)
    {
        $this->db->delete('device', ['idDevice' => $device->getIdDevice()]);
    }

    /**
     * Check if a device has the same category.
     * Check is done with name and category.
     * @param Device $device
     * @return bool true|false
     */
    protected function hasSameCategory(Device $device): bool
    {
        return $this->db->select(['COUNT(*)'], 'device', ['nameDevice' => $device->getName(), 'idCategory' => $device->getIdCategory()])->fetch_row()[0]? true : false;
    }

    /**
     * Check if a device already exists using it's name
     * @param Device $device
     * @return bool true|false
     */
    protected function nameExists(Device $device): bool
    {
        return $this->db->select(['COUNT(*)'], 'device', ['nameDevice' => $device->getName()])->fetch_row()[0]? true : false;
    }

    /**
     * Create device object
     * @param array $row available columns: idDevice, idCategory, nameDevice
     * @return object Device
     */
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