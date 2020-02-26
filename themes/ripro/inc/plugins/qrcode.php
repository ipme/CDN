<?php
//error_reporting(E_ERROR);
// require_once 'phpqrcode.php';
require_once dirname(dirname(dirname(__FILE__))) . "/inc/class/qrcode.class.php";
$datas = !empty($_GET["data"]) ? $_GET["data"] : null ;
$url = urldecode($datas);
QRcode::png($url);
