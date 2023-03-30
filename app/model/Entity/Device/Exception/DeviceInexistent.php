<?php
namespace App\Model\Transaction\Exception;
class DeviceInexistent extends FailedDevice
{
    public function __construct(\Exception $previous = null)
    {
        $message = "Device does not exist.";
        $code = 400;

        parent::__construct($message, $code, $previous);
    }
}