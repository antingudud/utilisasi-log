<?php
namespace App\Model\Transaction\Exception;
class InvalidCategory extends FailedDevice
{
    public function __construct(\Exception $previous = null)
    {
        $message = "Invalid category string.";
        $code = 400;

        parent::__construct($message, $code, $previous);
    }
}