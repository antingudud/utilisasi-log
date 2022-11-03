<?php
class Device extends DeviceModel{
    public function index(){
        echo "This is device";
    }

    public function showDevice(){
        $result = $this->getAllDevice();
        return $result;
    }

    public function showDeviceCategory($id){
        $result = $this->getDeviceCategory($id);
        return $result;
    }
}
?>