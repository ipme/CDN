<?php
/**
 * RiPro是一个优秀的主题，首页拖拽布局，高级筛选，自带会员生态系统，超全支付接口，你喜欢的样子我都有！
 * 正版唯一购买地址，全自动授权下载使用：https://vip.ylit.cc/
 * 作者唯一QQ：200933220 （油条）
 * 承蒙您对本主题的喜爱，我们愿向小三一样，做大哥的女人，做大哥网站中最想日的一个。
 * 能理解使用盗版的人，但是不能接受传播盗版，本身主题没几个钱，主题自有支付体系和会员体系，盗版风险太高，鬼知道那些人乱动什么代码，无利不起早。
 * 开发者不易，感谢支持，更好的更用心的等你来调教
 */

use \Yurun\Util\HttpRequest;
use Yurun\PaySDK\Lib\XML;
class Payjs
{
    private $mchid;
    private $key;
    private $api_url_native;
    private $api_url_cashier;
    private $api_url_refund;
    private $api_url_close;
    private $api_url_check;
    private $api_url_user;
    private $api_url_info;
    private $api_url_bank;
    private $api_url_jsapi;

    public function __construct($config = null)
    {
        if (!$config) exit('config needed');

        $this->mchid = $config['mchid'];
        $this->key   = $config['key'];
        $api_url     = isset($config['api_url']) ? $config['api_url'] : 'https://payjs.cn/api/';

        $this->api_url_native  = $api_url . 'native';
        $this->api_url_cashier = $api_url . 'cashier';
        $this->api_url_refund  = $api_url . 'refund';
        $this->api_url_close   = $api_url . 'close';
        $this->api_url_check   = $api_url . 'check';
        $this->api_url_user    = $api_url . 'user';
        $this->api_url_info    = $api_url . 'info';
        $this->api_url_bank    = $api_url . 'bank';
        $this->api_url_jsapi   = $api_url . 'jsapi';
    }

    // 扫码支付
    public function native(array $data)
    {
        $this->url = $this->api_url_native;
        return $this->post($data);
    }

    // JSAPI 模式
    public function jsapi(array $data)
    {
        $this->url = $this->api_url_jsapi;
        $data      = $this->sign($data);
        $url       = $this->url . '?' . http_build_query($data);
        return $url;
    }

    // 收银台模式
    public function cashier(array $data)
    {
        $this->url = $this->api_url_cashier;
        $data      = $this->sign($data);
        $url       = $this->url . '?' . http_build_query($data);
        return $url;
    }

    // 退款
    public function refund($payjs_order_id)
    {
        $this->url = $this->api_url_refund;
        $data      = ['payjs_order_id' => $payjs_order_id];
        return $this->post($data);
    }

    // 关闭订单
    public function close($payjs_order_id)
    {
        $this->url = $this->api_url_close;
        $data      = ['payjs_order_id' => $payjs_order_id];
        return $this->post($data);
    }

    // 检查订单
    public function check($payjs_order_id)
    {
        $this->url = $this->api_url_check;
        $data      = ['payjs_order_id' => $payjs_order_id];
        return $this->post($data);
    }

    // 用户资料
    public function user($openid)
    {
        $this->url = $this->api_url_user;
        $data      = ['openid' => $openid];
        return $this->post($data);
    }

    // 商户资料
    public function info()
    {
        $this->url = $this->api_url_info;
        $data      = [];
        return $this->post($data);
    }

    // 银行资料
    public function bank($name)
    {
        $this->url = $this->api_url_bank;
        $data      = ['bank' => $name];
        return $this->post($data);
    }

    // 异步通知接收
    public function notify()
    {
        $data = $_POST;
        if ($this->checkSign($data) === true) {
            return $data;
        } else {
            return false;
        }
    }

    // 数据签名
    public function sign(array $data)
    {
        $data['mchid'] = $this->mchid;
        $data = array_filter($data);
        ksort($data);
        $data['sign'] = strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $this->key)));
        return $data;
    }

    // 校验数据签名
    public function checkSign($data)
    {
        $in_sign = $data['sign'];
        unset($data['sign']);
        $data = array_filter($data);
        ksort($data);
        $sign = strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $this->key)));
        return $in_sign == $sign ? true : false;
    }

    // 数据发送
    public function post($data)
    {
        $data   = $this->sign($data);
        $http = new HttpRequest;
        $response = $http->send($this->url, $data, 'POST');
        $result = $response->json(true);
        return $response->json(true);
    }

}
