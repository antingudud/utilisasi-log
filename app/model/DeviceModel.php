<?php
class DeviceModel extends ConnectDB{
    protected function getAllDevice(){
        $sql = "SELECT * FROM device";
        $stmt = $this->connectTo()->prepare($sql);
        $stmt->execute();

        $getresult = $stmt->get_result();
        $result = $getresult->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
    protected function getDeviceCategory($id){
        $sql = "SELECT
                    device.nameDevice, device.idDevice
                FROM
                    device
                LEFT JOIN
                    category
                ON
                    device.idCategory = category.idCategory
                WHERE
                    category.nameCategory
                LIKE
                    ?";
        $stmt = $this->connectTo()->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $getresult = $stmt->get_result();
        $result = $getresult->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
}
?>