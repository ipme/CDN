<?php
/**
 * RiPro是一个优秀的主题，首页拖拽布局，高级筛选，自带会员生态系统，超全支付接口，你喜欢的样子我都有！
 * 正版唯一购买地址，全自动授权下载使用：https://vip.ylit.cc/
 * 作者唯一QQ：200933220 （油条）
 * 承蒙您对本主题的喜爱，我们愿向小三一样，做大哥的女人，做大哥网站中最想日的一个。
 * 能理解使用盗版的人，但是不能接受传播盗版，本身主题没几个钱，主题自有支付体系和会员体系，盗版风险太高，鬼知道那些人乱动什么代码，无利不起早。
 * 开发者不易，感谢支持，更好的更用心的等你来调教
 */

//**切换暗黑风格
function tap_dark()
{
    session_start();
    $is_ripro_dark   = !empty($_POST['is_ripro_dark']) ? intval($_POST['is_ripro_dark']) : 0;
    $_SESSION['is_ripro_dark'] = $is_ripro_dark;
    echo $_SESSION['is_ripro_dark'];
    exit();
}
add_action('wp_ajax_tap_dark', 'tap_dark');
add_action('wp_ajax_nopriv_tap_dark', 'tap_dark');

/**
 * [ajax_search AJAX搜索]
 * @Author   Dadong2g
 * @DateTime 2019-08-21T23:35:34+0800
 * @return   [type]                   [JSON Arr]
 */
function ajax_search()
{
    global $wp_query;
    header('Content-type:application/json; Charset=utf-8');
    $text   = !empty($_POST['text']) ? esc_sql($_POST['text']) : null;
    $args = array(
        's' => $text,'posts_per_page' => 5
    );
    $array_posts = array ();
    $data = new WP_Query($args);
    while ( $data->have_posts() ) : $data->the_post();
        array_push($array_posts, array("title"=>get_the_title(),"url"=>get_permalink(),"img"=>_get_post_timthumb_src() )); 
    endwhile;
    echo json_encode($array_posts);
    exit();
}
add_action('wp_ajax_ajax_search', 'ajax_search');
add_action('wp_ajax_nopriv_ajax_search', 'ajax_search');


/**
 * [user_login 用户登录]
 * @Author   Dadong2g
 * @DateTime 2019-06-02T15:34:38+0800
 * @return   [type]                   [description]
 */
function user_login()
{
    session_start();
    header('Content-type:application/json; Charset=utf-8');
    $username   = !empty($_POST['username']) ? esc_sql($_POST['username']) : null;
    $password   = !empty($_POST['password']) ? esc_sql($_POST['password']) : null;
    $rememberme = !empty($_POST['rememberme']) ? esc_sql($_POST['rememberme']) : null;
    if (_cao('is_close_wplogin')) {
        echo json_encode(array('status' => '0', 'msg' => '仅开放社交账号登录'));exit;
    }
    $login_data                  = array();
    $login_data['user_login']    = $username;
    $login_data['user_password'] = $password;
    $login_data['remember']      = false;
    if (isset($rememberme) && $rememberme == '1') {
        $login_data['remember'] = true;
    }
    if (!$username || !$password) {
        echo json_encode(array('status' => '0', 'msg' => '请输入登录账号/密码'));exit;
    }
    //是否腾讯验证
    if (_cao('is_captcha_qq','0') && @$_SESSION['is_tencentcaptcha'] == 0) {
       $_SESSION['is_tencentcaptcha'] = 0;
       echo json_encode(array('status' => '0', 'msg' => '安全验证失败'));exit;
    }
    $user_verify = wp_signon($login_data, false);
    if (is_wp_error($user_verify)) {
        echo json_encode(array('status' => '0', 'msg' => '用户名或密码错误'));exit;
    } else {
        echo json_encode(array('status' => '1', 'msg' => '登录成功'));exit;
    }
    exit();
}
add_action('wp_ajax_user_login', 'user_login');
add_action('wp_ajax_nopriv_user_login', 'user_login');

/**
 * [user_register 注册新用户]
 * @Author   Dadong2g
 * @DateTime 2019-06-02T15:34:30+0800
 * @return   [type]                   [description]
 */
function user_register()
{
    session_start();
    header('Content-type:application/json; Charset=utf-8');

    $user_name  = !empty($_POST['user_name']) ? sanitize_user($_POST['user_name']) : null;
    $user_email = !empty($_POST['user_email']) ? apply_filters('user_registration_email', $_POST['user_email']) : null;
    $user_pass  = !empty($_POST['user_pass']) ? esc_sql($_POST['user_pass']) : null;
    if (!$user_name || !$user_email || !$user_pass) {
        echo json_encode(array('status' => '0', 'msg' => '注册信息错误'));exit;
    }
    if (_cao('is_close_wpreg')) {
        echo json_encode(array('status' => '0', 'msg' => '仅开放社交账号注册'));exit;
    }
    if (!validate_username($user_name)) {
        echo json_encode(array('status' => '0', 'msg' => '用户名包含无效字符'));exit;
    }
    if (username_exists($user_name)) {
        echo json_encode(array('status' => '0', 'msg' => '该用户名已被注册'));exit;
    }
    if (!is_email($user_email)) {
        echo json_encode(array('status' => '0', 'msg' => '邮箱地址错误'));exit;
    }
    if (email_exists($user_email)) {
        echo json_encode(array('status' => '0', 'msg' => '邮箱已经被注册'));exit;
    }
    if (strlen($user_pass) < 6) {
        echo json_encode(array('status' => '0', 'msg' => '密码长度不得小于6位'));exit;
    }
    // 是否需要邮箱验证
    if (_cao('is_email_reg_cap')) {
        if (empty($_POST['captcha']) || empty($_SESSION['CAO_code_captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['CAO_code_captcha']) {
            echo json_encode(array('status' => '0', 'msg' => '验证码错误'));exit;
        }
        if ($_SESSION['CAO_code_captcha_email'] != $user_email) {
            echo json_encode(array('status' => '0', 'msg' => '验证码与邮箱不对应'));exit;
        }
    }
    //是否腾讯验证
    if (_cao('is_captcha_qq','0') && @$_SESSION['is_tencentcaptcha'] == 0) {
       $_SESSION['is_tencentcaptcha'] = 0;
       echo json_encode(array('status' => '0', 'msg' => '安全验证失败'));exit;
    }
    // 验证通过
    $nweUserData = array(
        'ID'         => '',
        'user_login' => $user_name,
        'user_pass'  => $user_pass,
        'user_email' => $user_email,
        'role'       => get_option('default_role'),
    );
    $user_id = wp_insert_user($nweUserData);

    if (is_wp_error($user_id)) {
        echo json_encode(array('status' => '0', 'msg' => '注册失败，请重试'));exit;
    } else {
        wp_set_auth_cookie($user_id, true, false);
        wp_set_current_user($user_id);
        //发送邮件
        $message = __('注册成功！') . "\r\n\r\n";
        $message .= sprintf(__('用户名: %s'), $user_name) . "\r\n\r\n";
        //$message .= sprintf(__('密码: %s'), $user_pass) . "\r\n\r\n";

        if (_cao('is_mail_nitfy_reg')) {
            _sendMail($user_email, '注册信息', $message);
        }
        echo json_encode(array('status' => '1', 'msg' => '注册成功'));exit;
    }
    exit();
}
add_action('wp_ajax_user_register', 'user_register');
add_action('wp_ajax_nopriv_user_register', 'user_register');

/**
 * [sessioncode 生产验证码]
 * @Author   Dadong2g
 * @DateTime 2019-06-02T15:34:20+0800
 * @param    [type]                   $email [description]
 * @return   [type]                          [description]
 */
function sessioncode($email)
{
    session_start();
    $originalcode = '0,1,2,3,4,5,6,7,8,9';
    $originalcode = explode(',', $originalcode);
    $countdistrub = 10;
    $_dscode      = "";
    $counts       = 6;
    for ($j = 0; $j < $counts; $j++) {
        $dscode = $originalcode[rand(0, $countdistrub - 1)];
        $_dscode .= $dscode;
    }
    $_SESSION['CAO_code_captcha']       = strtolower($_dscode);
    $_SESSION['CAO_code_captcha_email'] = $email;
    $message                            = '验证码：' . $_dscode;
    $send_email                         = _sendMail($email, '验证码', $message);
    if ($send_email) {
        return true;
    }
    return false;
}

/**
 * [captcha_email 验证邮箱]
 * @Author   Dadong2g
 * @DateTime 2019-06-02T15:34:06+0800
 * @return   [type]                   [description]
 */
function captcha_email()
{
    header('Content-type:application/json; Charset=utf-8');
    global $wpdb;
    $user_email = !empty($_POST['user_email']) ? esc_sql($_POST['user_email']) : null;
    $user_email = apply_filters('user_registration_email', $user_email);
    $user_email = $wpdb->_escape(trim($user_email));

    if (email_exists($user_email)) {
        echo json_encode(array('status' => '0', 'msg' => '邮箱已存在'));exit;
    } else {
        $send_email = sessioncode($user_email);
        if ($send_email) {
            echo json_encode(array('status' => '1', 'msg' => '发送成功'));exit;
        } else {
            echo json_encode(array('status' => '0', 'msg' => '发送失败'));exit;
        }
    }
    exit();
}
add_action('wp_ajax_captcha_email', 'captcha_email');
add_action('wp_ajax_nopriv_captcha_email', 'captcha_email');

//腾讯防水墙
function tencentcaptcha()
{
    session_start();
    header('Content-type:application/json; Charset=utf-8');
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    }else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }else if(!empty($_SERVER["REMOTE_ADDR"])){
        $cip = $_SERVER["REMOTE_ADDR"];
    }else{
        $cip = '';
    }
    preg_match("/[\d\.]{7,15}/", $cip, $cips);
    $cip = isset($cips[0]) ? $cips[0] : 'unknown';
    unset($cips);

    $AppSecretKey = _cao('captcha_qq_secretkey','');
    $appid = !empty($_POST['appid']) ? $_POST['appid'] : null;
    $Ticket = !empty($_POST['Ticket']) ? $_POST['Ticket'] : null;
    $Randstr = !empty($_POST['Randstr']) ? $_POST['Randstr'] : null;
    $UserIP = $cip; 
    $url = "https://ssl.captcha.qq.com/ticket/verify";
    $params = array(
        "aid" => $appid,
        "AppSecretKey" => $AppSecretKey,
        "Ticket" => $Ticket,
        "Randstr" => $Randstr,
        "UserIP" => $UserIP
    );
    $paramstring = http_build_query($params);
    $geturl = $url.'?'.$paramstring;
    $content = tx_http_curl($geturl);
    $result = json_decode($content,true);
    if($result){
        if($result['response'] == 1){
            $_SESSION['is_tencentcaptcha'] = 1;
            echo json_encode(array('status' => '1', 'msg' => '验证通过'));exit;
        }else{
            $_SESSION['is_tencentcaptcha'] = 0;
            echo json_encode(array('status' => '0', 'msg' => $result['err_msg']));exit;
        }
    }else{
        $_SESSION['is_tencentcaptcha'] = 0;
        echo json_encode(array('status' => '0', 'msg' => '请求失败'));exit;
    }
    exit();
}
add_action('wp_ajax_tencentcaptcha', 'tencentcaptcha');
add_action('wp_ajax_nopriv_tencentcaptcha', 'tencentcaptcha');

function tx_http_curl($url,$type='get',$res='json',$arr=''){
    //1.初始化curl
    $ch = curl_init();
    //2.设置curl的参数
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($type == 'post'){
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
    }
    //3.采集
    $output = curl_exec($ch);
    //4.关闭
    curl_close($ch);
    if($res=='json'){
        if(curl_error($ch)){
            //请求失败，返回错误信息
            return curl_error($ch);
        }else{
            //请求成功，返回信息
            return $output;
        }
    }
}

/**
 * @package caozhuti
 */

/**
 * [isLoginCheck 登陆状态验证]
 * @Author   Dadong2g
 * @DateTime 2019-05-31T13:12:49+0800
 * @return   boolean                  [description]
 */
function isLoginCheck()
{
    if (!is_user_logged_in()) {
        header('Allow: POST');
        header('HTTP/1.1 503 Method Not Allowed');
        header('Content-Type: text/plain');
        exit;
    }
}



//投稿 write_post
function cao_write_post()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid = $current_user->ID;
    isLoginCheck(); //检测登录
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    if ($nonce && !wp_verify_nonce($nonce, 'caoclick-' . $uid)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));exit;
    }
    $edit_id = !empty($_POST['edit_id']) ? (int)sanitize_text_field(trim($_POST['edit_id'])) : 0;
    $post_title = !empty($_POST['post_title']) ? sanitize_text_field(trim($_POST['post_title'])) : '';
    $post_content = !empty($_POST['post_content']) ? trim($_POST['post_content']) : '';
    $post_excerpt = !empty($_POST['post_excerpt']) ? sanitize_text_field(trim($_POST['post_excerpt'])) : '';
    $post_cat = !empty($_POST['post_cat']) ? (int)sanitize_text_field(trim($_POST['post_cat'])) : 1;
    $cao_status = !empty($_POST['cao_status']) ? trim($_POST['cao_status']) : 0;
    $cao_status = ($cao_status == 'fee') ? 1 : 0;
    $cao_price = !empty($_POST['cao_price']) ? (int)sanitize_text_field(trim($_POST['cao_price'])) : 0;
    $cao_vip_rate = !empty($_POST['cao_vip_rate']) ? sanitize_text_field(trim($_POST['cao_vip_rate'])) : 1;
    $cao_pwd = !empty($_POST['cao_pwd']) ? sanitize_text_field(trim($_POST['cao_pwd'])) : '';
    $cao_downurl = !empty($_POST['cao_downurl']) ? esc_url(trim($_POST['cao_downurl'])) : '';
    $post_status = !empty($_POST['post_status']) ? $_POST['post_status'] : '';
    $post_status = in_array($post_status, array('publish', 'draft', 'pending')) ? $post_status : 'draft';

    if (!_cao('is_all_publish_posts') && !current_user_can('publish_posts')) {
        echo json_encode(array('status' => '0', 'msg' => '您没有权限发布或修改文章'));exit;
    }

    if(strlen($post_content) < 100) {
        echo json_encode(array('status' => '0', 'msg' => '文章内容最低100个字符'));exit;
    }
    // 如果是编辑
    if ($edit_id > 0) {
        // 插入文章
        $new_post = wp_update_post( array( //Return: The ID of the post if the post is successfully updated in the database. Otherwise returns 0
            'ID'            => $edit_id,
            'post_title'    => $post_title,
            'post_excerpt'  => $post_excerpt,
            'post_content'  => $post_content,
            'post_status'   => $post_status,
            'post_author'   => get_current_user_id(),
            'post_category' => array($post_cat)
        ) );
    }else{
        // 插入文章
        $new_post = wp_insert_post( array(
            'post_title'    => $post_title,
            'post_excerpt'  => $post_excerpt,
            'post_content'  => $post_content,
            'post_status'   => $post_status,
            'post_author'   => get_current_user_id(),
            'post_category' => array($post_cat),
            'tags_input'    => ''
        ) );
    }
    

    if($new_post instanceof WP_Error) {
        echo json_encode(array('status' => '0', 'msg' => '网络错误，请重试或联系管理员'));exit;
    }

    // 如果是直接发布的 挂钩 用于后期添加
    if ($post_status == 'publish') {
        do_action('cao_immediate_to_publish', $new_post);
    }
    
    // 更新Meta
    $_cao_status = ($cao_status>0) ? 1 : 0 ;
    update_post_meta($new_post, 'cao_status', $_cao_status);
    update_post_meta($new_post, 'cao_price', $cao_price);
    update_post_meta($new_post, 'cao_vip_rate', $cao_vip_rate);
    update_post_meta($new_post, 'cao_pwd', $cao_pwd);
    update_post_meta($new_post, 'cao_downurl', $cao_downurl);
    update_post_meta($new_post, 'post_style', 'sidebar');

    echo json_encode(array('status' => '1', 'msg' => '提交成功，审核后公开'));exit;


}
add_action('wp_ajax_cao_write_post', 'cao_write_post');
add_action('wp_ajax_nopriv_cao_write_post', 'cao_write_post');



// 上传头像avatar_photo
function update_avatar_photo()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid = $current_user->ID;
    isLoginCheck(); //检测登录
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $file = !empty($_FILES['file']) ? $_FILES['file'] : null;
    if ($nonce && !wp_verify_nonce($nonce, 'caoclick-' . $uid)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));exit;
    }

    if (is_uploaded_file($file['tmp_name']) && is_user_logged_in()) {
        $picname = $file['name'];
        $picsize = $file['size'];
        $arrType = array('image/jpg', 'image/gif', 'image/png', 'image/bmp', 'image/pjpeg', "image/jpeg");
        $userid  = $uid;
        $rand    = (rand(10, 100));
        if ($picname != "") {
            if ($picsize > 200400) {
                echo json_encode(array('status' => '0', 'msg' => '头像最大限制200KB'));exit;
            } elseif (!in_array($file['type'], $arrType)) {
                echo json_encode(array('status' => '0', 'msg' => '图片类型错误'));exit;
            } else {
                $pics = 'avatar-' . $userid . '.jpg';
                // 上传生成的海报图片至指定文件夹
                $upload_dir = wp_upload_dir();
                $upfile = $upload_dir['basedir'] . '/avatar/';
                if (!is_dir($upfile)) {
                    wp_mkdir_p($upfile);
                }
                $pic_path = $upfile . $pics;
                if (move_uploaded_file($file['tmp_name'], $pic_path)) {
                    update_user_meta($userid, 'user_custom_avatar', get_bloginfo('url') . '/wp-content/uploads/avatar/' . $pics);
                    echo json_encode(array('status' => '1', 'msg' => '上传成功'));exit;
                } else {
                    echo json_encode(array('status' => '0', 'msg' => '上传失败'));exit;
                }
            }
        }
    }
    echo json_encode(array('status' => '0', 'msg' => '文件错误'));exit;

}
add_action('wp_ajax_update_avatar_photo', 'update_avatar_photo');
add_action('wp_ajax_nopriv_update_avatar_photo', 'update_avatar_photo');




/**
 * [update_img 新增文件类型验证安全]
 * @Author   Dadong2g
 * @DateTime 2019-10-25T20:20:45+0800
 * @return   [type]                   [description]
 */
function update_img()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid = $current_user->ID;
    isLoginCheck(); //检测登录
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $file = !empty($_FILES['file']) ? $_FILES['file'] : null;
    if ($nonce && !wp_verify_nonce($nonce, 'caoclick-' . $uid)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));exit;
    }
    $file_index = mb_strrpos($file["name"],'.'); //扩展名定位

    //图片验证
    $is_img = getimagesize($file["tmp_name"]);
    
    if(!$is_img && true){
        echo json_encode(array('status' => '0', 'msg' => '上传文件类型错误'));exit;
    }
    //图片类型验证
    $image_type = ['image/jpg', 'image/gif', 'image/png', 'image/bmp', 'image/pjpeg', "image/jpeg"];
    if(!in_array($file['type'], $image_type) && true){
        echo json_encode(array('status' => '0', 'msg' => '禁止上传非图片类型文件'));exit;
    }
    //图片后缀验证
    $postfix = ['.png','.jpg','.jpeg','pjpeg','gif','bmp'];
    $file_postfix = strtolower(mb_substr($file["name"], $file_index));
    if(!in_array($file_postfix, $postfix) && true){
        echo json_encode(array('status' => '0', 'msg' => '上传后缀不允许'));exit;
    }
    if ( !empty( $file ) ) {
        // 获取上传目录信息
        $wp_upload_dir = wp_upload_dir();
        // 将上传的图片文件移动到上传目录 md5纯命名图片
        $basename   = _new_filename($file['name']);
        $filename   = $wp_upload_dir['path'] . '/' . $basename;
        $re         = rename( $file['tmp_name'], $filename );
        $attachment = array(
                'guid'           => $wp_upload_dir['url'] . '/' . $basename,
                'post_mime_type' => $file['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', $basename ),
                'post_content'   => '',
                'post_status'    => 'inherit'
        );
        $attach_id  = wp_insert_attachment( $attachment, $filename );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        // 返回图片地址和状态
        echo json_encode(
            array('errno' => '0',
             'data' => array(wp_get_attachment_url( $attach_id )) 
            )
        );exit;
    }


    // 返回图片地址和状态
    echo json_encode(array('errno' => '1', 'data' => array()));exit;
}
add_action('wp_ajax_update_img', 'update_img');
add_action('wp_ajax_nopriv_update_img', 'update_img');





/**
 * [cdk_pay 卡密充值]
 * @Author   Dadong2g
 * @DateTime 2019-06-02T15:33:58+0800
 * @return   [type]                   [description]
 */
function cdk_pay()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid = $current_user->ID;
    isLoginCheck(); //检测登录
    $cdkcode = !empty($_POST['cdkcode']) ? esc_sql($_POST['cdkcode']) : null;
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    if ($nonce && !wp_verify_nonce($nonce, 'caoclick-' . $uid)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));exit;
    }
    // 验证长度
    if ($cdkcode && strlen($cdkcode) != 12) {
        echo json_encode(array('status' => '0', 'msg' => '卡密错误'));exit;
    }

    // 实例化卡密
    $CaoCdk    = new CaoCdk();
    $cdk_money = sprintf('%0.2f', $cdk_money);
    $cdk_money = $CaoCdk->checkCdk($cdkcode);
    if (!$cdk_money) {
        echo json_encode(array('status' => '0', 'msg' => '卡密无效'));exit;
    }

    // 卡密有效 进行换算
    $CaoUser   = new CaoUser($uid);
    $old_money = $CaoUser->get_balance();
    if (!$CaoUser->update_balance($cdk_money)) {
        echo json_encode(array('status' => '0', 'msg' => '兑换失败'));exit;
    }
    // 充值余额成功 废弃卡密 updataCdk
    if (!$CaoCdk->updataCdk($cdkcode)) {
        echo json_encode(array('status' => '0', 'msg' => '卡密异常'));exit;
    }

    // 添加纪录
    if ($uid) {
        $Caolog    = new Caolog();
        $new_money = $old_money + $cdk_money;
        $note      = '卡密充值 [' . $cdkcode . '] +' . $cdk_money;
        $Caolog->addlog($uid, $old_money, $cdk_money, $new_money, 'cdk', $note);
    }
    
    echo json_encode(array('status' => '1', 'msg' => '卡密充值成功'));
    if (_cao('is_mail_nitfy_cdk')) {
        _sendMail($current_user->user_email, '卡密充值成功', $note);
    }
    exit;
}
add_action('wp_ajax_cdk_pay', 'cdk_pay');
add_action('wp_ajax_nopriv_cdk_pay', 'cdk_pay');


// 提现申请
function add_reflog()
{
    header('Content-type:application/json; Charset=utf-8');
    if (_cao('is_ref_to_rmb')) {
        echo json_encode(array('status' => '0', 'msg' => 'RMB提现功能未开启'));exit;
    }
    global $current_user;
    $uid = $current_user->ID;
    isLoginCheck(); //检测登录
    $money = !empty($_POST['money']) ? (int)$_POST['money'] : 0;
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    if ($nonce && !wp_verify_nonce($nonce, 'caoclick-' . $uid)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));exit;
    }
    $site_min_tixian_num = _cao('site_min_tixian_num');
    $Reflog = new Reflog($uid);
    // 验证长度
    if ($money < $site_min_tixian_num) {
        echo json_encode(array('status' => '0', 'msg' => '提现金额最低'.$site_min_tixian_num.'元起'));exit;
    }

    if ($money > $Reflog->get_ke_bonus()) {
        echo json_encode(array('status' => '0', 'msg' => '可提现金额不足'));exit;
    }
    $note = '用户ID：'.$uid.' 申请提现';
    if ($Reflog->addlog($money,$note)) {
        echo json_encode(array('status' => '1', 'msg' => '提现申请成功，将尽快为您转账'));exit;
    }else{
        echo json_encode(array('status' => '0', 'msg' => '申请失败，稍后再试'));exit;
    }
    
}
add_action('wp_ajax_add_reflog', 'add_reflog');
add_action('wp_ajax_nopriv_add_reflog', 'add_reflog');



// 提现站内余额申请
function add_reflog2()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid = $current_user->ID;
    isLoginCheck(); //检测登录
    $money = !empty($_POST['money']) ? (int)$_POST['money'] : 0;
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    if ($nonce && !wp_verify_nonce($nonce, 'caoclick-' . $uid)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));exit;
    }
    $site_min_tixian_num = _cao('site_min_tixian_num');
    $Reflog = new Reflog($uid);
    // 验证长度
    if ($money < $site_min_tixian_num) {
        echo json_encode(array('status' => '0', 'msg' => '提现金额最低'.$site_min_tixian_num.'元起'));exit;
    }

    if ($money > $Reflog->get_ke_bonus()) {
        echo json_encode(array('status' => '0', 'msg' => '可提现金额不足'));exit;
    }
    $note = '用户ID：'.$uid.' 提现到站内余额';
    if ($Reflog->addlog($money,$note)) {
        // $money 兑换
        $charge_rate  = (int) _cao('site_change_rate'); //充值比例
        $CaoUser   = new CaoUser($uid);
        $old_money = $CaoUser->get_balance();
        $add_money = $money*$charge_rate;
        if (!$CaoUser->update_balance($add_money)) {
            echo json_encode(array('status' => '0', 'msg' => '佣金兑换失败'));exit;
        }
        // 兑换成功 添加纪录
        if ($uid) {
            $Caolog    = new Caolog();
            $new_money = $old_money + $add_money;
            $note      = '佣金提现兑换 [￥' . $money . '] +' . $add_money;
            $Caolog->addlog($uid, $old_money, $add_money, $new_money, 'other', $note);
        }

        echo json_encode(array('status' => '1', 'msg' => '提现成功，已经自动兑换到您的可用余额'));exit;
    }else{
        echo json_encode(array('status' => '0', 'msg' => '申请失败，稍后再试'));exit;
    }
    
}
add_action('wp_ajax_add_reflog2', 'add_reflog2');
add_action('wp_ajax_nopriv_add_reflog2', 'add_reflog2');


/**
 * [charge_pay 在线付款支付]
 * @Author   Dadong2g
 * @DateTime 2019-06-03T22:28:59+0800
 * @return   [type]                   [JOSN]
 */
function charge_pay()
{
    header('Content-type:application/json; Charset=utf-8');
    date_default_timezone_set('Asia/Shanghai');
    $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1'; //客户端IP
    global $current_user;
    $uid = $current_user->ID;
    isLoginCheck(); //检测登录
    $nonce      = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $charge_num = !empty($_POST['charge_num']) ? $_POST['charge_num'] : null;
    $pay_type   = !empty($_POST['pay_type']) ? (int) $_POST['pay_type'] : null; //1支付宝；2微信
    if ($nonce && !wp_verify_nonce($nonce, 'caoclick-' . $uid)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));exit;
    }

    // 基础验证通过 验证前台表单数据 充值数量和支付方式
    $min_cahrge_num =_cao('min_cahrge_num','1');
    if (!$charge_num || $charge_num < 0) {
        echo json_encode(array('status' => '0', 'msg' => '请输入充值数量'));exit;
    }
    if ($charge_num < $min_cahrge_num) {
        echo json_encode(array('status' => '0', 'msg' => '最低充值数量为：'.$min_cahrge_num));exit;
    }
    if (!isset($pay_type) || $pay_type == 0) {
        echo json_encode(array('status' => '0', 'msg' => '请选择支付方式'));exit;
    }

    // 实例化订单
    $ShopOrder = new ShopOrder();

    /////////商品属性START///////
    $charge_rate    = (int) _cao('site_change_rate'); //充值比例
    $order_price    = sprintf('%0.2f', $charge_num / $charge_rate); // 订单价格 换算人民币,保留两位小数点
    $order_trade_no = date("ymdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999); // 订单号
    $order_name     = get_bloginfo('name') . '-余额充值'; //订单名称
    $order_type     = 'charge'; //类型 充值
    /////////商品属性END/////////

    // 判断支付方式 1 支付宝 START
    if ($pay_type == 1) {
        // 获取后台支付宝配置
        $aliPayConfig = _cao('alipay');
        // 判断是否开启手机版跳转
        if (wp_is_mobile() && $aliPayConfig['is_mobile']) {
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }
            // 支付宝公共配置
            $params         = new \Yurun\PaySDK\Alipay\Params\PublicParams;
            $params->appID  = $aliPayConfig['pid'];
            $params->md5Key = $aliPayConfig['md5Key'];
            // SDK实例化，传入公共配置
            $pay       = new \Yurun\PaySDK\Alipay\SDK($params);
            // 支付接口
            $request    = new \Yurun\PaySDK\Alipay\Params\WapPay\Request;
            $request->notify_url    = get_template_directory_uri() . '/shop/alipay/notify.php';
            $request->return_url    = get_template_directory_uri() . '/shop/alipay/return.php'; // 支付后跳转返回地址
            $request->businessParams->seller_id    = $aliPayConfig['pid']; // 卖家支付宝用户号
            $request->businessParams->out_trade_no = $order_trade_no; // 商户订单号
            $request->businessParams->total_fee    = $order_price; // 价格
            $request->businessParams->subject      = $order_name; // 商品标题
            $request->businessParams->show_url     = home_url(); // 用户付款中途退出返回商户网站的地址。

            $payurl = $pay->redirectExecuteUrl($request);
            // type 1 = 扫码支付  2 跳转支付
            echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $payurl, 'qrcode' => '', 'msg' => $order_trade_no));
            exit;
        } elseif (!$aliPayConfig['is_pcqr']) {
            // 支付宝-电脑网站支付
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }
            // 支付宝公共配置
            $params         = new \Yurun\PaySDK\Alipay\Params\PublicParams;
            $params->appID  = $aliPayConfig['pid'];
            $params->md5Key = $aliPayConfig['md5Key'];
            // SDK实例化，传入公共配置
            $pay       = new \Yurun\PaySDK\Alipay\SDK($params);
            // 支付接口
            $request = new \Yurun\PaySDK\Alipay\Params\Pay\Request;
            $request->notify_url    = get_template_directory_uri() . '/shop/alipay/notify.php';
            $request->return_url    = get_template_directory_uri() . '/shop/alipay/return.php'; // 支付后跳转返回地址
            $request->businessParams->seller_id    = $aliPayConfig['pid']; // 卖家支付宝用户号
            $request->businessParams->out_trade_no = $order_trade_no; // 商户订单号
            $request->businessParams->total_fee    = $order_price; // 价格
            $request->businessParams->subject      = $order_name; // 商品标题
            // 跳转到支付宝页面
            $payurl = $pay->redirectExecuteUrl($request);
            // var_dump($payurl);
            // type 1 = 扫码支付  2 跳转支付
            echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $payurl, 'qrcode' => '', 'msg' => $order_trade_no));
            exit;
        } else {
            // 应用模式公共配置-当面付
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }
            // 更换公共配置文件
            $params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
            $params->appID = $aliPayConfig['appid'];
            $params->appPrivateKey = $aliPayConfig['privateKey'];
            $params->appPublicKey = $aliPayConfig['publicKey'];
            // SDK实例化，传入公共配置
            $pay = new \Yurun\PaySDK\AlipayApp\SDK($params);
            // 支付接口
            $request = new \Yurun\PaySDK\AlipayApp\FTF\Params\QR\Request;
            $request->notify_url    = get_template_directory_uri() . '/shop/alipay/notify2.php'; // 支付后通知地址
            $request->businessParams->out_trade_no = $order_trade_no; // 商户订单号
            $request->businessParams->total_amount = $order_price; // 价格
            $request->businessParams->subject      = $order_name; // 商品标题

            // 调用接口
            try{
                $data = $pay->execute($request);
            }
            catch(Exception $e){
                var_dump($pay->response->body());
            }
            // QR内容
            $qrimg = getQrcode($data['alipay_trade_precreate_response']['qr_code']);

            $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/alipay.png" class="qr-pay">';
            $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">支付宝扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$qrimg.'"/> </div> <div class="bottom alipay"> 请使用支付宝扫一扫<br>扫描二维码支付</br> </div> </div>';
            echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $qrimg, 'num' => $order_trade_no));
            exit;
        }
    }
    //END ALIPAY

    // 2 微信
    if ($pay_type == 2) {
        // 获取后台支付配置
        $wxPayConfig = _cao('weixinpay');
        // 公共配置
        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
        $params->appID = $wxPayConfig['appid'];
        $params->mch_id = $wxPayConfig['mch_id'];
        $params->key = $wxPayConfig['key'];
        // SDK实例化，传入公共配置
        $pay = new \Yurun\PaySDK\Weixin\SDK($params);

        // 判断是否开启手机版跳转
        if (wp_is_mobile() && $wxPayConfig['is_mobile']) {
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }
            
            // 支付接口H5
            $request = new \Yurun\PaySDK\Weixin\H5\Params\Pay\Request;
            $request->body = $order_name; // 商品描述
            $request->out_trade_no = $order_trade_no; // 订单号
            $request->total_fee = $order_price*100; // 订单总金额，单位为：分
            $request->spbill_create_ip = $ip; // 客户端ip，必须传正确的用户ip，否则会报错
            $request->notify_url = get_template_directory_uri() . '/shop/weixin/notify.php'; // 异步通知地址
            $request->scene_info = new \Yurun\PaySDK\Weixin\H5\Params\SceneInfo;
            $request->scene_info->type = 'Wap'; // 可选值：IOS、Android、Wap
            // 下面参数根据type不同而不同
            $request->scene_info->wap_url = esc_url(home_url());
            $request->scene_info->wap_name = get_bloginfo('name');
            // 调用接口
            $result = $pay->execute($request);
            if($pay->checkResult()){
                echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $result['mweb_url'], 'qrcode' => '', 'msg' => $order_trade_no));
                exit;
            }else{
                $error_msg = $pay->getErrorCode() . ':' . $pay->getError();
                echo json_encode(array('status' => '0', 'msg' => $error_msg));
                exit;
            }

        } else {
            // PC使用当面付返回二维码
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }
            // 支付接口 PC扫码
            $request = new \Yurun\PaySDK\Weixin\Native\Params\Pay\Request;
            $request->body = $order_name; // 商品描述
            $request->out_trade_no = $order_trade_no; // 订单号
            $request->total_fee = $order_price*100; // 订单总金额，单位为：分
            $request->spbill_create_ip = $ip; // 客户端ip
            $request->notify_url = get_template_directory_uri() . '/shop/weixin/notify.php'; // 异步通知地址
            // 调用接口
            $result = $pay->execute($request);
            $shortUrl = $result['code_url'];
            if (is_array($result) && $shortUrl) {
                // 获取成功 返回QR内容
                $qrimg = getQrcode($shortUrl);
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/weixin.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">微信扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$qrimg.'"/> </div> <div class="bottom weixinpay"> 请使用微信扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $qrimg, 'num' => $order_trade_no));
                exit;
            }else{
                echo json_encode(array('status' => '0', 'msg' => '接口网络异常'));exit;
            }
        }
    }

    //PAYJS 
    if ($pay_type == 4) {
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
         // 添加订单 ShopOrder
        if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
            echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
        }
        if (false) {
            // 手机模式因openid获取问题 暂时未开放
        }else{
            // 构造订单基础信息
            $data = [
                'body' => $order_name,                        // 订单标题
                'total_fee' => $order_price*100,                           // 订单金额
                'out_trade_no' => $order_trade_no,                   // 订单号
                'attach' => 'payjs_order_attach',            // 订单附加信息(可选参数)
                'notify_url' => get_template_directory_uri() . '/shop/payjs/notify.php',    // 异步通知地址(可选参数)
            ];
            $result = $payjs->native($data);
            // var_dump($result);die;
            if (is_array($result) && $result['return_code'] == 1 ) {
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/weixin.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">微信扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$result['qrcode'].'"/> </div> <div class="bottom weixinpay"> 请使用微信扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $result['qrcode'], 'num' => $order_trade_no));
                exit;
            }else{
                echo json_encode(array('status' => '0', 'msg' => 'PAYJS接口异常'));exit;
            }
        }
        
        echo json_encode(array('status' => '0', 'msg' => '请配置payjs参数'));exit;
    }

    //虎皮椒支付 讯虎支付 V3 微信
    if ($pay_type == 5) {
        require_once get_template_directory() . '/inc/class/xunhupay.class.php';
        // 获取后台支付配置
        $XHpayConfig = _cao('xunhupay');
        
         // 添加订单 ShopOrder
        if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
            echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
        }

        $data=array(
            'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
            'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
            'plugins'   => 'ripro-xunhupay-v3',//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
            'appid'     => $XHpayConfig['appid'], //必须的，APPID
            'trade_order_id'=> $order_trade_no, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
            'payment'   => 'wechat',//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
            'total_fee' => $order_price,//人民币，单位精确到分(测试账户只支持0.1元内付款)
            'title'     => $order_name, //必须的，订单标题，长度32或以内
            'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
            'notify_url'=>  get_template_directory_uri() . '/shop/xunhupay/notify.php', //必须的，支付成功异步回调接口
            'return_url'=> get_template_directory_uri() . '/shop/xunhupay/return.php',//必须的，支付成功后的跳转地址
            'callback_url'=> esc_url(home_url('/user?action=charge')),//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
            'modal'=>null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
            'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
        );

        $hashkey =$XHpayConfig['appsecret'];
        $data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);
        $url              = $XHpayConfig['url_do'];

        try {
            $response     = XH_Payment_Api::http_post($url, json_encode($data));
            /**
             * 支付回调数据
             * @var array(
             *      order_id,//支付系统订单ID
             *      url//支付跳转地址
             *  )
             */
            $result       = $response?json_decode($response,true):null;
            if(!$result){
                throw new Exception('Internal server error',500);
            }

            $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
            if(!isset( $result['hash'])|| $hash!=$result['hash']){
                throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
            }

            if($result['errcode']!=0){
                throw new Exception($result['errmsg'],$result['errcode']);
            }
            $pay_url =$result['url'];  
            if ($XHpayConfig['is_pop_qrcode']) {
                //获取js地址
                $RiProPay = new RiProPay;
                $pay_js_url = $RiProPay->_cao_get_xunhupay_js_url($pay_url);
                //获取二维码地址
                $pay_qrcode_url = $RiProPay->_cao_get_xunhupay_qrcode($pay_js_url);
                
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/weixin.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">微信扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$pay_qrcode_url.'"/> </div> <div class="bottom weixinpay"> 请使用微信扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $pay_qrcode_url, 'num' => $order_trade_no));
                exit;
            }else{
                echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $pay_url, 'qrcode' => '', 'msg' => $order_trade_no));exit;
            }
            
        } catch (Exception $e) {
            echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";exit;
            //TODO:处理支付调用异常的情况
        }
        exit;
    }

    //虎皮椒支付 讯虎支付 V3 支付宝
    if ($pay_type == 6) {
        require_once get_template_directory() . '/inc/class/xunhupay.class.php';
        // 获取后台支付配置
        $XHpayConfig = _cao('xunhualipay');
         // 添加订单 ShopOrder
        if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
            echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
        }
        $data=array(
            'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
            'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
            'plugins'   => 'ripro-xunhupay-v3',//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
            'appid'     => $XHpayConfig['appid'], //必须的，APPID
            'trade_order_id'=> $order_trade_no, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
            'payment'   => 'alipay',//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
            'total_fee' => $order_price,//人民币，单位精确到分(测试账户只支持0.1元内付款)
            'title'     => $order_name, //必须的，订单标题，长度32或以内
            'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
            'notify_url'=>  get_template_directory_uri() . '/shop/xunhupay/notify2.php', //必须的，支付成功异步回调接口
            'return_url'=> get_template_directory_uri() . '/shop/xunhupay/return.php',//必须的，支付成功后的跳转地址
            'callback_url'=> esc_url(home_url('/user?action=charge')),//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
            'modal'=>null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
            'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
        );

        $hashkey =$XHpayConfig['appsecret'];
        $data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);
        $url              = $XHpayConfig['url_do'];

        try {
            $response     = XH_Payment_Api::http_post($url, json_encode($data));
            /**
             * 支付回调数据
             * @var array(
             *      order_id,//支付系统订单ID
             *      url//支付跳转地址
             *  )
             */
            $result       = $response?json_decode($response,true):null;
            if(!$result){
                throw new Exception('Internal server error',500);
            }

            $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
            if(!isset( $result['hash'])|| $hash!=$result['hash']){
                throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
            }

            if($result['errcode']!=0){
                throw new Exception($result['errmsg'],$result['errcode']);
            }
            $pay_url =$result['url'];  
            
            if ($XHpayConfig['is_pop_qrcode']) {
                //获取二维码地址
                $RiProPay = new RiProPay;
                $pay_qrcode_url = $RiProPay->_cao_get_xunhupay_qrcode($pay_url);

                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/alipay.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">支付宝扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$pay_qrcode_url.'"/> </div> <div class="bottom alipay"> 请使用支付宝扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $pay_qrcode_url, 'num' => $order_trade_no));
                exit;
            }else{
                echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $pay_url, 'qrcode' => '', 'msg' => $order_trade_no));exit;
            }
            
            


        } catch (Exception $e) {
            echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";exit;
            //TODO:处理支付调用异常的情况
        }
        exit;
    }

    //码支付 codepay 微信 7 8支付宝
    if ($pay_type == 7 || $pay_type == 8) {
        // 获取后台支付配置
        $codepayConfig = _cao('codepay');
         // 添加订单 ShopOrder
        if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
            echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
        }

        //判断吗支付支付方式
        switch ($pay_type) {
            case '7':
                $paymethod = 1; // 支付宝
                break;
            case '8':
                $paymethod = 3; // 微信
                break;
        }
        $params = array(
            "id" => $codepayConfig['mzf_appid'],
            "token" => $codepayConfig['mzf_token'],
            "pay_id" => $order_trade_no, //唯一标识
            "type" => $paymethod,//1支付宝支付 3微信支付 2QQ钱包
            "price" => $order_price,//金额
            "param" => "rimini",//自定义参数
            "notify_url"=>get_template_directory_uri() . '/shop/codepay/notify.php',//通知地址
        ); //构造需要传递的参数

        // 请求支付数据
        $query = 'id='.$params['id'].'&token='.$params['token'].'&price='.$params['price'].'&pay_id='.$params['pay_id'].'&type='.$params['type'].'&notify_url='.$params['notify_url'].'&page=4'; //创建订单所需的参数
        $urls = 'https://codepay.fateqq.com/creat_order/creat_order?'.trim($query); //支付页面
        $result = get_url_contents($urls);
        $resultData = json_decode($result,true);

        if ($resultData && $resultData['status'] == 0) {
            if ($paymethod == 3) {
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/weixin.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">微信扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$resultData['qrcode'].'"/> </div> <div class="bottom weixinpay"> 请使用微信扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $resultData['qrcode'], 'num' => $order_trade_no));
                exit;
            }else{
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/alipay.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">支付宝扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$resultData['qrcode'].'"/> </div> <div class="bottom alipay"> 请使用支付宝扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str , 'img' => $resultData['qrcode'], 'num' => $order_trade_no));
                exit;
            }
            
        }else{
            echo json_encode(array('status' => '0', 'msg' => $resultData['msg']));exit;
        }

    }


}
add_action('wp_ajax_charge_pay', 'charge_pay');
add_action('wp_ajax_nopriv_charge_pay', 'charge_pay');

/**
 * [go_post_pay 支付模式购买文章]
 * @Author   Dadong2g
 * @DateTime 2020-01-15T11:41:26+0800
 * @return   [type]                   [description]
 */
function go_post_pay()
{
    header('Content-type:application/json; Charset=utf-8');
    date_default_timezone_set('Asia/Shanghai');
    $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1'; //客户端IP
    global $current_user;
    $uid = (is_user_logged_in()) ? $current_user->ID : 0 ;

    if ($uid>0 && !_cao('is_online_pay_status',true)) {
        echo json_encode(array('status' => '0', 'msg' => '登录用户仅限请使用余额支付'));exit;
    }

    // isLoginCheck(); //检测登录
    $nonce      = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    $post_id = !empty($_POST['post_id']) ? $_POST['post_id'] : 0;

    // 1支付宝官方；2微信官方 ；3 其他  ；4 PAYJS  ；5 讯虎微信  ；6 讯虎支付宝 ；7 吗支付支付宝  ；8 吗支付微信
    $pay_type   = !empty($_POST['pay_type']) ? (int) $_POST['pay_type'] : null; 

    if ($nonce && !wp_verify_nonce($nonce, 'caopay-' . $post_id)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));exit;
    }

    if ($post_id <= 0) {
        echo json_encode(array('status' => '0', 'msg' => '购买ID参数错误'));exit;
    }
    


    // 实例化订单
    $ShopOrder = new ShopOrder();
    $CaoUser = new CaoUser($uid);
    $PostPay = new PostPay($uid, $post_id);
    
    /////////商品属性START///////
    $charge_rate    = (int) _cao('site_change_rate'); //网站比例
    //获取资源文章价格等信息
    $post_price   = get_post_meta($post_id, 'cao_price', true);
    $post_price = ($post_price) ? $post_price : '0' ;
    // 计算价格 验证会员折扣权限
    $post_vip_rate = get_post_meta($post_id, 'cao_vip_rate', true);
    $cao_is_boosvip  = get_post_meta($post_id, 'cao_is_boosvip', true);
    if ($cao_is_boosvip && is_boosvip_status($uid)) {
        $post_price    = 0;
    }
    if (_cao('is_online_pay_reta',true)) {
        $vip_status    = $CaoUser->vip_status();
        $order_vip_rate = ($vip_status) ? $post_vip_rate : 1 ;
        // 折扣信息
        if ($order_vip_rate == 0) {
            $post_price = 0;
        } elseif ($order_vip_rate == 1) {
            $post_price = $post_price;
        } elseif ($order_vip_rate > 0 && $order_vip_rate < 1) {
            $post_price = sprintf('%0.2f', $post_price*$order_vip_rate );
        }else{
            $post_price = $post_price;
        }
    }else{
        $order_vip_rate =  1 ;
        $post_price = $post_price;
    }
    

    $order_price    = sprintf('%0.2f', $post_price / $charge_rate); // 订单价格 换算人民币,保留两位小数点
    $order_trade_no = date("ymdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999); // 订单号
    $order_name     = get_bloginfo('name') . '-资源购买'; //订单名称
    $order_type     = 'other'; //类型 购买 其他

    if ($post_price <= 0) {
        echo json_encode(array('status' => '0', 'msg' => '免费或'._cao('site_vip_name').'免费资源仅限余额支付'));exit;
    }

    //写入本地文章购买记录
    if (!$PostPay->add($post_price, $order_vip_rate,$order_trade_no,$pay_type)) {
        echo json_encode(array('status' => '0', 'msg' => '添加订单异常'));exit;
    }

    /////////商品属性END/////////

    // 判断支付方式 1 支付宝 START
    if ($pay_type == 1) {
        // 获取后台支付宝配置
        $aliPayConfig = _cao('alipay');
        // 判断是否开启手机版跳转
        if (wp_is_mobile() && $aliPayConfig['is_mobile']) {
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }


            // 支付宝公共配置
            $params         = new \Yurun\PaySDK\Alipay\Params\PublicParams;
            $params->appID  = $aliPayConfig['pid'];
            $params->md5Key = $aliPayConfig['md5Key'];
            // SDK实例化，传入公共配置
            $pay       = new \Yurun\PaySDK\Alipay\SDK($params);
            // 支付接口
            $request    = new \Yurun\PaySDK\Alipay\Params\WapPay\Request;
            $request->notify_url    = get_template_directory_uri() . '/shop/alipay/notify.php';
            $request->return_url    = get_template_directory_uri() . '/shop/alipay/return.php'; // 支付后跳转返回地址
            $request->businessParams->seller_id    = $aliPayConfig['pid']; // 卖家支付宝用户号
            $request->businessParams->out_trade_no = $order_trade_no; // 商户订单号
            $request->businessParams->total_fee    = $order_price; // 价格
            $request->businessParams->subject      = $order_name; // 商品标题
            $request->businessParams->show_url     = home_url(); // 用户付款中途退出返回商户网站的地址。

            $payurl = $pay->redirectExecuteUrl($request);
            // type 1 = 扫码支付  2 跳转支付
            echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $payurl, 'qrcode' => '', 'msg' => $order_trade_no));
            exit;
        } elseif (!$aliPayConfig['is_pcqr']) {
            // 支付宝-电脑网站支付
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }
            // 支付宝公共配置
            $params         = new \Yurun\PaySDK\Alipay\Params\PublicParams;
            $params->appID  = $aliPayConfig['pid'];
            $params->md5Key = $aliPayConfig['md5Key'];
            // SDK实例化，传入公共配置
            $pay       = new \Yurun\PaySDK\Alipay\SDK($params);
            // 支付接口
            $request = new \Yurun\PaySDK\Alipay\Params\Pay\Request;
            $request->notify_url    = get_template_directory_uri() . '/shop/alipay/notify.php';
            $request->return_url    = get_template_directory_uri() . '/shop/alipay/return.php'; // 支付后跳转返回地址
            $request->businessParams->seller_id    = $aliPayConfig['pid']; // 卖家支付宝用户号
            $request->businessParams->out_trade_no = $order_trade_no; // 商户订单号
            $request->businessParams->total_fee    = $order_price; // 价格
            $request->businessParams->subject      = $order_name; // 商品标题
            // 跳转到支付宝页面
            $payurl = $pay->redirectExecuteUrl($request);
            // var_dump($payurl);
            // type 1 = 扫码支付  2 跳转支付
            echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $payurl, 'qrcode' => '', 'msg' => $order_trade_no));
            exit;
        } else {
            // 应用模式公共配置-当面付
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }
            // 更换公共配置文件
            $params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
            $params->appID = $aliPayConfig['appid'];
            $params->appPrivateKey = $aliPayConfig['privateKey'];
            $params->appPublicKey = $aliPayConfig['publicKey'];
            // SDK实例化，传入公共配置
            $pay = new \Yurun\PaySDK\AlipayApp\SDK($params);
            // 支付接口
            $request = new \Yurun\PaySDK\AlipayApp\FTF\Params\QR\Request;
            $request->notify_url    = get_template_directory_uri() . '/shop/alipay/notify2.php'; // 支付后通知地址
            $request->businessParams->out_trade_no = $order_trade_no; // 商户订单号
            $request->businessParams->total_amount = $order_price; // 价格
            $request->businessParams->subject      = $order_name; // 商品标题

            // 调用接口
            try{
                $data = $pay->execute($request);
            }
            catch(Exception $e){
                var_dump($pay->response->body());
            }
            // QR内容
            $qrimg = getQrcode($data['alipay_trade_precreate_response']['qr_code']);

            $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/alipay.png" class="qr-pay">';
            $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">支付宝扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$qrimg.'"/> </div> <div class="bottom alipay"> 请使用支付宝扫一扫<br>扫描二维码支付</br> </div> </div>';
            echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $qrimg, 'num' => $order_trade_no));
            exit;
        }
    }
    //END ALIPAY

    // 2 微信
    if ($pay_type == 2) {
        // 获取后台支付配置
        $wxPayConfig = _cao('weixinpay');
        // 公共配置
        $params = new \Yurun\PaySDK\Weixin\Params\PublicParams;
        $params->appID = $wxPayConfig['appid'];
        $params->mch_id = $wxPayConfig['mch_id'];
        $params->key = $wxPayConfig['key'];
        // SDK实例化，传入公共配置
        $pay = new \Yurun\PaySDK\Weixin\SDK($params);

        // 判断是否开启手机版跳转
        if (wp_is_mobile() && $wxPayConfig['is_mobile']) {
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }
            
            // 支付接口H5
            $request = new \Yurun\PaySDK\Weixin\H5\Params\Pay\Request;
            $request->body = $order_name; // 商品描述
            $request->out_trade_no = $order_trade_no; // 订单号
            $request->total_fee = $order_price*100; // 订单总金额，单位为：分
            $request->spbill_create_ip = $ip; // 客户端ip，必须传正确的用户ip，否则会报错
            $request->notify_url = get_template_directory_uri() . '/shop/weixin/notify.php'; // 异步通知地址
            $request->scene_info = new \Yurun\PaySDK\Weixin\H5\Params\SceneInfo;
            $request->scene_info->type = 'Wap'; // 可选值：IOS、Android、Wap
            // 下面参数根据type不同而不同
            $request->scene_info->wap_url = esc_url(home_url());
            $request->scene_info->wap_name = get_bloginfo('name');
            // 调用接口
            $result = $pay->execute($request);
            if($pay->checkResult()){
                echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $result['mweb_url'], 'qrcode' => '', 'msg' => $order_trade_no));
                exit;
            }else{
                $error_msg = $pay->getErrorCode() . ':' . $pay->getError();
                echo json_encode(array('status' => '0', 'msg' => $error_msg));
                exit;
            }

        } else {
            // PC使用当面付返回二维码
            // 添加订单 ShopOrder
            if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
                echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
            }
            // 支付接口 PC扫码
            $request = new \Yurun\PaySDK\Weixin\Native\Params\Pay\Request;
            $request->body = $order_name; // 商品描述
            $request->out_trade_no = $order_trade_no; // 订单号
            $request->total_fee = $order_price*100; // 订单总金额，单位为：分
            $request->spbill_create_ip = $ip; // 客户端ip
            $request->notify_url = get_template_directory_uri() . '/shop/weixin/notify.php'; // 异步通知地址
            // 调用接口
            $result = $pay->execute($request);
            $shortUrl = $result['code_url'];
            if (is_array($result) && $shortUrl) {
                // 获取成功 返回QR内容
                $qrimg = getQrcode($shortUrl);
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/weixin.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">微信扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$qrimg.'"/> </div> <div class="bottom weixinpay"> 请使用微信扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $qrimg, 'num' => $order_trade_no));
                exit;
            }else{
                echo json_encode(array('status' => '0', 'msg' => '接口网络异常'));exit;
            }
        }
    }

    //PAYJS 
    if ($pay_type == 4) {
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
         // 添加订单 ShopOrder
        if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
            echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
        }
        if (false) {
            // 手机模式因openid获取问题 暂时未开放
        }else{
            // 构造订单基础信息
            $data = [
                'body' => $order_name,                        // 订单标题
                'total_fee' => $order_price*100,                           // 订单金额
                'out_trade_no' => $order_trade_no,                   // 订单号
                'attach' => 'payjs_order_attach',            // 订单附加信息(可选参数)
                'notify_url' => get_template_directory_uri() . '/shop/payjs/notify.php',    // 异步通知地址(可选参数)
            ];
            $result = $payjs->native($data);
            // var_dump($result);die;
            if (is_array($result) && $result['return_code'] == 1 ) {
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/weixin.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">微信扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$result['qrcode'].'"/> </div> <div class="bottom weixinpay"> 请使用微信扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $result['qrcode'], 'num' => $order_trade_no));
                exit;
            }else{
                echo json_encode(array('status' => '0', 'msg' => 'PAYJS接口异常'));exit;
            }
        }
        
        echo json_encode(array('status' => '0', 'msg' => '请配置payjs参数'));exit;
    }

    //虎皮椒支付 讯虎支付 V3 微信
    if ($pay_type == 5) {
        require_once get_template_directory() . '/inc/class/xunhupay.class.php';
        // 获取后台支付配置
        $XHpayConfig = _cao('xunhupay');
        
         // 添加订单 ShopOrder
        if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
            echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
        }

        $data=array(
            'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
            'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
            'plugins'   => 'ripro-xunhupay-v3',//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
            'appid'     => $XHpayConfig['appid'], //必须的，APPID
            'trade_order_id'=> $order_trade_no, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
            'payment'   => 'wechat',//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
            'total_fee' => $order_price,//人民币，单位精确到分(测试账户只支持0.1元内付款)
            'title'     => $order_name, //必须的，订单标题，长度32或以内
            'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
            'notify_url'=>  get_template_directory_uri() . '/shop/xunhupay/notify.php', //必须的，支付成功异步回调接口
            'return_url'=> get_template_directory_uri() . '/shop/xunhupay/return.php',//必须的，支付成功后的跳转地址
            'callback_url'=> esc_url(home_url('/user?action=charge')),//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
            'modal'=>null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
            'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
        );

        $hashkey =$XHpayConfig['appsecret'];
        $data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);
        $url              = $XHpayConfig['url_do'];

        try {
            $response     = XH_Payment_Api::http_post($url, json_encode($data));
            /**
             * 支付回调数据
             * @var array(
             *      order_id,//支付系统订单ID
             *      url//支付跳转地址
             *  )
             */
            $result       = $response?json_decode($response,true):null;
            if(!$result){
                throw new Exception('Internal server error',500);
            }

            $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
            if(!isset( $result['hash'])|| $hash!=$result['hash']){
                throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
            }

            if($result['errcode']!=0){
                throw new Exception($result['errmsg'],$result['errcode']);
            }
            $pay_url =$result['url'];  
            if ($XHpayConfig['is_pop_qrcode']) {
                //获取js地址
                $RiProPay = new RiProPay;
                $pay_js_url = $RiProPay->_cao_get_xunhupay_js_url($pay_url);
                //获取二维码地址
                $pay_qrcode_url = $RiProPay->_cao_get_xunhupay_qrcode($pay_js_url);
                
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/weixin.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">微信扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$pay_qrcode_url.'"/> </div> <div class="bottom weixinpay"> 请使用微信扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $pay_qrcode_url, 'num' => $order_trade_no));
                exit;
            }else{
                echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $pay_url, 'qrcode' => '', 'msg' => $order_trade_no));exit;
            }
            
        } catch (Exception $e) {
            echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";exit;
            //TODO:处理支付调用异常的情况
        }
        exit;
    }

    //虎皮椒支付 讯虎支付 V3 支付宝
    if ($pay_type == 6) {
        require_once get_template_directory() . '/inc/class/xunhupay.class.php';
        // 获取后台支付配置
        $XHpayConfig = _cao('xunhualipay');
         // 添加订单 ShopOrder
        if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
            echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
        }
        $data=array(
            'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
            'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
            'plugins'   => 'ripro-xunhupay-v3',//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
            'appid'     => $XHpayConfig['appid'], //必须的，APPID
            'trade_order_id'=> $order_trade_no, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
            'payment'   => 'alipay',//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
            'total_fee' => $order_price,//人民币，单位精确到分(测试账户只支持0.1元内付款)
            'title'     => $order_name, //必须的，订单标题，长度32或以内
            'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
            'notify_url'=>  get_template_directory_uri() . '/shop/xunhupay/notify2.php', //必须的，支付成功异步回调接口
            'return_url'=> get_template_directory_uri() . '/shop/xunhupay/return.php',//必须的，支付成功后的跳转地址
            'callback_url'=> esc_url(home_url('/user?action=charge')),//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
            'modal'=>null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
            'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
        );

        $hashkey =$XHpayConfig['appsecret'];
        $data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);
        $url              = $XHpayConfig['url_do'];

        try {
            $response     = XH_Payment_Api::http_post($url, json_encode($data));
            /**
             * 支付回调数据
             * @var array(
             *      order_id,//支付系统订单ID
             *      url//支付跳转地址
             *  )
             */
            $result       = $response?json_decode($response,true):null;
            if(!$result){
                throw new Exception('Internal server error',500);
            }

            $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
            if(!isset( $result['hash'])|| $hash!=$result['hash']){
                throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
            }

            if($result['errcode']!=0){
                throw new Exception($result['errmsg'],$result['errcode']);
            }
            $pay_url =$result['url'];  
            
            if ($XHpayConfig['is_pop_qrcode']) {
                //获取二维码地址
                $RiProPay = new RiProPay;
                $pay_qrcode_url = $RiProPay->_cao_get_xunhupay_qrcode($pay_url);
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/alipay.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">支付宝扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$pay_qrcode_url.'"/> </div> <div class="bottom alipay"> 请使用支付宝扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $pay_qrcode_url, 'num' => $order_trade_no));
                exit;
            }else{
                echo json_encode(array('status' => '1', 'type' => '2', 'rurl' => $pay_url, 'qrcode' => '', 'msg' => $order_trade_no));exit;
            }
            
            


        } catch (Exception $e) {
            echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";exit;
            //TODO:处理支付调用异常的情况
        }
        exit;
    }

    //码支付 codepay 微信 7 8支付宝
    if ($pay_type == 7 || $pay_type == 8) {
        // 获取后台支付配置
        $codepayConfig = _cao('codepay');
         // 添加订单 ShopOrder
        if (!$ShopOrder->add($uid, $order_trade_no, $order_type, $order_price, $pay_type)) {
            echo json_encode(array('status' => '0', 'msg' => '订单创建失败'));exit;
        }

        //判断吗支付支付方式
        switch ($pay_type) {
            case '7':
                $paymethod = 1; // 支付宝
                break;
            case '8':
                $paymethod = 3; // 微信
                break;
        }
        $params = array(
            "id" => $codepayConfig['mzf_appid'],
            "token" => $codepayConfig['mzf_token'],
            "pay_id" => $order_trade_no, //唯一标识
            "type" => $paymethod,//1支付宝支付 3微信支付 2QQ钱包
            "price" => $order_price,//金额
            "param" => "rimini",//自定义参数
            "notify_url"=>get_template_directory_uri() . '/shop/codepay/notify.php',//通知地址
        ); //构造需要传递的参数

        // 请求支付数据
        $query = 'id='.$params['id'].'&token='.$params['token'].'&price='.$params['price'].'&pay_id='.$params['pay_id'].'&type='.$params['type'].'&notify_url='.$params['notify_url'].'&page=4'; //创建订单所需的参数
        $urls = 'https://codepay.fateqq.com/creat_order/creat_order?'.trim($query); //支付页面
        $result = get_url_contents($urls);
        $resultData = json_decode($result,true);

        if ($resultData && $resultData['status'] == 0) {
            if ($paymethod == 3) {
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/weixin.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">微信扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$resultData['qrcode'].'"/> </div> <div class="bottom weixinpay"> 请使用微信扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str, 'img' => $resultData['qrcode'], 'num' => $order_trade_no));
                exit;
            }else{
                $iconstr = '<img src="'.get_template_directory_uri() . '/assets/icons/alipay.png" class="qr-pay">';
                $html_str = '<div class="qrcon"> <h5> '.$iconstr.' </h5> <div class="title">支付宝扫码支付 '.$order_price.' 元</div> <div align="center" class="qrcode"> <img src="'.$resultData['qrcode'].'"/> </div> <div class="bottom alipay"> 请使用支付宝扫一扫<br>扫描二维码支付</br> </div> </div>';
                echo json_encode(array('status' => '1', 'type' => '1', 'msg' => $html_str , 'img' => $resultData['qrcode'], 'num' => $order_trade_no));
                exit;
            }
            
        }else{
            echo json_encode(array('status' => '0', 'msg' => $resultData['msg']));exit;
        }

    }


}
add_action('wp_ajax_go_post_pay', 'go_post_pay');
add_action('wp_ajax_nopriv_go_post_pay', 'go_post_pay');



// 检测支付状态
function check_pay()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid = is_user_logged_in() ? $current_user->ID : 0;
    $post_id = !empty($_POST['post_id']) ? $_POST['post_id'] : 0;
    $orderNum = !empty($_POST['num']) ? $_POST['num'] : null;
    $ShopOrder = new ShopOrder();
    $status = $ShopOrder->check($orderNum);
    if ($status) {
        $intstatus = 1;
        $msg = '恭喜你，支付成功';
        $RiProPay = new RiProPay;
        $RiProPay->AddPayPostCookie($uid,$post_id,$orderNum);
    }else{
        $intstatus = 0;
        $msg = '支付中';
    }
    $result = array(
        'status' => $intstatus,
        'msg' => $msg
    );
    echo json_encode($result);
    exit;
}
add_action('wp_ajax_check_pay', 'check_pay');
add_action('wp_ajax_nopriv_check_pay', 'check_pay');


/**
 * [add_pay_post 购买文章资源]
 * @Author   Dadong2g
 * @DateTime 2019-06-02T15:33:41+0800
 */
function add_pay_post()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    isLoginCheck(); //检测登录
    $uid     = $current_user->ID;
    $post_id = !empty($_POST['post_id']) ? (int) $_POST['post_id'] : null;
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    // $create_nonce= wp_create_nonce('caopay-'.$uid);
    if ($nonce && !wp_verify_nonce($nonce, 'caopay-' . $post_id)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));
        exit;
    }

    if (!$post_id > 0) {
        echo json_encode(array('status' => '0', 'msg' => '资源错误'));
        exit;
    }
    // 验证通过 开始处理消费逻辑
    $PostPay = new PostPay($uid, $post_id);
    $CaoUser = new CaoUser($uid);
    // 检测用户是否已经购买过 防止重复扣费
    if ($PostPay->isPayPost()) {
        echo json_encode(array('status' => '0', 'msg' => '您已经购买过'));
        exit;
    }
    // 计算价格 验证会员折扣权限
    $post_price    = get_post_meta($post_id, 'cao_price', true);
    $post_vip_rate = get_post_meta($post_id, 'cao_vip_rate', true);
    $cao_is_boosvip  = get_post_meta($post_id, 'cao_is_boosvip', true);
    if ($cao_is_boosvip && is_boosvip_status($uid)) {
        $post_price    = 0;
    }
    $vip_status    = $CaoUser->vip_status();
    if ($vip_status) {
        $order_vip_rate = $post_vip_rate;
    } else {
        $order_vip_rate = 1;
    }
    // 发起订单请求
    $order_trade_no = date("ymdhis") . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999); // 订单号
    $payInfo = $PostPay->add($post_price, $order_vip_rate,$order_trade_no,9999);
    if (!$payInfo || !is_array($payInfo)) {
        echo json_encode(array('status' => '0', 'msg' => '添加订单失败'));
        exit;
    }
    // 订单添加成功 开始扣费逻辑
    $amount    = $payInfo['order_amount'] * -1;
    $old_money = $CaoUser->get_balance();
    if (!$CaoUser->update_balance($amount)) {
        echo json_encode(array('status' => '0', 'msg' => '可用余额不足，<b><a href="'.esc_url(home_url('/user?action=charge')).'">去充值</a></b>'));
        exit;
    }
    // 添加纪录
    if ($uid) {
        $Caolog    = new Caolog();
        $new_money = $old_money + $amount;
        $note      = '站内货币购买资源 ' . $amount;
        $Caolog->addlog($uid, $old_money, $amount, $new_money, 'post', $note);
    }

    // 扣费成功 更具上面返回的订单号更新订单状态
    if (!$PostPay->update($payInfo['order_trade_no'])) {
        echo json_encode(array('status' => '0', 'msg' => '订单状态异常，请联系管理员'));
        exit;
    }
    // 更新完成 更新资源销售数量 输出成功信息
    $before_paynum = get_post_meta($post_id, 'cao_paynum', true);
    update_post_meta($post_id, 'cao_paynum', (int) $before_paynum + 1);
    // 发放佣金
    $author_id = (int)get_post($post_id)->post_author;
    if ($author_id != $uid) {
        //自己购买自己不发放
        add_post_author_bonus($author_id,$payInfo['order_amount']);
    }
    echo json_encode(array('status' => '1', 'msg' => '购买成功，扣除：' . $payInfo['order_amount'] . _cao('site_money_ua')));
    if (_cao('is_mail_nitfy_pay')) {
        _sendMail($current_user->user_email, '资源购买成功', '成功购买资源，扣除：' . $payInfo['order_amount'] . _cao('site_money_ua'));
    }
    exit;
}
add_action('wp_ajax_add_pay_post', 'add_pay_post');
add_action('wp_ajax_nopriv_add_pay_post', 'add_pay_post');



function pay_vip()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    isLoginCheck(); //检测登录
    $uid     = $current_user->ID;
    $pay_id = !empty($_POST['pay_id']) ? (int) $_POST['pay_id'] : null;
    $nonce   = !empty($_POST['nonce']) ? $_POST['nonce'] : null;
    if ($nonce && !wp_verify_nonce($nonce, 'caoclick-'.$uid)) {
        echo json_encode(array('status' => '0', 'msg' => '非法请求'));
        exit;
    }

    // 验证通过 开始处理消费逻辑
    $PostPay = new PostPay($uid, $post_id);
    $CaoUser = new CaoUser($uid);

    // 获取后台价格设置
    $vip_pay_setting = _cao('vip-pay-setting');
    $payInfo = [];
    foreach ($vip_pay_setting as $key => $item) {
        if ($key == $pay_id) {
            $payInfo = $item;
            break; // 当 $value为c时，终止循环
        }
        
    }
    if (empty($payInfo)) {
        echo json_encode(array('status' => '0', 'msg' => '购买信息错误'));
        exit;
    }

    // 计算价格 验证会员折扣权限
    $pay_price = $payInfo['price'] * -1;
    $pay_daynum = $payInfo['daynum'];
    
    if (is_boosvip_status($uid)) {
        echo json_encode(array('status' => '0', 'msg' => '您已经是终身永久，无需重复开通'));
        exit;
    }

    // 订单计算成功 开始扣费逻辑
    $amount    = $payInfo['price'] * -1;
    $old_money = $CaoUser->get_balance();
    if (!$CaoUser->update_balance($amount)) {
        echo json_encode(array('status' => '0', 'msg' => '可用余额不足'));
        exit;
    }
    // 添加纪录
    if ($uid) {
        $Caolog    = new Caolog();
        $new_money = $old_money + $amount;
        $note      = '购买'._cao('site_vip_name') .' '. $amount;
        $Caolog->addlog($uid, $old_money, $amount, $new_money, 'other', $note);
    }

    // 扣费成功 更新会员数据
    if (!$CaoUser->update_vip_pay($pay_daynum)) {
        echo json_encode(array('status' => '0', 'msg' => '购买失败，请联系网站管理员'));
        exit;
    }
    if ($pay_daynum == 9999) {
        $success_msg = '成功开通：终身特权！ 扣除：' . $payInfo['price'] . _cao('site_money_ua');
    }else{
        $success_msg = '成功开通：'.$pay_daynum.'天特权！ 扣除：' . $payInfo['price'] . _cao('site_money_ua');
    }
    
    echo json_encode(array('status' => '1', 'msg' => $success_msg));
    if (_cao('is_mail_nitfy_vip')) {
        _sendMail($current_user->user_email, '特权开通成功', $success_msg);
    }
    exit;
}
add_action('wp_ajax_pay_vip', 'pay_vip');
add_action('wp_ajax_nopriv_pay_vip', 'pay_vip');



/**
 * [userinfo AJAX保存用户基本信息]
 * @Author   Dadong2g
 * @DateTime 2019-05-31T13:12:33+0800
 * @return   [type]                   [description]
 */
function edit_user_info()
{
    session_start();
    global $current_user;
    isLoginCheck(); //检测登录
    $uid         = $current_user->ID;
    $nickname    = !empty($_POST['nickname']) ? wp_strip_all_tags($_POST['nickname']) : null;
    $email       = !empty($_POST['email']) ? $_POST['email'] : null;
    $avatar_type = !empty($_POST['user_avatar_type']) ? $_POST['user_avatar_type'] : 'gravatar';
    $phone       = !empty($_POST['phone']) ? $_POST['phone'] : null;
    $qq          = !empty($_POST['qq']) ? $_POST['qq'] : null;
    $description = !empty($_POST['description']) ? $_POST['description'] : null;

    $userdata                 = array();
    $userdata['ID']           = $uid;
    $userdata['nickname']     = $nickname;
    $userdata['display_name'] = @$userdata['nickname'];
    

    if ($current_user->user_email != $email) {
        // 邮箱验证
        $preg_email = '/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';
        if (preg_match($preg_email, $email)) {
            $userdata['user_email'] = esc_sql($email);
        } else {
            echo "邮箱格式错误";exit();
        }

       // 是否需要邮箱验证
        if (_cao('is_user_bang_email')) {
            if (empty($_POST['captcha']) || empty($_SESSION['CAO_code_captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['CAO_code_captcha']) {
                 echo "新邮箱验证码错误";exit();
            }
            if ($_SESSION['CAO_code_captcha_email'] != $email) {
                echo "验证码与新邮箱不对应";exit();
            }
        }
    }
    

    if (wp_update_user($userdata)) {
        if ($phone && $phone != get_user_meta($uid, 'phone', true)) {
            // 手机验证
            if (preg_match("/^1[345678]{1}\d{9}$/", $phone)) {
                update_user_meta($uid, 'phone', $phone);
            } else {
                echo "手机号码格式错误";exit();
            }
        }
        // is_numeric();
        if ($qq && $qq != get_user_meta($uid, 'qq', true)) {
            if (is_numeric($qq)) {
                update_user_meta($uid, 'qq', $qq);
            } else {
                echo "QQ号码格式错误";exit();
            }
        }
        if ($description && $description != get_user_meta($uid, 'description', true)) {
            update_user_meta($uid, 'description', $description);
        }
        if ($avatar_type) {
            update_user_meta($uid, 'user_avatar_type', $avatar_type);
        }
        echo "1";exit();
    } else {
        echo "修改失败";exit();
    }

    exit();
}

add_action('wp_ajax_edit_user_info', 'edit_user_info');
add_action('wp_ajax_nopriv_edit_user_info', 'edit_user_info');

//修改密码
function edit_repassword()
{
    global $current_user;
    isLoginCheck(); //检测登录
    $uid         = $current_user->ID;
    $password    = !empty($_POST['password']) ? wp_strip_all_tags($_POST['password']) : null;
    $new_password    = !empty($_POST['new_password']) ? wp_strip_all_tags($_POST['new_password']) : null;
    $re_password    = !empty($_POST['re_password']) ? wp_strip_all_tags($_POST['re_password']) : null;
    if (strlen($password) < 6) {
        echo "密码长度至少6位";exit();
    } elseif ($new_password != $re_password) {
        echo "两次输入密码不一致";exit();
    } else {
        $userdata['ID']        = $uid;
        $userdata['user_pass'] = $re_password;
        wp_update_user($userdata);
        echo "1";exit();
    }
    exit();
}
add_action('wp_ajax_edit_repassword', 'edit_repassword');
add_action('wp_ajax_nopriv_edit_repassword', 'edit_repassword');




function unset_open_oauth()
{
    global $current_user;
    isLoginCheck(); //检测登录
    $uid = $current_user->ID;
    $unsetid = !empty($_POST['unsetid']) ? $_POST['unsetid'] : null;
    if ($unsetid) {
        update_user_meta($uid, 'open_'.$unsetid.'_openid', '');
        update_user_meta($uid, 'open_'.$unsetid.'_bind', 0);
        echo "1";exit();
    }else{
        echo "0";exit();
    }
    
}
add_action('wp_ajax_unset_open_oauth', 'unset_open_oauth');
add_action('wp_ajax_nopriv_unset_open_oauth', 'unset_open_oauth');



/**
 * [user_qiandao 签到]
 * @Author   Dadong2g
 * @DateTime 2019-09-21T12:11:40+0800
 * @return   [type]                   [description]
 */
function user_qiandao()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid         = ($current_user->ID) ? $current_user->ID : 0 ;
    if ($uid == 0) {
        echo json_encode(array('status' => '0', 'msg' => '请登录后签到'));
        exit;
    }
    if (!_cao('is_qiandao','1')) {
        echo json_encode(array('status' => '0', 'msg' => '签到功能暂未开启'));
        exit;
    }
    if (_cao_user_is_qiandao()) {
        echo json_encode(array('status' => '0', 'msg' => '今日已签到，请明日再来'));
        exit;
    }else{
        $thenTime = time();
        $qiandao_money = _cao('qiandao_to_money','5');
        update_user_meta($uid, 'cao_qiandao_time',$thenTime);
        // 卡密有效 进行换算
        $CaoUser   = new CaoUser($uid);
        $old_money = $CaoUser->get_balance();
        if (!$CaoUser->update_balance($qiandao_money)) {
            echo json_encode(array('status' => '0', 'msg' => '签到异常，请稍后重试'));exit;
        }
        // 添加纪录
        if ($uid) {
            $Caolog    = new Caolog();
            $new_money = $old_money + $qiandao_money;
            $note      = '签到赠送'. $qiandao_money;
            $Caolog->addlog($uid, $old_money, $qiandao_money, $new_money, 'other', $note);
        }
        echo json_encode(array('status' => '1', 'msg' => '签到成功，赠送'.$qiandao_money._cao('site_money_ua') ));
        exit;
    }

}
add_action('wp_ajax_user_qiandao', 'user_qiandao');
add_action('wp_ajax_nopriv_user_qiandao', 'user_qiandao');



/**
 * [edit_user_qr AJAX保存收款码]
 * @Author   Dadong2g
 * @DateTime 2019-05-31T13:35:53+0800
 * @return   [type]                   [description]
 */
function edit_user_qr()
{
    global $current_user;
    isLoginCheck(); //检测登录

    $uid       = $current_user->ID;
    $qr_alipay = !empty($_POST['qr_alipay']) ? $_POST['qr_alipay'] : null;
    $qr_weixin = !empty($_POST['qr_weixin']) ? $_POST['qr_weixin'] : null;
    if ($qr_alipay && $qr_alipay != get_user_meta($uid, 'qr_alipay', true)) {
        update_user_meta($uid, 'qr_alipay', $qr_alipay);
    }
    if ($qr_weixin && $qr_weixin != get_user_meta($uid, 'qr_weixin', true)) {
        update_user_meta($uid, 'qr_weixin', $qr_weixin);
    }
    echo "1";exit();
}

add_action('wp_ajax_edit_user_qr', 'edit_user_qr');
add_action('wp_ajax_nopriv_edit_user_qr', 'edit_user_qr');



//收藏文章
function fav_post()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid         = ($current_user->ID) ? $current_user->ID : 0 ;
    $post_id    = !empty($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    
    if ($uid == 0) {
        echo json_encode(array('status' => '0', 'msg' => '请登录后再收藏'));
        exit;
    }
    
    if (is_get_post_fav($post_id)) {
        // 取消收藏
        _cao_del_follow_post($uid,$post_id);
        echo json_encode(array('status' => '1', 'msg' => '取消收藏成功'));exit;
    }else{
        //新收藏
        _cao_add_follow_post($uid,$post_id);
        echo json_encode(array('status' => '1', 'msg' => '收藏成功'));exit;
    }
    exit;
}
add_action('wp_ajax_fav_post', 'fav_post');
add_action('wp_ajax_nopriv_fav_post', 'fav_post');



///////////*******下载弹窗********////////////

function user_down_ajax()
{
    header('Content-type:application/json; Charset=utf-8');
    global $current_user;
    $uid         = ($current_user->ID) ? $current_user->ID : 0 ;
    $post_id = !empty($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
    
    if ($uid == 0 && !_cao('is_ripro_nologin_pay','1')) {
        echo json_encode(array('status' => '0', 'msg' => '请登录后下载'));
        exit;
    }
    if (!$post_id) {
        echo json_encode(array('status' => '0', 'msg' => '下载参数错误，请刷新重试'));
        exit;
    }

    // 判断是否有权限下载
    $CaoUser = new CaoUser($uid);
    $PostPay = new PostPay($uid, $post_id);
    $cao_is_post_free = cao_is_post_free($uid,$post_id);

    if ($cao_is_post_free && !is_user_logged_in()) {
        echo json_encode(array('status' => '0', 'msg' => '免费资源请登录后下载'));
        exit;
    }

    if ($PostPay->isPayPost() || $cao_is_post_free) {
        
        //免登录购买用户直接下载
        if(!is_user_logged_in() && _cao('is_ripro_nologin_pay','1')){
            echo json_encode(array('status' => '1', 'msg' => esc_url(home_url('/go?post_id='.$post_id)) ));
            exit;
        }

        // 判断会员类型 判断下载次数
        $vip_status = $CaoUser->vip_status();
        $this_vip_downum = cao_vip_downum($uid,$vip_status);

        if ($this_vip_downum['is_down'] || ($PostPay->isPayPost()) ) {
          echo json_encode(array('status' => '1', 'msg' => esc_url(home_url('/go?post_id='.$post_id)) ));
          exit;
        } else {
         $_msg = '<p>今日免费下载次数已用【'.$this_vip_downum['today_down_num'].'】,剩余【'.$this_vip_downum['over_down_num'].'】</p>';
         $_msg .= '<p style=" font-size: 15px; color: #888; margin: 0; ">'._cao('site_no_vip_name').'用户每日免费下载次数（'.($_num=(_cao('is_novip_down_num')) ? _cao('novip_down_num') : '无限').'）</p>';
         $_msg .= '<p style=" font-size: 15px; color: #888; margin: 0; ">'._cao('site_vip_name').'会员每日免费下载次数（'.($_num=(_cao('is_vip_down_num')) ? _cao('vip_down_num') : '无限').'）</p>';
         $_msg .= '<p style=" font-size: 15px; color: #888; margin: 0; ">永久'._cao('site_vip_name').'会员每日免费下载次数（'.($_num=(_cao('is_boosvip_down_num')) ? _cao('boosvip_down_num') : '无限').'）</p>';
          echo json_encode(array('status' => '0', 'msg' => $_msg));
          exit;
        }
      
    }else{
      echo json_encode(array('status' => '0', 'msg' => '您没有购买此资源或下载权限错误' ));
      exit;
    }
    echo json_encode(array('status' => '0', 'msg' => '您没有购买此资源或下载权限错误' ));
    exit;
}
add_action('wp_ajax_user_down_ajax', 'user_down_ajax');
add_action('wp_ajax_nopriv_user_down_ajax', 'user_down_ajax');


