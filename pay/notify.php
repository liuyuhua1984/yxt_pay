<?php
#ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
require_once 'log.php';

// 初始化日志
//$logHandler=new CLogFileHandler("../logs/".date('Y-m-d').'.log');
//$log=Log::Init($logHandler,15);
class PayNotifyCallBack extends WxPayNotify{
	// 查询订单
	public function Queryorder($transaction_id){
		$input=new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result=WxPayApi::orderQuery($input);
		//error_log("订单查询结果::".json_encode($result));
	//	Log::ERROR("query:".json_encode($result));
		if (array_key_exists("return_code",$result)&&array_key_exists("result_code",$result)&&$result["return_code"]=="SUCCESS"&&$result["result_code"]=="SUCCESS"){
			
			 
			if ($result['return_code']=="SUCCESS"){
			
				$attach=explode("|",$result['attach']);
				
				// 进库
				$data=array();
				$data['openid']=$result['openid'];
				$data['trade_type']=$result['trade_type'];
				$data['total_fee']=$result['total_fee'];
				$data['fee_type']=$result['fee_type'];
				$data['cash_fee']=$result['cash_fee'];
				$data['coupon_fee']=$result['coupon_fee'];
				$data['coupon_count']=$result['coupon_count'];
				$data['transaction_id']=$result['transaction_id'];
				$data['out_trade_no']=$result['out_trade_no'];
				$data['time_end']=$result['time_end'];
				$data['create_time']=time();
				$tra = $data['transaction_id'];//微信支付订单号
				$query = db_factory::get_mysql()->get_one("pay","","transaction_id='$tra'");
			//	error_log("进来了没::".json_encode($query));
				if($query){
					return true;
				}
				try{
				
				db_factory::get_mysql()->insert($data,"pay");
			
 				$t_sign=md5($attach[0]+$result['total_fee']+$attach[1]+WxPayConfig::MD_KEY);
				$lCallBack=array("phone"=>$attach[0],'gold'=>$data['total_fee'],'schoolid'=>$attach[1],
						"transaction_id"=> $tra,"sign"=>$t_sign);
				//error_log("插入数据::".json_encode($lCallBack));
				$re=YxtCurl::doCurlPostRequest(WxPayConfig::CALL_BACK_URL,$lCallBack);
				//error_log("error".$re['error']);
				db_factory::get_mysql()->update(array("flag"=>$re['error']),"pay","transaction_id='$tra'");
				}catch (Exception $e){
					
					error_log("插入数据库异常前::".$e->getMessage());
				}
			}
			
			return true;
		}
		return false;
	}
	
	// 重写回调处理函数
	public function NotifyProcess($data,&$msg){
//		Log::ERROR("call back:".json_encode($data));
// 		error_log("回调处理".$data);
		$notfiyOutput=array();
		
		if (!array_key_exists("transaction_id",$data)){
			$msg="输入参数不正确";
			return false;
		}
		// 查询订单，判断订单真实性
		if (!$this->Queryorder($data["transaction_id"])){
			$msg="订单查询失败";
			return false;
		}
		return true;
	}
}

//Log::ERROR("begin notify");
$notify=new PayNotifyCallBack();
$notify->Handle(false);

?>