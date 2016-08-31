<?php

/**
 * 公众号支付
 */
$data=$_POST;
if (!isset($data['tel'])|| !isset($data['schoolid'])){
	$resp['error']=-1; // 表示参数不全
	error_log("坑无数!!!");
	return;
}

// http://127.0.0.1/in.php?m=xx
// global $phone;
// global $sign;
// global $gold;
// global $body;
// global $school;
global $phone;
global $school;
global $gold;

$phone=$data['tel']; // 电话
//$sign=$data['sign']; // 签名
//$gold=$data['gold']; // 充值金额
//$body=$data['des']; // 商品简单描述
$school=$data['schoolid'];
// $_SESSION['phone'] = $phone;
// $_SESSION['school'] = $school;

// error_log("sing::"+$phone.$gold.$school.WxPayConfig::MD_KEY);
// $md=md5($phone.$gold.$school.WxPayConfig::MD_KEY);
// if ($md!=$sign){
// 	$resp['error']=2; // 表示签名有问题
	
// 	exit(json_encode($resp));
// }

include 'get_openid.php';


?>