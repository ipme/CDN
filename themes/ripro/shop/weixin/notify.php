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
 * weixin异步通知
 */

header('Content-type:text/html; Charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
ob_start();
require_once dirname(__FILE__) . "../../../../../../wp-load.php";
ob_end_clean();

// 获取后台支付配置
$wxPayConfig = _cao('weixinpay');

// 公共配置
$params         = new \Yurun\PaySDK\Weixin\Params\PublicParams;
$params->appID  = $wxPayConfig['appid'];
$params->mch_id = $wxPayConfig['mch_id'];
$params->key    = $wxPayConfig['key'];
// SDK实例化，传入公共配置
$sdk = new \Yurun\PaySDK\Weixin\SDK($params);

class PayNotify extends \Yurun\PaySDK\Weixin\Notify\Pay
{
    /**
     * 后续执行操作
     * @return void
     */
    protected function __exec()
    {
        // 支付成功处理，一般做订单处理，$this->data 是从微信发送来的数据
        // file_put_contents(__DIR__ . '/notify_result.txt', date('Y-m-d H:i:s') . ':' . var_export($this->data, true));

        //商户本地订单号
        $out_trade_no = $this->data['out_trade_no'];
        //支付宝交易号
        $trade_no = $this->data['transaction_id'];

        //发送支付成功回调用
        $RiProPay = new RiProPay;
        $RiProPay->send_order_trade_notify($out_trade_no,$trade_no,'ripropaysucc');
        // 告诉微信我处理过了，不要再通过了
        $this->reply(true, 'OK');
    }
}

$payNotify = new PayNotify;

try {
    $sdk->notify($payNotify);
} catch (Exception $e) {
    file_put_contents(__DIR__ . '/notify_result.txt', $e->getMessage() . ':' . var_export($payNotify->data, true));
}
