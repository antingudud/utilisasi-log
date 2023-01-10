<?php
namespace App\Controller;
use App\Model\Options;
use App\Model\DeviceService;

class OptionsContr
{
    public function getDevices()
    {
        return (new Options($_POST))->getDevices();
    }

    public function showEditList()
    {
        $errors = [];
        $id = $_POST;
        foreach ($id['id'] as $key => $value) {
            if (empty($value) || strlen($value) > 8 || !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                $errors = ['error' => 'Invalid device ID'];
            }
        }
        if (!empty($errors)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid device ID']);
            return;
        }

        echo json_encode( (new DeviceService)->getEditList($id) );
    }
}