<?php
//码支付
header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require_once dirname(__FILE__) . "../../../../../../wp-load.php";
ob_end_clean();

// 获取后台支付配置
$codepayConfig = _cao('codepay');
$mzf_appid  = $codepayConfig['mzf_appid']; //appid
$mzf_secret = $codepayConfig['mzf_secret']; //secret


ksort($_POST); //排序post参数
reset($_POST); //内部指针指向数组中的第一个元素
$sign = '';//初始化
foreach ($_POST AS $key => $val) { //遍历POST参数
    if ($val == '' || $key == 'sign') continue; //跳过这些不签名
    if ($sign) $sign .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
    $sign .= "$key=$val"; //拼接为url参数形式
}
if (!$_POST['pay_no'] || md5($sign . $mzf_secret) != $_POST['sign']) { //不合法的数据
    exit('fail');  //返回失败 继续补单
} else { 
    //商户本地订单号
    $out_trade_no = $_POST['pay_id'];
    //交易号
    $trade_no = $_POST['pay_no'];
    //发送支付成功回调用
    $RiProPay = new RiProPay;
    $RiProPay->send_order_trade_notify($out_trade_no,$trade_no,'ripropaysucc');
    echo 'success';exit();
}