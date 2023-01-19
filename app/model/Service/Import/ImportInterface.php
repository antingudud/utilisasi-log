<?php
namespace App\Model\Service\Import;

use App\Model\Service\Log\Log;

interface ImportInterface
{
    public function __construct(Log $log);
    public function import(Object $file): Array;
}