<?php
namespace App\Model\Transaction\Exception;

use Exception;

abstract class FailedDevice extends Exception
{
    public function __construct(String $message, Int $code = 0, Exception $previous = null)
    {
        header('HTTP/1.1 400 Bad Request');
        $message = 'Problem adding or editing device: ' . $message;
        parent::__construct($message, $code, $previous);
    }
}