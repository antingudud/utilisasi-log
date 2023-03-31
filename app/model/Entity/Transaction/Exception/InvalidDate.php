<?php
namespace App\Model\Transaction\Exception;
class InvalidDate extends FailedTransaction
{
    public function __construct($dateFormat, \Exception $previous = null)
    {
        $message = "Invalid date format (" . $dateFormat . ").";
        $code = 400;

        parent::__construct($message, $code, $previous);
    }
}