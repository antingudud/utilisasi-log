<?php
namespace App\Model\Service\Chart;
use App\Model\Repository\Transaction\Repo;

interface DrawChartInterface
{
    public function __construct(string $idDevice, int $year, int $selectedTime, string $range = "");
    public function setRepo(Repo $repo);
    public function draw();
}
