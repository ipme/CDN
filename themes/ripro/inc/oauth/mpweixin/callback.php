<?php
//启用 session
session_start();
// 要求noindex
wp_no_robots();


if (empty($_SESSION['CAO_mpweixin_scene_str'])) {
    wp_die('非法访问，没有经过第三方登录返回');
}

// 调取回调函数
$WeChat = new WeChat();
//获取用户信息
$mpwxresult = $WeChat->callback();

//赋值
$_SESSION['CAO_mpweixin_gologin'] = '';

$openid   = $mpwxresult; // 唯一ID
$userInfo = $mpwxresult; //第三方用户信息
// 处理本地业务逻辑
//
if ($openid && $userInfo) {
    $_prefix          = 'mpweixin';
    $_openid_meta_key = 'open_' . $_prefix . '_openid';
    // 初始化信息
    $metaInfo = array(
        'openid' => $openid,
        'name'   => $userInfo['nickname'],
        'bind'   => 1,
        'avatar' => $userInfo['headimgurl'],
    );

    global $wpdb, $current_user;

    // 查询meta
    $user_exist = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key=%s AND meta_value=%s", $_openid_meta_key, $openid));
    // 如果当前用户已登录，而$user_exist存在，即该开放平台账号连接被其他用户占用了，不能再重复绑定了
    $current_user_id = get_current_user_id();
    if ($current_user_id != 0 && isset($user_exist) && $current_user_id != $user_exist) {
        wp_die('<meta charset="UTF-8" />绑定失败，可能之前已有其他账号绑定，请先登录其他账户解绑。');
    }

    if (isset($user_exist) && (int) $user_exist > 0) {
        // 该开放平台账号已连接过WP系统，再次使用它直接登录
        $user_exist = (int) $user_exist;
        wp_set_current_user($user_exist);
        wp_set_auth_cookie($user_exist);
        $user = get_user_by('id', $user_exist);
        do_action('wp_login', $user->user_login, $user); // 保证挂载的action执行
        wp_safe_redirect(home_url('/user'));
        exit;
    } elseif ($current_user_id) {
        // Open 连接未被占用且当前已登录了本地账号, 那么直接绑定信息到该账号 case: 从个人资料设置中点击了绑定社交账号等操作
        update_user_meta($current_user_id, 'open_' . $_prefix . '_openid', $metaInfo['openid']);
        update_user_meta($current_user_id, 'open_' . $_prefix . '_bind', $metaInfo['bind']);
        update_user_meta($current_user_id, 'open_' . $_prefix . '_name', $metaInfo['name']);
        update_user_meta($current_user_id, 'open_' . $_prefix . '_avatar', $metaInfo['avatar']);
        wp_safe_redirect(home_url('/user'));
        exit;
    } else {
        // 该开放平台账号未连接过WP系统，使用它登录并分配和绑定一个WP本地新用户
        $login_name = "u" . mt_rand(1000, 9999) . mt_rand(1000, 9999);
        $user_pass  = wp_create_nonce(rand(10, 1000));
        $nickname   = $metaInfo['name'];
        $userdata   = array(
            'user_login'   => $login_name,
            'user_email'   => $login_name.'_mail@no.com',
            'display_name' => $nickname,
            'nickname'     => $nickname,
            'user_pass'    => $user_pass,
            'role'         => get_option('default_role'),
            'first_name'   => $nickname,
        );
        $user_id = wp_insert_user($userdata);
        if (is_wp_error($user_id)) {
            echo $user_id->get_error_message();
        } else {
            // 更新用户字段
            update_user_meta($user_id, 'open_' . $_prefix . '_openid', $metaInfo['openid']);
            update_user_meta($user_id, 'open_' . $_prefix . '_bind', $metaInfo['bind']);
            update_user_meta($user_id, 'open_' . $_prefix . '_name', $metaInfo['name']);
            update_user_meta($user_id, 'open_' . $_prefix . '_avatar', $metaInfo['avatar']);
            update_user_meta($user_id, 'user_avatar_type',$_prefix);

            //登录
            wp_set_auth_cookie($user_id, true, false);
            wp_safe_redirect($_SESSION['rurl']);
            $user = get_user_by('id', $user_id);
            do_action('wp_login', $user->user_login, $user); // 保证挂载的action执行
            wp_safe_redirect(home_url('/user'));
        }
        exit;
    }
}
