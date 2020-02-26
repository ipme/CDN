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
 * payjs异步通知
 */

header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require_once dirname(__FILE__) . "../../../../../../wp-load.php";
ob_end_clean();
require_once get_template_directory() . '/inc/class/Payjs.class.php';
// 获取后台支付配置
$PayJsConfig = _cao('payjs');
// 配置通信参数
$config = [
    'mchid' => $PayJsConfig['mchid'],   // 配置商户号
    'key'   => $PayJsConfig['key'],   // 配置通信密钥
];
// 初始化 PAYJS
$payjs = new Payjs($config);

// 接收异步通知,无需关注验签动作,已自动处理
$notify_info = $payjs->notify();

if ($notify_info && is_array($notify_info) && $notify_info['attach'] == 'payjs_order_attach') {
    
    //商户本地订单号
    $out_trade_no = $notify_info['out_trade_no'];
    //交易号
    $trade_no = $notify_info['payjs_order_id'];

    // 处理本地业务逻辑
    if ($notify_info['transaction_id']) {
        //发送支付成功回调用
        $RiProPay = new RiProPay;
        $RiProPay->send_order_trade_notify($out_trade_no,$trade_no,'ripropaysucc');
        echo 'success';exit();
    } else {
        // 输出错误日志 可以在生产环境关闭 注释即可
        echo "error";exit();
    }
    exit();


}
echo "error";exit();