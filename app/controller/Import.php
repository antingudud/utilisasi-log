<?php
namespace App\Controller;

use App\Model\Service\Import\ImportInterface;

class ImportContr
{
    private $service;
    public function __construct(ImportInterface $service)
    {
        $this->service = $service;
    }
    public function import()
    {
        $this->service->import();
    }
}