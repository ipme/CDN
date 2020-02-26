<?php
// 添加视频
function my_videos( $atts, $content = null ) {
	extract( shortcode_atts( array (
		'href' => '',
		 'img' => '<img class="aligncenter" src="'.$content.'">'
	), $atts ) );
	return '<div class="video-content"><a class="videos" href="'.$href.'" title="播放视频">'.$img.'<i class="be be-play"></i></a></div>';
}

// 评论可见
function reply_read($atts, $content=null) {
	extract(shortcode_atts(array("notice" => '
	<div class="reply-read">
		<div class="reply-ts">
			<div class="read-sm"><i class="be be-info"></i>' . sprintf(__( '此处为隐藏的内容！', 'begin' )) . '</div>
			<div class="read-sm"><i class="be be-loader"></i>' . sprintf(__( '发表评论并刷新，方可查看', 'begin' )) . '</div>
		</div>
		<div class="read-pl"><a href="#respond" class="flatbtn"><i class="be be-speechbubble"></i>' . sprintf(__( '发表评论', 'begin' )) . '</a></div>
		<div class="clear"></div>
    </div>'), $atts));
	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {
			return '<div class="hide-t"><i class="be be-loader"></i>' . sprintf(__( '隐藏的内容', 'begin' )) . '</div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
		}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $notice;
	}
    if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if ($wpdb->get_results($query)) {
		return do_shortcode('<div class="hide-t"><i class="be be-loader"></i>' . sprintf(__( '隐藏的内容', 'begin' )) . '</div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>');
	} else {
		return $notice;
	}
}

// 登录可见
function login_to_read($atts, $content = null) {
	extract(shortcode_atts(array("notice" =>'
	<div class="reply-read">
		<div class="reply-ts">
			<div class="read-sm"><i class="be be-info"></i>' . sprintf(__( '此处为隐藏的内容！', 'begin' )) . '</div>
			<div class="read-sm"><i class="be be-loader"></i>' . sprintf(__( '登录后方可查看！', 'begin' )) . '</div>
		</div>
		<div class="read-pl"><a href="#login" class="flatbtn show-layer" data-show-layer="login-layer" role="button"><i class="be be-timerauto"></i>' . sprintf(__( '登录', 'begin' )) . '</a></div>
		<div class="clear"></div>
	</div>'), $atts));
	if (is_user_logged_in()) {
		return '<div class="hide-t"><i class="be be-loader"></i>' . sprintf(__( '隐藏的内容', 'begin' )) . '</div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
	} else {
		return $notice;
	}
}

// 加密内容
function secret($atts, $content=null){
extract(shortcode_atts(array('key'=>null), $atts));
if ( current_user_can('level_10') ) {
	return '<div class="hide-t"><i class="be be-loader"></i>' . sprintf(__( '隐藏的内容', 'begin' )) . '</div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
}
if(isset($_POST['secret_key']) && $_POST['secret_key']==$key){
	return '<div class="hide-t"><i class="be be-loader"></i>' . sprintf(__( '隐藏的内容', 'begin' )) . '</div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
	} else {
		return '
		<form class="post-password-form" action="'.get_permalink().'" method="post">
			<div class="post-secret"><i class="be be-info"></i>' . sprintf(__( '输入密码查看隐藏内容：', 'begin' )) . '</div>
			<p>
				<input id="pwbox" type="password" size="20" name="secret_key">
				<input type="submit" value="' . sprintf(__( '提交', 'begin' )) . '" name="Submit">
			</p>
		</form>';
	}
}

// 关注密码
function wechat_key($atts, $content=null) {
	extract(shortcode_atts( array (
		'key' => null,
		'reply' => ''
		), $atts));
	if ( current_user_can('level_10') ) {
		return '<div class="hide-t"><i class="be be-loader"></i>' . sprintf(__( '隐藏的内容', 'begin' )) . '</div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
	}
	$cookie_name = 'wechat_key';
	$c = md5($cookie_name);
	$cookie_value = isset($_COOKIE[$cookie_name])?$_COOKIE[$cookie_name]:'';
	if($cookie_value==$c || isset($_POST['wechat_key']) && $_POST['wechat_key']==$key) {
		setcookie($cookie_name, $c ,time()+(int)30*86400, "/");
		$_COOKIE[$cookie_name] = $c;
		return '<div class="hide-t"><i class="be be-loader"></i>' . sprintf(__( '隐藏的内容', 'begin' )) . '</div><div class="secret-password">'.do_shortcode( $content ).'</div><div class="secret-b"></div>';
	} else {
		return '
		<form class="post-password-form wechat-key-form" action="'.get_permalink().'" method="post">
			<div class="wechat-box wechat-left">
				<div class="post-secret"><i class="be be-info"></i>' . sprintf(__( '输入验证码查看隐藏内容：', 'begin' )) . '</div>
				<p>
					<input id="wpbox" type="password" size="20" name="wechat_key">
					<input type="submit" value="' . sprintf(__( '提交', 'begin' )) . '" name="Submit">
				</p>
				<div class="wechat-secret">
					<div class="wechat-follow">扫描二维码关注本站微信公众号 <span class="wechat-w">'.zm_get_option('wechat_fans').'</span></div>
					<div class="wechat-follow">或者在微信里搜索 <span class="wechat-w">'.zm_get_option('wechat_fans').'</span></div>
					<div class="wechat-follow">回复 <span class="wechat-w">'.$reply.'</span> 获取验证码</div>
				</div>
			</div>
			<div class="wechat-box wechat-right">
				<img src="'.zm_get_option('wechat_qr').'" alt="wechat">
				<span class="wechat-t">'.zm_get_option('wechat_fans').'</span>
			</div>
			<div class="clear"></div>
		</form>';
	}
}

// 下载回复查看
function pan_password($atts, $content=null) {
	extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>网盘密码：</strong><span class="reply-prompt"><i class="be be-warning"></i>发表评论并刷新可见</span>', 'begin' )) . '</div>'), $atts));
	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {return ''.do_shortcode( $content ).'';}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $notice;
	}
	if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if ($wpdb->get_results($query)) {
		return do_shortcode(''.do_shortcode( $content ).'');
	} else {
		return $notice;
	}
}

function rar_password($atts, $content=null) {
	extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>解压密码：</strong><span class="reply-prompt"><i class="be be-warning"></i>发表评论并刷新可见</span>', 'begin' )) . '</div>'), $atts));
	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {return ''.do_shortcode( $content ).'';}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $notice;
	}
	if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if ($wpdb->get_results($query)) {
		return do_shortcode(''.do_shortcode( $content ).'');
	} else {
		return $notice;
	}
}

function down_password($atts, $content=null) {
	extract(shortcode_atts(array("notice" => '<div class="reply-pass">' . sprintf(__( '<strong>下载地址：</strong><span class="reply-prompt"><i class="be be-warning"></i>发表评论并刷新可见</div>', 'begin' )) . '</span>'), $atts));
	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {return ''.do_shortcode( $content ).'';}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $notice;
	}
	if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if ($wpdb->get_results($query)) {
		return do_shortcode(''.do_shortcode( $content ).'');
	} else {
		return $notice;
	}
}

// 幻灯
function gallery($atts, $content=null){
	return '<div id="slider-single" class="slider-single">'.$content.'</div>';
}

// 下载按钮
function button_a($atts, $content = null) {
	return '<div class="down"><a class="d-popup" title="下载链接" href="#button_file"><i class="be be-download"></i>下载地址</a><div class="clear"></div></div>';
}

// 下载按钮
function button_url($atts,$content=null){
	global $wpdb, $post;
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	if ( get_post_meta($post->ID, 'down_link_much', true) ) {
		return '<div class="down down-link down-much"><a href="'.$href.'" rel="external nofollow" target="_blank"><i class="be be-download"></i>'.$content.'</a></div><div class="down-return"></div>';
	} else {
		return '<div class="down down-link"><a href="'.$href.'" rel="external nofollow" target="_blank"><i class="be be-download"></i>'.$content.'</a></div><div class="clear"></div>';
	}
}

// 链接按钮
function button_link($atts,$content=null){
	global $wpdb, $post;
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	if ( get_post_meta($post->ID, 'down_link_much', true) ) {
		return '<div class="down down-link down-much"><a href="'.$href.'" rel="external nofollow" target="_blank">'.$content.'</a></div><div class="down-return"></div>';
	} else {
		return '<div class="down down-link"><a href="'.$href.'" rel="external nofollow" target="_blank">'.$content.'</a></div><div class="clear"></div>';
	}
}

// fieldset标签
function fieldset_label($atts, $content = null) {
	return do_shortcode( $content );
}

// 添加<code>
function addcode($atts, $content=null, $code="") {
	$return = '<code>';
	$return .= $content;
	$return .= '</code>';
	return $return;
}
add_shortcode('code' , 'addcode');

// 添加宽图
function add_full_img($atts, $content=null, $full_img="") {
	$return = '<div class="full-img">';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	return $return;
}

// 隐藏图
function add_hide_img($atts, $content=null, $hide_img="") {
	$return = '<div class="hide-img">';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	return $return;
}

// 两栏
function add_two_column($atts, $content=null, $two_column="") {
	$return = '<div class="two-column">';
	$return .= do_shortcode( $content );
	$return .= '</div>';
	return $return;
}

// 同标签
function tags_posts( $atts, $content = null ){
	extract( shortcode_atts( array(
		'ids' => '',
		'title' => '',
		'n' => ''
	),
	$atts ) );
	$content .=  '<div class="tags-posts"><h3>'.$title.'</h3><ul>';
	$recent = new WP_Query( array( 'posts_per_page' => $n, 'tag__in' => explode(',', $ids)) );
	while($recent->have_posts()) : $recent->the_post();
	$content .=  '<li><a target="_blank" href="' . get_permalink() . '"><i class="be be-arrowright"></i>' . get_the_title() . '</a></li>';
	endwhile;wp_reset_query();
	$content .=  '</ul></div>';
	return $content;
}

// 文字展开
function show_more($atts, $content = null) {
	return '<span class="show-more" title="' . (__( '文字折叠', 'begin' )) . '"><span><i class="be be-squareplus"></i>' . sprintf(__( '展开', 'begin' )) . '</span></span>';
}

function section_content($atts, $content = null) {
	return '<div class="section-content">'.do_shortcode( $content ).'</p></div><p>';
}

// 短代码广告
function post_ad(){
if ( wp_is_mobile() ) {
		return '<div class="post-tg"><div class="tg-m tg-site">'.stripslashes( zm_get_option('ad_s_z_m') ).'</div></div>';
	} else {
		return '<div class="post-tg"><div class="tg-pc tg-site">'.stripslashes( zm_get_option('ad_s_z') ).'</div></div>';
	}
}

// 直达链接
function direct_btn(){
	global $post;
	if ( get_post_meta($post->ID, 'direct', true) ) {
	$direct = get_post_meta($post->ID, 'direct', true);
		if ( get_post_meta($post->ID, 'direct_btn', true) ) {
		$direct_btn = get_post_meta($post->ID, 'direct_btn', true);
		return '<div class="content-more"><a href="'.$direct.'" target="_blank" rel="nofollow">'.$direct_btn.'</a></div>';
	} else {
			return '<div class="content-more"><a href="'.$direct.'" target="_blank" rel="nofollow">'.zm_get_option('direct_w').'</a></div>';
		}
	}
}

// 醒目框
function zm_green($atts, $content=null){
	return '<div class="mark_a mark">'.do_shortcode( $content ).'</div>';
}

function zm_red($atts, $content=null){
	return '<div class="mark_b mark">'.do_shortcode( $content ).'</div>';
}

function zm_gray($atts, $content=null){
	return '<div class="mark_c mark">'.do_shortcode( $content ).'</div>';
}

function zm_yellow($atts, $content=null){
	return '<div class="mark_d mark">'.do_shortcode( $content ).'</div>';
}

function zm_blue($atts, $content=null){
	return '<div class="mark_e mark">'.do_shortcode( $content ).'</div>';
}

// 按钮
function begin_add_mce_button() {
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'begin_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'begin_register_mce_button' );
	}
}

function begin_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['begin_mce_button'] = get_bloginfo( 'template_url' ) . '/js/mce-button.js';
	return $plugin_array;
}
function begin_register_mce_button( $buttons ) {
	array_push( $buttons, 'begin_mce_button' );
	return $buttons;
}

// 列表按钮
function lists_code_plugin() {
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
		return;
	}
	if (get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'list_mce_external_plugins_filter');
		add_filter('mce_buttons', 'list_mce_buttons_filter');
	}
}

function list_mce_external_plugins_filter($plugin_array) {
	$plugin_array['list_code_plugin'] = get_template_directory_uri() . '/inc/addlist/list-btn.js';
	return $plugin_array;
}

function list_mce_buttons_filter($buttons) {
	array_push($buttons, 'list_code_plugin');
	return $buttons;
}


function wplist_shortcode($atts, $content = '') {
	$atts['content'] = $content;
	$out = '<div class="wplist-item"><a href="' . $atts['link'] . '" target="_blank" isconvert="1" rel="nofollow" >';
	$out.= '<div class="wplist-item-img"><img itemprop="image" src="' . $atts['img'] . '" alt="' . $atts['title'] . '" /></div>';
	$out.= '<div class="wplist-title">' . $atts['title'] . '</div>';
	$out.= '<p class="wplist-des">' . $atts['content'] . '</p>';
	if (!empty($atts['price'])) {
		$out.= '<div class="wplist-oth"><div class="wplist-res wplist-price">' . $atts['price'] . '</div>';
		if (!empty($atts['oprice'])) {
			$out.= '<div class="wplist-res wplist-old-price"><del>' . $atts['oprice'] . '</del></div>';
		}
		$out.= '</div>';
	}
	$out.= '<div class="wplist-btn">' . $atts['btn'] . '</div><div class="clear"></div>';
	$out.= '</a><div class="clear"></div></div>';
	return $out;
}

// TAB按钮
function tab_code_plugin() {
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
		return;
	}
	if (get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'tabs_mce_external_plugins_filter');
		add_filter('mce_buttons', 'tabs_mce_buttons_filter');
	}
}

function tabs_mce_external_plugins_filter($plugin_array) {
	$plugin_array['tabs_code_plugin'] = get_template_directory_uri() . '/inc/addlist/tabs-btn.js';
	return $plugin_array;
}

function tabs_mce_buttons_filter($buttons) {
	array_push($buttons, 'tabs_code_plugin');
	return $buttons;
}

function start_tab_shortcode($atts, $content = '') {
	return '<div class="tab-group">';
}

function tabs_shortcode($atts, $content = '') {
	$atts['content'] =  do_shortcode($content);
	$out.= '<section id="tab'.$atts['number'].'" title="' . $atts['title'] . '">';
	$out.= $atts['content'];
	$out.= '</section>';
	return $out;
}

function end_tab_shortcode($atts, $content = '') {
	return '</div>';
}

// iframe
function iframe_add_shortcode( $atts ) {
	$defaults = array(
		'src' => '',
		'width' => '100%',
		'height' => '500',
		'scrolling' => 'yes',
		'class' => 'iframe-class',
		'frameborder' => '0'
	);

	foreach ( $defaults as $default => $value ) {
		if ( ! @array_key_exists( $default, $atts ) ) {
			$atts[$default] = $value;
		}
	}
	$html .= '<iframe';
	foreach( $atts as $attr => $value ) {
		if ( strtolower($attr) != 'same_height_as' AND strtolower($attr) != 'onload'
			AND strtolower($attr) != 'onpageshow' AND strtolower($attr) != 'onclick') {
			if ( $value != '' ) {
				$html .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
			} else {
				$html .= ' ' . esc_attr( $attr );
			}
		}
	}
	$html .= '></iframe>'."\n";

	if ( isset( $atts["same_height_as"] ) ) {
		$html .= '
			<script>
			document.addEventListener("DOMContentLoaded", function(){
				var target_element, iframe_element;
				iframe_element = document.querySelector("iframe.' . esc_attr( $atts["class"] ) . '");
				target_element = document.querySelector("' . esc_attr( $atts["same_height_as"] ) . '");
				iframe_element.style.height = target_element.offsetHeight + "px";
			});
			</script>
		';
	}
	return $html;
}
add_shortcode( 'iframe', 'iframe_add_shortcode' );