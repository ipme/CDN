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
 * 下载地址加密flush shangche
 *
 */
header("Content-type:text/html;character=utf-8");
global $current_user;

$post_id = !empty($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
$ref = !empty($_GET['ref']) ? (int)$_GET['ref'] : 0;

if (!$post_id && !$ref) {
    wp_die('地址错误或者URL参数错误','URL参数错误');
}

// 开始下载处理
if (isset($post_id) && empty($ref)):
    $uid = $current_user->ID;
    $cao_is_post_free = cao_is_post_free($uid,$post_id);
    if (!is_user_logged_in() && !_cao('is_ripro_nologin_pay','1')) {
        wp_die('请登录后下载资源包','请登录下载');
    }
    if ($cao_is_post_free && !is_user_logged_in()) {
        wp_die('免费资源请登录后进行下载','请登录下载');
    }
    

    // 判断是否有权限下载
    $CaoUser = new CaoUser($uid);
    $PostPay = new PostPay($uid, $post_id);
    $_downurl     = get_post_meta($post_id, 'cao_downurl', true);
    $home_url=esc_url(home_url());
    // 本地文件做处理
    if(strpos($_downurl,$home_url) !== false){ 
    	$parse_url = parse_url($_downurl);
    	$_downurl  =$parse_url['path'];
	}

    if ($PostPay->isPayPost() || $cao_is_post_free) {
    	if(!is_user_logged_in() && _cao('is_ripro_nologin_pay','1')){
            $before_paynum = get_post_meta($post_id, 'cao_paynum', true);
            update_post_meta($post_id, 'cao_paynum', (int) $before_paynum + 1);
			$flush = _download_file($_downurl);
            exit();
		}
        // 判断会员类型 判断下载次数
        $vip_status = $CaoUser->vip_status();
        $this_vip_downum = cao_vip_downum($uid,$vip_status);
        // var_dump($this_vip_downum);die;
        if ($this_vip_downum['is_down'] || ( $PostPay->isPayPost() )) {
            //没有真实购买 但是使用免费权限下载 将计算下载次数
            if (!$PostPay->isPayPost() && $cao_is_post_free) {
                update_user_meta($uid, 'cao_vip_downum', $this_vip_downum['today_down_num'] + 1); //更新+1
                // 更新完成 更新资源销售数量 输出成功信息
                $before_paynum = get_post_meta($post_id, 'cao_paynum', true);
                update_post_meta($post_id, 'cao_paynum', (int) $before_paynum + 1);

            }
            # // 开始下载缓冲...
            $flush = _download_file($_downurl);
            exit();
        } else {
            wp_die('今日下载次数已用：'.$this_vip_downum['today_down_num'].'次,剩余下载次数：'.$this_vip_downum['over_down_num'],'下载次数超出限制');exit();
        }
    	
    }else{
    	wp_die('您没有购买此资源或下载权限错误','非法下载');
    }
endif;

// 开始推广地址处理
if (isset($ref) && empty($post_id)):
    session_start();
    $from_user_id = $ref;
    // empty($_SESSION['WPAY_code_captcha']);
    $_SESSION['cao_from_user_id'] = $from_user_id;
    header("Location:" . home_url());
    exit();
endif;
// 结束推广地址处理


wp_die('地址错误或者URL参数错误');
