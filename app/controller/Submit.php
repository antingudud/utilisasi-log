<?php

namespace App\Controller;

use App\Model\Device;

class SubmitContr
{
    protected $data;

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
    public function log()
    {
        $download = intval($this->data['download']);
        $upload = intval($this->data['upload']);
        $date = $this->data['date'];
        $idDevice = strval($this->data['idDevice']);

        return (new Device)->log($download, $upload, $date, $idDevice);
    }
    public function delete()
    {
        return (new Device)->delete($this->data['id']);
    }
    public function edit()
    {
        return (new Device)->update($this->data);
    }
}
