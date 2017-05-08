
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>会员支付</title>
<link rel="stylesheet" type="text/css" href="../css/dase.css">
<link rel="stylesheet" type="text/css" href="../css/pagare.css">
<script type="text/javascript" src="../js/chongzhi.js"></script>
<script type="text/javascript" src="../js/MD5.js"></script>
<script src="../js/jquery-1.11.3.min.js"></script>

</head>

<body>

	<!--  
		<c:if test="${type==0}">
		<input id="tel" value="${parent.tel}">
		<input id="schoolid" value="${parent.shcoolid}"> 
	</c:if>
	<c:if test="${type==1}">	
		<input value="${teacher.tel}">
		<input value="${teacher.schoolid}">
	</c:if>	
-->
	<div class="left fl">
		<div class="div" id="zhifu">会员支付</div>
	<!-- 	<div class="div1" id="chongzhi">账户充值</div> -->
	</div>

	<div class="right fl">
		<div class="zhifu-one" id="zhifu-one">
		<!--  
			<form action="show.php" method="get">
			-->
				<input id="tel" type="hidden" name="phone" value=<?php echo isset($phone)? $phone : 0;?> /> 
				<input id="schoolid" type="hidden" name="school" value=<?php echo isset($school) ? $school : 0;?> />
				<input id="openid" type="hidden" name="openid" value=<?php  echo isset($openId) ? $openId : 0; ?> />
					
				<ul>
<!-- -月收一个月的钱, -学期收5个月的钱,一年收10个月的钱-->
					<li><span>开通/续费时长：</span><select onchange="jnmoney(this)">
						<option value="1">一月</option>
							<option value="5">一学期</option>
							<option value="10">一年</option>
					</select></li>
					<!--  
					<li><span>其他：</span><input id="pay_other" type="number" value="15"   maxlength="10"  
						class="input"><span>月</span></li>
						-->
					<li><span>您应支付：</span> <input id="jnjine" type="number" name="gold" maxlength="10"
						value="30" class="input" readonly> <span>元</span></li>
				</ul>
				
			<div class="ok-ok">
				<a onclick="zaixianchongzhi()" style=" display:inline-block; margin-left:20%;">确定</a>
				
				<a href="javascript:history.go(-1)" style="display:inline-block; margin-left:10%;">返回</a>
			</div>
				<!--  
				<input type="submit" value="确定"
					style="display: block; height: 100%; line-height: 100px; width: 200px; text-align: center; margin: 0 auto; font-size: 44px; color: #fff; background: #06c1ae; border-radius: 20px;" />
		
			</form>
			--> 
		</div>
	<!--  
		<div class="chongzhi-one none" id="chongzhi-one">
			<ul>
				<li><span>充值账号：</span><input type="text" placeholder=""
					class="input"></li>
				<li><span>充值金额：</span><select>
						<option>10</option>
						<option>20</option>
						<option>50</option>
						<option>100</option>
						<option>其他</option>
				</select></li>
				<li><span>其他：</span><input type="text" placeholder="" class="input"><span>元</span></li>
			</ul>

			<div class="ok-ok">
				<a id="ok">确定</a>
			</div>
		</div>
	</div>

	<div class="chen none" id="chen">
		<div class="kuang">
			<p>
				是否给123充值<span>3</span>元
			</p>
			<a class="ok-k" id="ok-o">确定</a> <a class="tuichu" id="tuichu">否</a>
		</div>
		-->
	</div>


	<script type="text/javascript">
	$("#zhifu").click(function(){
		$("#zhifu").removeClass("div1");
		$("#zhifu").addClass("div");
		$("#chongzhi").removeClass("div");
		$("#chongzhi").addClass("div1");
		$("#zhifu-one").removeClass("none");
		$("#chongzhi-one").addClass("none");
		});
	$("#chongzhi").click(function(){
		$("#chongzhi").removeClass("div1");
		$("#chongzhi").addClass("div");
		$("#zhifu").removeClass("div");
		$("#zhifu").addClass("div1");
		$("#zhifu-one").addClass("none");
		$("#chongzhi-one").removeClass("none");
		
		});
		
	$("#ok").click(function(){
		$("#chen").removeClass("none");
		});
	$("#ok-o").click(function(){
		$("#chen").addClass("none");
		});
	$("#tuichu").click(function(){
		$("#chen").addClass("none");
		});
</script>
<!-- 
	<script type="text/javascript">


 function callpay()
 {
 	if (typeof WeixinJSBridge == "undefined"){
 	    if( document.addEventListener ){
 	        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
 	    }else if (document.attachEvent){
 	        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
 	        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
 	    }
 	}else{
 	    jsApiCall();
 	}
 }


</script>
 -->
</body>
</html>
