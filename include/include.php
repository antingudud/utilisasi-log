<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . "/util/classes/connection.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/util/classes/class.php");

// Core
require_once ($_SERVER['DOCUMENT_ROOT'] . "/util/core/Router.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/util/core/View.php");

// Model
require_once ($_SERVER['DOCUMENT_ROOT'] . "/util/app/model/TransactionModel.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/app/model/DeviceModel.php";

// Controller
require_once ($_SERVER['DOCUMENT_ROOT'] . "/util/app/controller/Transaction.php");
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/app/controller/Home.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/util/app/controller/Device.php";

// View
require_once($_SERVER['DOCUMENT_ROOT'] . "/util/app/view/TransactionView.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/util/app/view/DeviceView.php");
?>