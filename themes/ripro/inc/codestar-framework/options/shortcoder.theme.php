<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'csf_demo_shortcodes';

//
// Create a shortcoder
//
CSF::createShortcoder($prefix, array(
    'button_title'   => '添加付费隐藏内容',
    'select_title'   => '选择添加的内容块',
    'insert_title'   => '插入简码',
    'show_in_editor' => true,
    'gutenberg'      => array(
        'title'       => 'RiPro主题简码',
        'description' => 'RiPro主题简码块',
        'icon'        => 'screenoptions',
        'category'    => 'widgets',
        'keywords'    => array('shortcode', 'csf', 'insert'),
        'placeholder' => '在这里写短代码...',
    ),
));

//
// A shortcode [rihide]隐藏部分付费内容[/rihide]
//
CSF::createSection($prefix, array(
    'title'     => '[rihide] 隐藏部分付费内容',
    'view'      => 'normal',
    'shortcode' => 'rihide',
    'fields'    => array(

        array(
            'id'    => 'content',
            'type'  => 'wp_editor',
            'title' => '',
            'desc'  => '[rihide]隐藏部分付费内容[/rihide] <br/> 注意：添加隐藏内容后，因为公用价格和折扣字段，所有资源类型优先为付费查看内容模式，侧边栏下载资源小工具将不显示',
        ),

    ),
));

/**
 * [shop_shortcode 付费查看部分内容]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T13:10:24+0800
 * @param    [type]                   $atts    [description]
 * @param    [type]                   $content [description]
 * @return   [type]                            [description]
 */
function shop_shortcode($atts, $content)
{
    global $post, $wpdb;
    $user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
    $atts    = shortcode_atts(array(
        'id' => 0,
    ), $atts, 'rihide');
    $post_id = $post->ID;
    if ($atts['id']) {
        $post_id = $atts['id'];
    }

    $CaoUser = new CaoUser($user_id);
    $PostPay = new PostPay($user_id, $post_id);

    // meta init
    $cao_price     = get_post_meta($post_id, 'cao_price', true);
    $cao_vip_rate  = get_post_meta($post_id, 'cao_vip_rate', true);
    $cao_paynum    = get_post_meta($post_id, 'cao_paynum', true);
    $cao_is_boosvip  = get_post_meta($post_id, 'cao_is_boosvip', true);
    $site_vip_name = _cao('site_vip_name');
    $site_money_ua = _cao('site_money_ua');
    if ($CaoUser->vip_status()) {
        $cao_this_am = ($cao_price * $cao_vip_rate) . $site_money_ua;
    } else {
        $cao_this_am = $cao_price . $site_money_ua;
    }

    // 优惠信息
    switch ($cao_vip_rate) {
        case 1:
            $rate_text = '暂无优惠';
            break;
        case 0:
            $rate_text = $site_vip_name . '免费';
            break;
        default:
            $rate_text = $site_vip_name . '价 ' . ($cao_vip_rate * 10) . ' 折';
    }
    if ($cao_is_boosvip) {
        $rate_text = $rate_text.' 永久'.$site_vip_name.'免费';
    }

    $cao_is_post_free = cao_is_post_free($user_id,$post_id);
    //如果免登录购买开启 并且没有登录
    if (_cao('is_ripro_nologin_pay','1') && !is_user_logged_in()) {
        if ($PostPay->isPayPost()) { //如果购买过 输出内容
            $do_shortcode = '<div class="content-hide-tips"><i class="fa fa-unlock-alt"></i>'; //原始内容
            $do_shortcode .= do_shortcode($content); //原始内容
            $do_shortcode .= '</div>'; //原始内容
        } elseif ($cao_is_post_free) {// 如果是免费资源 提示登陆查看
            $do_shortcode = '<div class="content-hide-tips"><i class="fa fa-lock"></i>';
            $do_shortcode .= '<span class="rate label label-warning">登录后免费查看</span>';
            $do_shortcode .= '<div class="login-false">当前隐藏内容需登录后查看';
            $do_shortcode .= '<div class="coin"><span class="label label-warning">免费</span></div>';
            $do_shortcode .= '</div>';
            $do_shortcode .= '<p class="t-c">已有<span class="red">' . _get_post_views() . '</span>人阅读</p>';
            $do_shortcode .= '<div class="pc-button">';
            $do_shortcode .= '<button type="button" class="login-btn btn btn--primary"><i class="fa fa-user"></i> 登录后查看</button>';
            $do_shortcode .= '</div>';
            $do_shortcode .= '</div>';
        } else {
            $create_nonce = wp_create_nonce('caopay-' . $post_id);
            $do_shortcode = '<div class="content-hide-tips"><i class="fa fa-lock"></i>';
            $do_shortcode .= '<span class="rate label label-warning">' . $rate_text . '</span>';
            $do_shortcode .= '<div class="login-false">当前隐藏内容需要支付';
            $do_shortcode .= '<div class="coin"><span class="label label-warning">' . $cao_this_am . '</span></div>';
            $do_shortcode .= '</div>';
            $do_shortcode .= '<p class="t-c">已有<span class="red">' . $cao_paynum . '</span>人支付</p>';
            $do_shortcode .= '<div class="pc-button">';
            if ($cao_is_boosvip && is_boosvip_status($user_id)) {
                $do_shortcode .= '<button type="button" class="click-pay btn btn--secondary" data-postid="' . $post_id . '" data-nonce="' . $create_nonce . '" data-price="0' . $site_money_ua . '"><i class="fa fa-diamond"></i> 永久'.$site_vip_name.'免费查看</button>';
            }else{
                $do_shortcode .= '<button type="button" class="click-pay btn btn--secondary" data-postid="' . $post_id . '" data-nonce="' . $create_nonce . '" data-price="' . $cao_this_am . '"><i class="fa fa-money"></i> 支付查看</button>';
            }
            $do_shortcode .= '</div>';
            $do_shortcode .= '</div>';
        }

    }elseif (is_user_logged_in()){ // 已经登录 忽略乎免登录逻辑
        if ($PostPay->isPayPost() || $cao_is_post_free) { //如果购买过 或者是免费资源 输出内容
            $do_shortcode = '<div class="content-hide-tips"><i class="fa fa-unlock-alt"></i>'; //原始内容
            $do_shortcode .= do_shortcode($content); //原始内容
            $do_shortcode .= '</div>'; //原始内容
        }else{
            $create_nonce = wp_create_nonce('caopay-' . $post_id);
            $do_shortcode = '<div class="content-hide-tips"><i class="fa fa-lock"></i>';
            $do_shortcode .= '<span class="rate label label-warning">' . $rate_text . '</span>';
            $do_shortcode .= '<div class="login-false">当前隐藏内容需要支付';
            $do_shortcode .= '<div class="coin"><span class="label label-warning">' . $cao_this_am . '</span></div>';
            $do_shortcode .= '</div>';
            $do_shortcode .= '<p class="t-c">已有<span class="red">' . $cao_paynum . '</span>人支付</p>';
            $do_shortcode .= '<div class="pc-button">';
            if ($cao_is_boosvip && is_boosvip_status($user_id)) {
                $do_shortcode .= '<button type="button" class="click-pay btn btn--secondary" data-postid="' . $post_id . '" data-nonce="' . $create_nonce . '" data-price="0' . $site_money_ua . '"><i class="fa fa-diamond"></i> 永久'.$site_vip_name.'免费查看</button>';
            }else{
                $do_shortcode .= '<button type="button" class="click-pay btn btn--secondary" data-postid="' . $post_id . '" data-nonce="' . $create_nonce . '" data-price="' . $cao_this_am . '"><i class="fa fa-money"></i> 立即购买</button>';
            }
            $do_shortcode .= '</div>';
            $do_shortcode .= '</div>';
        }
    }else{ //没有开启免登录 没有登录 输出正常逻辑
        $do_shortcode = '<div class="content-hide-tips"><i class="fa fa-lock"></i>';
        $do_shortcode .= '<span class="rate label label-warning">' . $rate_text . '</span>';
        $do_shortcode .= '<div class="login-false">当前隐藏内容需要支付';
        $do_shortcode .= '<div class="coin"><span class="label label-warning">' . $cao_this_am . '</span></div>';
        $do_shortcode .= '</div>';
        $do_shortcode .= '<p class="t-c">已有<span class="red">' . $cao_paynum . '</span>人支付</p>';
        $do_shortcode .= '<div class="pc-button">';
        $do_shortcode .= '<button type="button" class="login-btn btn btn--primary"><i class="fa fa-user"></i> 登录购买</button>';
        $do_shortcode .= '</div>';
        $do_shortcode .= '</div>';
    }

    return $do_shortcode;

}
add_shortcode('rihide', 'shop_shortcode');
