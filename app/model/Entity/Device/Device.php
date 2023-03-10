<?php
namespace App\Model\Device;

class Device
{
    private $idDevice;
    private $idCategory;
    private $nameDevice;

    public function setIdDevice(String $id)
    {
        $this->idDevice = $id;
        return $this;
    }
    public function getIdDevice()
    {
        return $this->idDevice;
    }

    public function setIdCategory(String $id)
    {
        $this->idCategory = $id;
        return $this;
    }
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    public function setName(String $name)
    {
        $this->nameDevice = $name;
        return $this;
    }
    public function getName()
    {
        return $this->nameDevice;
    }
}