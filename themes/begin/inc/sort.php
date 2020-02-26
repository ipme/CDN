<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function begin_order() {
	if ( isset($_GET['order']) ) {
		switch ($_GET['order']) {
			case 'rand' : $orderby = 'rand'; break;
			case 'commented' : $orderby = 'comment_count'; break;
			default : $orderby = 'title';
		}
		global $wp_query;
		if($_GET['order'] == 'zm_like') {
			$args = array( 'meta_key' => 'zm_like', 'orderby' => 'meta_value_num');
		} else {
			$args= array('orderby' => $orderby, 'order' => 'DESC');
		}
		if($_GET['order'] == 'views') {
			$args = array( 'meta_key' => 'views', 'orderby' => 'meta_value_num');
		} else {
			$args= array('orderby' => $orderby, 'order' => 'DESC');
		}
		$arms = array_merge($args, $wp_query->query);
		query_posts($arms);
	}
}

function begin_orderby() {
	echo '<div class="sorting"><ul><li class="order"><a href="javascript:void(0)" title="' . sprintf(__( '文章排序', 'begin' )) . '"><i class="be be-sort"></i></a></li></ul>';
	echo '<ul class="order-box">';
	echo '<li><a href="';
	echo bloginfo('url');
	echo '" rel="nofollow" title="' . sprintf(__( '按日期排序', 'begin' )) . '"><i class="be be-calendar2"></i></a></li>';
	echo '<li><a ';
		if ( isset($_GET['order']) && ($_GET['order']=='rand') ) echo 'class="current"';
	echo 'href="';
		echo get_option('home');
	echo '/?order=rand" rel="nofollow" title="' . sprintf(__( '随机排序', 'begin' )) . '"><i class="be be-repeat"></i></a></li>';
	echo '<li><a ';
		if ( isset($_GET['order']) && ($_GET['order']=='commented') ) echo 'class="current"';
	echo 'href="';
		echo get_option('home');
	echo '/?order=commented" rel="nofollow" title="' . sprintf(__( '按评论排序', 'begin' )) . '"><i class="be be-speechbubble"></i></a></li>';
	echo '<li><a ';
		if ( isset($_GET['order']) && ($_GET['order']=='views') ) echo 'class="current"';
	echo 'href="';
		echo get_option('home');
	echo '/?order=views" rel="nofollow" title="' . sprintf(__( '按浏览排序', 'begin' )) . '"><i class="be be-eye"></i></a></li>';
	echo '<li><a ';
		if ( isset($_GET['order']) && ($_GET['order']=='views') ) echo 'class="current"';
	echo 'href="';
		echo get_option('home');
	echo '/?order=zm_like" rel="nofollow" title="' . sprintf(__( '按点赞排序', 'begin' )) . '"><i class="be be-thumbs-up"></i></a></li>';
	echo '</ul></div>';
}