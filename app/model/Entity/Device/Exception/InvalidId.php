<?php
namespace App\Model\Transaction\Exception;
class InvalidID extends FailedDevice
{
    public function __construct(\Exception $previous = null)
    {
        $message = "Invalid device ID.";
        $code = 400;

        parent::__construct($message, $code, $previous);
    }
}