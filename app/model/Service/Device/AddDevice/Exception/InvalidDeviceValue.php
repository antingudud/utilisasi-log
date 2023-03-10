<?php
namespace App\Model\Service\Device\Exception;
use App\Model\Transaction\Exception\FailedTransaction;
class InvalidDeviceValue extends FailedTransaction
{
    public function __construct(\Exception $previous = null)
    {
        $message = "Invalid device value.";
        $code = 500;

        parent::__construct($message, $code, $previous);
    }
}