<?php
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
$data = $_GET;
if (isset($data["phone"])){
	$phone = $data["phone"];
	//error_log($phone);
}

if (isset($data["school"])){
	$school = $data["school"];
	//error_log($school);
}
$param="phone=".$phone."&school=".$school;

$tools=new JsApiPay();
$openId=$tools->GetOpenid($param);
//error_log("openid::".$openId);
// 调用统一查询接口
include "show.php";
?>