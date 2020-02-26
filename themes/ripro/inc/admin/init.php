<?php



/**
 * [my_users_columns 挂钩WP后台用户列表]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:32:52+0800
 * @param    [type]                   $columns [description]
 * @return   [type]                            [description]
 */
function my_users_columns($columns)
{
    $columns['vip_type'] = __('会员类型');
    $columns['vip_balance'] = __('余额');
    $columns['user_status'] = __('账号状态');

    $columns['reg_time'] = __('注册时间');
    $columns['signup_ip'] = __('注册IP');
    $columns['last_login'] = __('上次登录');
    $columns['last_login_ip'] = __('登录IP');
    return $columns;
}
/**
 * [output_my_users_columns 添加用户列表自定义列]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:32:38+0800
 * @param    [type]                   $var         [description]
 * @param    [type]                   $column_name [description]
 * @param    [type]                   $user_id     [description]
 * @return   [type]                                [description]
 */
function output_my_users_columns($var, $column_name, $user_id)
{
	$CaoUser = new CaoUser($user_id);
    $user = get_userdata( $user_id );
    switch ($column_name) {
        case "vip_type":
            return $CaoUser->vip_name();
            break;
        case "vip_balance":
            return $CaoUser->get_balance();
            break;
		case "user_status":
			$is_ban = (get_user_meta($user_id, 'cao_banned', true)) ? true : false;	
			if ($is_ban) {
				$str = '封号';
			}else{
				$str = '正常';
			}
			return $str;
			break;
        case "reg_time":
            return get_date_from_gmt($user->user_registered) ;
            break;
        case "signup_ip":
            return @get_user_meta( $user->ID, 'signup_ip', true);
            break;
        case "last_login":
            return @get_user_meta( $user->ID, 'last_login', ture);
            break;

        case "last_login_ip":
            return @get_user_meta( $user->ID, 'last_login_ip', ture);
            break;

    }
}
add_filter('manage_users_columns', 'my_users_columns');
add_action('manage_users_custom_column', 'output_my_users_columns', 10, 3);


/**
 * [my_post_custom_columns 挂钩WP后台文章列表]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:33:01+0800
 * @param    [type]                   $columns [description]
 * @return   [type]                            [description]
 */
function my_post_custom_columns($columns)
{
    // Add a new field
    $columns['cao_price'] = __('资源价格');
    // Delete an existing field, eg. comments
    unset($columns['comments']);
    return $columns;
}
/**
 * [output_my_post_custom_columns 添加文章列表自定义列]
 * @Author   Dadong2g
 * @DateTime 2019-05-28T12:32:08+0800
 * @param    [type]                   $column_name [description]
 * @param    [type]                   $post_id     [description]
 * @return   [type]                                [description]
 */
function output_my_post_custom_columns($column_name, $post_id)
{
    switch ($column_name) {
        case "cao_price":
            // Retrieve data and echo result
        $cao_price = (get_post_meta($post_id, 'cao_price', true)) ? get_post_meta($post_id, 'cao_price', true) : '—' ;
            echo $cao_price;
            break;
    }
}

add_filter('manage_posts_columns', 'my_post_custom_columns');
add_action('manage_posts_custom_column', 'output_my_post_custom_columns', 10, 2);





function add_settings_menu() {

    add_menu_page('商城管理', '商城管理', 'administrator',  'cao_admin_page', 'cao_admin_page', 'dashicons-cart', 100);
    add_submenu_page('cao_admin_page', _cao('site_money_ua').'充值记录', _cao('site_money_ua').'充值记录', 'administrator', 'cao_order_page','cao_order_page');
	add_submenu_page('cao_admin_page', '资源订单', '资源订单', 'administrator', 'cao_paylog_page','cao_paylog_page');
	add_submenu_page('cao_admin_page', '卡密管理', '卡密管理', 'administrator', 'cao_cdk_page','cao_cdk_page');
	add_submenu_page('cao_admin_page', '会员管理', '会员管理', 'administrator', 'users.php','');
	add_submenu_page('cao_admin_page', '提现管理', '提现管理', 'administrator', 'cao_ref_page','cao_ref_page');
	add_submenu_page('cao_admin_page', '后台充值'._cao('site_money_ua'), '后台充值'._cao('site_money_ua'), 'administrator', 'cao_charge_page','cao_charge_page');
    add_submenu_page('cao_admin_page', '用户余额明细查询', '用户余额明细查询', 'administrator', 'cao_balance_page','cao_balance_page');
    add_submenu_page('cao_admin_page', '批量修改资源价格', '批量修改资源价格', 'administrator', 'cao_editprice_page','cao_editprice_page');
	add_submenu_page('cao_admin_page', '数据库优化', '数据库优化', 'administrator', 'wp_clean_up_page','wp_clean_up_page');
}
if (is_site_shop_open()) {
    add_action('admin_menu','add_settings_menu');
}


require_once get_template_directory() . '/inc/plugins/wp-clean-up/wp-clean-up.php';

function cao_admin_page() {
    require_once get_template_directory() . '/inc/admin/page/index.php';
}

function cao_order_page() {
    require_once get_template_directory() . '/inc/admin/page/order.php';
}

function cao_paylog_page() {
    require_once get_template_directory() . '/inc/admin/page/paylog.php';
}

function cao_vip_page() {
    // require_once get_template_directory() . '/inc/admin/page/vip.php';
}

function cao_cdk_page() {
    require_once get_template_directory() . '/inc/admin/page/cdk.php';
}

function cao_ref_page() {
    require_once get_template_directory() . '/inc/admin/page/ref.php';
}

function cao_charge_page() {
    require_once get_template_directory() . '/inc/admin/page/charge.php';
}
function cao_balance_page() {
    require_once get_template_directory() . '/inc/admin/page/balance.php';
}
function cao_editprice_page() {
    require_once get_template_directory() . '/inc/admin/page/editprice.php';
}