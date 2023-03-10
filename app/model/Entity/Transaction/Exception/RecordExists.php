<?php
namespace App\Model\Transaction\Exception;

use Exception;

class RecordExists extends FailedTransaction
{
    public function __construct(\Exception $previous = null)
    {
        $message = "Record already exists.";
        $code = 400;

        parent::__construct($message, $code, $previous);
    }
}