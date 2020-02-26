<?php
/**
 * RiPro是一个优秀的主题，首页拖拽布局，高级筛选，自带会员生态系统，超全支付接口，你喜欢的样子我都有！
 * 正版唯一购买地址，全自动授权下载使用：https://vip.ylit.cc/
 * 作者唯一QQ：200933220 （油条）
 * 承蒙您对本主题的喜爱，我们愿向小三一样，做大哥的女人，做大哥网站中最想日的一个。
 * 能理解使用盗版的人，但是不能接受传播盗版，本身主题没几个钱，主题自有支付体系和会员体系，盗版风险太高，鬼知道那些人乱动什么代码，无利不起早。
 * 开发者不易，感谢支持，更好的更用心的等你来调教
 */

/**
 * 支付宝同步回调
 */

header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require_once dirname(__FILE__) . "../../../../../../wp-load.php";
ob_end_clean();

if (empty($_GET)) {
    echo '非法请求';exit();
}

// 获取后台支付宝配置
$aliPayConfig = _cao('alipay');

// 公共配置
$params         = new \Yurun\PaySDK\Alipay\Params\PublicParams;
$params->md5Key = $aliPayConfig['md5Key'];

// SDK实例化，传入公共配置
$pay = new \Yurun\PaySDK\Alipay\SDK($params);

if ($pay->verifyCallback($_GET)) {

	// 验证成功 $_GET['trade_no']
	$out_trade_no = $_GET['out_trade_no'];
	$_post_id = 0;
	// 查询本地订单
	$RiProPay = new RiProPay;
    $postData = $RiProPay->get_order_info($out_trade_no);
    if ($postData) {
    	$_post_id = intval($postData['post_id']);
    	$RiProPay->AddPayPostCookie($postData['user_id'],$_post_id,$postData['order_trade_no']);
    }
    if ($_post_id>0) {
    	wp_safe_redirect( get_the_permalink( $_post_id ) );
    }else{
    	wp_safe_redirect(home_url('/user'));
    }
	// 验证结束

} else {
    wp_safe_redirect(home_url());
}
