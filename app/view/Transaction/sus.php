<?php

use App\Core\ConnectDB;
use App\Core\Database\MysqliAdapter;
use App\Model\Mapper\Transaction\Mapper;
use App\Model\Repository\Transaction\Repo;
use App\Model\Service\Chart\DrawChart;

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";

$idDevice = $_GET['idDevice'];
$selectedTime = intval($_GET['selectedTime']);
$range = $_GET['range'];
$year = intval($_GET['year']);
$repo = (new Repo(new Mapper(New MysqliAdapter(New ConnectDB))));
$chart = new DrawChart($idDevice, $year, $selectedTime, $range, $repo);
$chart->draw();