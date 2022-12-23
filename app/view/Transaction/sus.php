<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
use App\View\Chart;
$idDevice = $_GET['idDevice'];
(int)$selectedTime = $_GET['selectedTime'];
$range = $_GET['range'];
(int)$year = $_GET['year'];
(new Chart($idDevice, $year, $selectedTime, $range))->drawChart();