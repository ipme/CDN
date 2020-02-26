<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $wpdb;
function begin_show_down($content) {
	if(is_single()) {
		$down_start=get_post_meta(get_the_ID(), 'down_start', true);
		$password_down=get_post_meta(get_the_ID(), 'password_down', true);
		$down_name=get_post_meta(get_the_ID(), 'down_name', true);
		$file_inf=get_post_meta(get_the_ID(), 'file_inf', true);
		$file_os=get_post_meta(get_the_ID(), 'file_os', true);
		$down_size=get_post_meta(get_the_ID(), 'down_size', true);
		$down_demo=get_post_meta(get_the_ID(), 'down_demo', true);
		$r_baidu_password=get_post_meta(get_the_ID(), 'r_baidu_password', true);
		$r_rar_password=get_post_meta(get_the_ID(), 'r_rar_password', true);
		$links_id=get_post_meta(get_the_ID(), 'links_id', true);
		$wechat_follow=get_post_meta(get_the_ID(), 'wechat_follow', true);
		if ( !$click_count = get_post_meta( $links_id, '_surl_count', true ) ) {$click_count = 0;}

		if($down_demo) {
			$demo_content .= '<a class="yanshibtn" rel="external nofollow"   href="'.get_template_directory_uri().'/preview.php?id='.get_the_ID().'" target="_blank" title="'.$down_demo.' "><i class="be be-eye" ></i>'. sprintf(__( '查看演示', 'begin' )) .'</a>';
		}

		if($r_baidu_password) {
			$pan_bd_password .=  '[pan]<span><strong>'. sprintf(__( '网盘密码', 'begin' )) .'： </strong>'.$r_baidu_password.'</span>[/pan]';
		}

		if($r_rar_password) {
			$rar_zip_password .=  '[rar]<span><strong>'. sprintf(__( '解压密码', 'begin' )) .'： </strong>'.$r_rar_password.'</span>[/rar]';
		}

		if($wechat_follow) {
			$wechat_reply .= '<span><strong>'. sprintf(__( '解压密码', 'begin' )) .'： </strong><br />扫描二维码关注本站微信公众号，回复 <strong class="wechat-w">'.$wechat_follow.'</strong> 获取解压密码</span>';
		}

		if($wechat_reply) {
			$wechat_img .= '
			<span class="wechat-right">
				<img src="'.zm_get_option('wechat_qr').'" alt="wechat">
				<span class="wechat-t">'.zm_get_option('wechat_fans').'</span>
			</span>';
		}

		if($password_down) {
			$down_password .=  '[down]<span class="down"><a title="'.$begin_name.'" href="'.get_template_directory_uri().'/down.php?id='.get_the_ID().'" rel="external nofollow" target="_blank"><i class="be be-download"></i>'. sprintf(__( '下载地址', 'begin' )) .'</a></span>[/down]';
		} else {
			$down_password .=  '<span class="down"><a title="'.$begin_name.'" href="'.get_template_directory_uri().'/down.php?id='.get_the_ID().'" rel="external nofollow" target="_blank"><i class="be be-download"></i>'. sprintf(__( '下载地址', 'begin' )) .'</a></span>';
		}

		if($links_id) {
			$click_count_on .=  '<span><strong>文件下载：</strong>'.$click_count.'&nbsp;次</span>';
		}

		if($down_start) {
			$content .= '
			<div class="down-form">
				<fieldset>
					<legend>'. sprintf(__( '下载信息', 'begin' )) .'</legend>
					'.$wechat_img.'
					<span class="down-form-inf">
						<span class="xz">'.$down_name.'</span>
						<span class="xz">'.$file_os.'</span>
						<span class="xz">'.$file_inf.'</span>
						<span class="xz">'.$down_size.'</span>
						'.$click_count_on.'
						<span class="xz pass">'.$wechat_reply.'</span>
						<span class="xz pass">'.$pan_bd_password.'</span>
						<span class="xz pass">'.$rar_zip_password.'</span>
						<div class="clear"></div>
					</span>
					<span class="xz down"> '.$down_password.'</span>
					<span class="xz down">
					 '.$demo_content.'
					</span>
					<div class="clear"></div>
				</fieldset>
			</div>
			<div class="clear"></div>';
		}
	}
	return $content;
}
add_action('the_content','begin_show_down');