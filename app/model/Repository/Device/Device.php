<?php
namespace App\Model\Repository\Device;

use App\Core\ConnectDB;
use App\Core\Database\AdapterInterface;
use App\Model\Device;
use App\Model\Mapper\Device\DeviceMapper;

class DeviceRepo
{
    private $mapper;

    /**
     * Don't use it
     */
    private $adapter;

    /**
     * Database connection
     */
    private $db;

    /**
     * Creates adapter, mapper, and db connection
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->mapper = new DeviceMapper($this->adapter);
        $this->db = (new ConnectDB())->connectTo();
    }

    /**
     * Get device by Id
     * 
     * @param string $id
     * @return array [id, name, category]
     */
    public function fetchById(String $id)
    {
        $id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);

        $db = (new ConnectDB)->connectTo();
        $query = "SELECT idDevice, nameDevice, nameCategory FROM device LEFT JOIN category ON device.idCategory = category.idCategory WHERE idDevice = ? ORDER BY idDevice";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        $data = [
            "id" => $result['idDevice'],
            "name" => $result['nameDevice'],
            "category" => $result['nameCategory']
        ];

        return $data;
    }
    /**
     * Get list of all devices from LAN and WAN category;
     * 
     * @return string | return list of LAN and WAN devices in JSON {data: [LAN: [{idDevice: "...", nameDevice: "...", nameCategory: "..."}, ...], WAN: [{idDevice: "...", nameDevice: "...", nameCategory: "..."}, ...]}
     */
    public function fetchAll()
    {
        $db = $this->db;
        $queryLan = "SELECT idDevice, nameDevice, nameCategory FROM device LEFT JOIN category ON device.idCategory = category.idCategory WHERE device.idCategory = '2325eesc' GROUP BY idDevice";
        $queryWan = "SELECT idDevice, nameDevice, nameCategory FROM device LEFT JOIN category ON device.idCategory = category.idCategory WHERE device.idCategory = '2ae27792' GROUP BY idDevice";

        $lan = $db->prepare($queryLan);
        $lan->execute();
        $lanResult = $lan->get_result()->fetch_all(MYSQLI_ASSOC);

        $wan = $db->prepare($queryWan);
        $wan->execute();
        $wanResult = $wan->get_result()->fetch_all(MYSQLI_ASSOC);

        $data = [
            'LAN' => $lanResult,
            'WAN' => $wanResult
        ];
        
        return $data;
    }

    public function create(String $idDevice)
    {
        return $this->mapper->find(['idDevice' => $idDevice], true);
    }
}