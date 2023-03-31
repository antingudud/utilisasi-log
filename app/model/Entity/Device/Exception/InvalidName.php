<?php
namespace App\Model\Transaction\Exception;
class InvalidName extends FailedDevice
{
    public function __construct(\Exception $previous = null)
    {
        $message = "Invalid name string.";
        $code = 400;

        parent::__construct($message, $code, $previous);
    }
}