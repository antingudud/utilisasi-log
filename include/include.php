<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/util/classes/connection.php");
require ($_SERVER['DOCUMENT_ROOT'] . "/util/classes/class.php");

// Core
require ($_SERVER['DOCUMENT_ROOT'] . "/util/core/Router.php");
require ($_SERVER['DOCUMENT_ROOT'] . "/util/core/View.php");

// Model
require ($_SERVER['DOCUMENT_ROOT'] . "/util/app/model/TransactionModel.php");
require $_SERVER['DOCUMENT_ROOT'] . "/util/app/model/DeviceModel.php";

// Controller
require ($_SERVER['DOCUMENT_ROOT'] . "/util/app/controller/Transaction.php");
require $_SERVER['DOCUMENT_ROOT'] . "/util/app/controller/Home.php";
require $_SERVER['DOCUMENT_ROOT'] . "/util/app/controller/Device.php";

// View
require($_SERVER['DOCUMENT_ROOT'] . "/util/app/view/TransactionView.php");
require($_SERVER['DOCUMENT_ROOT'] . "/util/app/view/DeviceView.php");
?>