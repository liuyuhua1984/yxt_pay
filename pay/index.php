<?php

// $url="http://pay.cqsins.com/yxt_pay/t.php";
// Header("Location: $url");
// exit();
include_once "../lib/WxPay.Config.php";
include_once "../lib/db_factory.class.php";
include_once "../lib/db_mysqli.class.php";
include_once "../lib/YxtCurl.php";
if (!isset($_GET["pay"])){
	error_log("有问题");
	return;
}

//error_log("进来了");
$pay=$_GET["pay"];
if ($pay){
	require $pay.".php";
}
?>