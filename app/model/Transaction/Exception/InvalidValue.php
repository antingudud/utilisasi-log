<?php
namespace App\Model\Transaction\Exception;
class InvalidValue extends FailedTransaction
{
    public function __construct(\Exception $previous = null)
    {
        $message = "Invalid transaction value.";
        $code = 400;

        parent::__construct($message, $code, $previous);
    }
}