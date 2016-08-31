<?php
class YxtCurl{
	/**
	 * 封闭curl的调用接口，get的请求方式。
	 */
	public static function doCurlGetRequest($url,$timeout=50){
		if ($url==""||$timeout<=0){
			return false;
		}
		// $con = null;
		try{
			// $url = $url.'?'.http_bulid_query($data);
			$con=curl_init($url);
			curl_setopt($con,CURLOPT_HEADER,false);
			curl_setopt($con,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($con,CURLOPT_TIMEOUT,$timeout);
			
			return curl_exec($con);
		}catch(\Exception $e){
			return -1;
		}finally {
			if ($con){
				curl_close($con);
			}
		}
	}
	
	/**
	 * * @desc 封装 curl 的调用接口，post的请求方式
	 */
	public static function doCurlPostRequest($url,$requestString,$timeout=50){
		if ($url==''||$requestString==''||$timeout<=0){
			return false;
		}
		
		try{
			
			$con=curl_init($url);
			curl_setopt($con,CURLOPT_HEADER,false);
			curl_setopt($con,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($con,CURLOPT_POST,true);
			curl_setopt($con,CURLOPT_POSTFIELDS,$requestString);
			curl_setopt($con,CURLOPT_TIMEOUT,$timeout);
			$val=curl_exec($con);
			// echo "这个是多少::".$val;
			return $val;
		}catch(\Exception $e){
			print_r("doCurlPostRequest:出异常了");
			return -1;
		}finally {
			if ($con){
				curl_close($con);
			}
		}
	}
}
?>