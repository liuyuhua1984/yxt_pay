<?php
#ini_set('date.timezone','Asia/Shanghai');

// error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
$data=$_POST;

// 初始化日志
//$logHandler=new CLogFileHandler("../logs/".date('Y-m-d').'.log');
//$log=Log::Init($logHandler,15);

// 打印输出数组信息
function printf_info($data){
// 	foreach ( $data as $key => $value ){
// 		error_log($key."="."$value");
// 	}
}


$phone=$data['phone']; // 电话
//$sign=$data['sign']; // 签名
$gold=$data['gold']; // 充值金额
$openId=$data['openid']; // 商品简单描述
$school=$data['school'];
//error_log($phone."="."$school");
// ①、获取用户openid
$tools=new JsApiPay();
//$openId=$tools->GetOpenid();
// ②、统一下单
$callback_url ="http://".$_SERVER['HTTP_HOST'].WxPayConfig::NOTIFY_URL;

$input=new WxPayUnifiedOrder();
$input->SetBody("会员充值");
$input->SetAttach($phone.'|'.$school);
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee($gold*WxPayConfig::MT);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis",time()+600));
$input->SetGoods_tag("幼校通");

$input->SetNotify_url($callback_url);
$input->SetTrade_type("JSAPI");

$input->SetOpenid($openId);
$order=WxPayApi::unifiedOrder($input);
// echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
 printf_info($order);
$jsApiParameters=$tools->GetJsApiParameters($order);

// 获取共享收货地址js函数参数
$editAddress=$tools->GetEditAddressParameters();
// $js = json_encode(array("param"=>$jsApiParameters));
//error_log("js:".$jsApiParameters);
echo $jsApiParameters;
// ③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>
