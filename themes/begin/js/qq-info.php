<?php
header("Content-Type:text/html; charset=UTF-8");
error_reporting(0);

include "../../../../wp-load.php";
$table_name=$wpdb->prefix."qq_usermeta";
    
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['type'])){
	if($_POST['type']=="qq"){
		if(isset($_POST['qq']) && $_POST['qq']==""){
			$message="请输入QQ";
			$status="1";
		}else{
			$qq=$_POST['qq'];
			$status="0";
		}
	}elseif($_POST['type']=="checkemail"){
		$qq=$_POST['qq'];
		$email=$_POST['email'];
		$status="0";
	}else{}
}else{
	$message="请求方式错误";
	$status="1";
}
//echo $qq;

$nameurl = "https://users.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?uins=".$qq;
$logourl="http://q.qlogo.cn/g?b=qq&nk={$qq}&s=100&t=".time().generate_code();
$ch = curl_init(); 
$timeout = 5; 
curl_setopt($ch, CURLOPT_URL, $nameurl); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
$contents = curl_exec($ch); 
curl_close($ch); 
$info=iconv('GBK', 'UTF-8', $contents);
$array = explode('"', $info);

if($_POST['type']=="qq"){
	if(count($results)=="0"){
		$email=$qq."@qq.com";
		$url="";
	}else{
		$email=$results[0][email];
		$url=',"url":"'.$results[0][url].'"';
	}
}

if($_POST['type']=="qq"){
if($status=="0"){
$cookiehash='' . COOKIEHASH;
print<<<END
{"name":"{$array[5]}","avatar":"{$logourl}","email":"{$email}"{$url},"status":"{$status}","cookiehash":"{$cookiehash}"}
END;
}else{
print<<<END
{"message":"{$message}","status":"{$status}"}
END;
}
}elseif($_POST['type']=="checkemail"){
if($status=="0"){
print<<<END
{"status":"{$status}"}
END;
}else{
print<<<END
{"message":"{$message}","status":"{$status}"}
END;
}
}
?>