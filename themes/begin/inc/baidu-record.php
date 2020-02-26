<?php
// 百度收录
function baidu_check($url){
	global $wpdb;
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
	$baidu_record  = get_post_meta($post_id,'baidu_record',true);
	if( $baidu_record != 1){
		$url='http://www.baidu.com/s?wd='.$url;
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$rs=curl_exec($curl);
		curl_close($curl);
		if(!strpos($rs,'没有找到')){
			if( $baidu_record == 0){
				update_post_meta($post_id, 'baidu_record', 1);
			} else {
				add_post_meta($post_id, 'baidu_record', 1, true);
			}
			return 1;
		} else {
			if( $baidu_record == false){
				add_post_meta($post_id, 'baidu_record', 0, true);
		}
			return 0;
		}
	} else {
		return 1;
	}
}

function baidu_record_t() {
	if (is_user_logged_in()) {
		if(baidu_check(get_permalink()) == 1) {
			echo '<li class="baidu-r"><a target="_blank" title="点击查看" rel="external nofollow" href="http://www.baidu.com/s?wd='.get_the_title().'"><i class="be be-baidu"></i>已收录</a></li>';
		} else {
			echo '<li class="baidu-r"><a rel="external nofollow" title="一键提交给百度" target="_blank" href="http://zhanzhang.baidu.com/sitesubmit/index?sitename='.get_permalink().'"><i class="be be-baidu"></i>暂未收录</a></li>';
		}
	}
}

function baidu_record_b() {
	if (is_user_logged_in()) {
		if(baidu_check(get_permalink()) == 1) {
			echo '<span class="baidu-r"><a target="_blank" title="点击查看" rel="external nofollow" href="http://www.baidu.com/s?wd='.get_the_title().'"><i class="be be-baidu"></i>已收录</a></span>';
		} else {
			echo '<span class="baidu-r"><a rel="external nofollow" title="一键提交给百度" target="_blank" href="http://zhanzhang.baidu.com/sitesubmit/index?sitename='.get_permalink().'"><i class="be be-baidu"></i>暂未收录</a></span>';
		}
	}
}