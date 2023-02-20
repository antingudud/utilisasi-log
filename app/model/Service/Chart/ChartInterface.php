<?php
namespace App\Model\Service\Chart;

use App\Core\Database\AdapterInterface;

interface DrawChartInterface
{
    public function __construct(string $idDevice, int $year, int $selectedTime, string $range = "", AdapterInterface $adapter);
    public function draw();
}
