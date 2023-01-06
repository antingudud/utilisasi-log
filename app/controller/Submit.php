<?php

namespace App\Controller;

use App\Model\Device;
use App\Model\TransacService;

class SubmitContr
{
    protected $data;
    private $device;
    private $service;

    function __construct(array $POST)
    {
        $this->data = array_map(function ($value) {
            if (is_array($value)) {
                if (is_array($value)) {
                    return array_map('trim', $value);
                }
            }
            return trim($value);
        }, $POST);
        $this->data = array_map(function ($value) {
            if (is_array($value)) {
                if (is_array($value)) {
                    return array_map('strip_tags', $value);
                }
            }
            return strip_tags($value);
        }, $this->data);
        $this->data = array_map(function ($value) {
            if (is_array($value)) {
                if (is_array($value)) {
                    return array_map('htmlspecialchars', $value);
                }
            }
            return htmlspecialchars($value);
        }, $this->data);
    }

    public function setService($service)
    {
        $this->service = $service;
    }

    public function setModel($model)
    {
        $this->device = $model;
    }

    public function log()
    {
        $download = floatval($this->data['download']);
        $upload = floatval($this->data['upload']);
        $date = strval($this->data['date']);
        $idDevice = strval($this->data['idDevice']);

        return $this->service->log($download, $upload, $date, $idDevice);
    }
    public function delete()
    {
        $id = $this->data['id'];
        return $this->service->delete($id);
    }
    public function edit()
    {
        $list = $this->data;

        return $this->service->update($list);
    }
}
