<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
$sus = new Chart();
$idDevice = $_GET['idDevice'];
$selectedTime = $_GET['selectedTime'];
$range = $_GET['range'];
$year = $_GET['year'];
$sus->getChart($idDevice,$year, $selectedTime, $range);