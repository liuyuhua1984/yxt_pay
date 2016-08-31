var jnmoney = function(select) {
	// 其他时特殊处理

	var money = returnmonry(select.value);

	var jnjine = document.getElementById("jnjine");
	jnjine.value = money

}

var returnmonry = function(month) {

	if (month > 0) {
		return month * 30;
	}
	alert("month:" + month);
	// if(month==1){
	// return month*30
	// }
	// if(month==2){
	// return month*30
	// }
	// if(month==3){
	// return month*30
	// }
	// if(month==4){
	// return month*30
	// }
	// if(month==5){
	// return month*30
	// }
	// if(month==6){
	// return month*30
	// }
	// if(month==7){
	// return month*30
	// }
	// if(month==8){
	// return month*30
	// }if(month==9){
	// return month*30
	// }
	// if(month==10){
	// return month*30
	// }
	// if(month==11){
	// return month*30
	// }
	// if(month==12){
	// return month*30
	// }else if (month== -1){
	//		
	// }

}

var zaixianchongzhi = function() {
	var gold = document.getElementById("jnjine").value;
	var tel = document.getElementById("tel").value;
	var school = document.getElementById("schoolid").value;
	var openid = document.getElementById("openid").value;
	//alert("openid:"+openid);
	var aj = $.ajax({
		url : 'jsapi.php',// 跳转到 action  
		data : {
			"gold" : gold,
			"phone" : tel,
			"school" : school,
			"openid" : openid
		},
		type : 'post',//提交方式 
		cache : false,
		dataType : 'json',
		async : true,
		success : function(data) {
//			var last=data.toJSONString(); //将JSON对象转化为JSON字符
			//alert("成功::"+data.appId);
			jsApiCall(data);
		},

		error : function(data) {
			// view("异常！");  
			alert("错误:"+data);
		}
	});

	//	window.location.href = "show.php?gold=" + gold + "phone=" + tel
	//			+ "school=" + schoolid;
	// window.location.href="show.php?"+htt;

	// alert(schoolid)
	// var md5=hex_md5(tel+jnjine+schoolid+"75A4Ykwu9fW@");
	// var des=document.getElementById("des").value;
	// alert(md5+"::"+tel+jnjine+schoolid);
	// des ="会员充值";
	// benginchongzhi(tel,md5,jnjine,schoolid,des);
}

function jsApiCall(jsApiParameters)
{
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		jsApiParameters,
		function(res){
			WeixinJSBridge.log(res.err_msg);
			//alert(res.err_code+res.err_desc+res.err_msg);
		}
	);
}
var benginchongzhi = function(phone, sign, gold, schoolid, des) {
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4) {
			if (xhr.status === 200) {
				var text = xhr.responseText;
				var jsonResult = eval("(" + text + ")");
				alert("sdsfsfsd:::" + jsonResult.error);
			}
		}
	};
	xhr.open("GET",
			"http://pay.cqsins.com/yxt_pay/test/pay/index.php?pay=AccountPay"
					+ "&phone=" + phone + "&sign=" + sign + "&gold=" + gold
					+ "&schoolid=" + schoolid + "&des=" + des, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send();
}
