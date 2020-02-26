<?php
/*
Name: WP-PostViews
Author: Lester 'GaMerZ' Chan
*/

### 添加设置菜单
add_action('admin_menu', 'begin_postviews_menu');
function begin_postviews_menu() { 
	add_options_page('PostViews','浏览计数','manage_options','views_options', 'postviews_settings_admin');
}
function postviews_settings_admin() {
	require get_template_directory() . '/inc/postviews/postviews-options.php';
}

### 统计文章浏览
add_action( 'wp_head', 'begin_process_postviews' );
function begin_process_postviews() {
	global $user_ID, $post;
	if( is_int( $post ) ) {
		$post = get_post( $post );
	}
	if( ! wp_is_post_revision( $post ) && ! is_preview() ) {
		if( is_single() || is_page() ) {
			$id = intval( $post->ID );
			$views_options = get_option( 'views_options' );
			if ( !$post_views = get_post_meta( $post->ID, 'views', true ) ) {
				$post_views = 0;
			}
			$should_count = false;
			switch( intval( $views_options['count'] ) ) {
				case 0:
					$should_count = true;
					break;
				case 1:
					if(empty( $_COOKIE[USER_COOKIE] ) && intval( $user_ID ) === 0) {
						$should_count = true;
					}
					break;
				case 2:
					if( intval( $user_ID ) > 0 ) {
						$should_count = true;
					}
					break;
			}
			if( intval( $views_options['exclude_bots'] ) === 1 ) {
				$bots = array
				(
					'Google Bot' => 'google'
					, 'MSN' => 'msnbot'
					, 'Alex' => 'ia_archiver'
					, 'Lycos' => 'lycos'
					, 'Ask Jeeves' => 'jeeves'
					, 'Altavista' => 'scooter'
					, 'AllTheWeb' => 'fast-webcrawler'
					, 'Inktomi' => 'slurp@inktomi'
					, 'Turnitin.com' => 'turnitinbot'
					, 'Technorati' => 'technorati'
					, 'Yahoo' => 'yahoo'
					, 'Findexa' => 'findexa'
					, 'NextLinks' => 'findlinks'
					, 'Gais' => 'gaisbo'
					, 'WiseNut' => 'zyborg'
					, 'WhoisSource' => 'surveybot'
					, 'Bloglines' => 'bloglines'
					, 'BlogSearch' => 'blogsearch'
					, 'PubSub' => 'pubsub'
					, 'Syndic8' => 'syndic8'
					, 'RadioUserland' => 'userland'
					, 'Gigabot' => 'gigabot'
					, 'Become.com' => 'become.com'
					, 'Baidu' => 'baiduspider'
					, 'so.com' => '360spider'
					, 'Sogou' => 'spider'
					, 'soso.com' => 'sosospider'
					, 'Yandex' => 'yandex'
				);
				$useragent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
				foreach ( $bots as $name => $lookfor ) {
					if ( ! empty( $useragent ) && ( stristr( $useragent, $lookfor ) !== false ) ) {
						$should_count = false;
						break;
					}
				}
			}
			if( $should_count && ( ( isset( $views_options['use_ajax'] ) && intval( $views_options['use_ajax'] ) === 0 ) || ( !defined( 'WP_CACHE' ) || !WP_CACHE ) ) ) {
				if (zm_get_option('rand_views')) {
					update_post_meta( $id, 'views', ( $post_views + mt_rand(1, zm_get_option('rand_mt')) ) );
					do_action( 'postviews_increment_views', ( $post_views + mt_rand(1, zm_get_option('rand_mt')) ) );
				} else {
					update_post_meta( $id, 'views', ( $post_views + 1 ) );
					do_action( 'postviews_increment_views', ( $post_views + 1 ) );
				}
			}
		}
	}
}

### 在启用WP_CACHE的情况下统计浏览
add_action('wp_enqueue_scripts', 'begin_postview_cache_count_enqueue');
function begin_postview_cache_count_enqueue() {
	global $user_ID, $post;

	if( !defined( 'WP_CACHE' ) || !WP_CACHE )
		return;

	$views_options = get_option( 'views_options' );

	if( isset( $views_options['use_ajax'] ) && intval( $views_options['use_ajax'] ) === 0 )
		return;

	if ( !wp_is_post_revision( $post ) && ( is_single() || is_page() ) ) {
		$should_count = false;
		switch( intval( $views_options['count'] ) ) {
			case 0:
				$should_count = true;
				break;
			case 1:
				if ( empty( $_COOKIE[USER_COOKIE] ) && intval( $user_ID ) === 0) {
					$should_count = true;
				}
				break;
			case 2:
				if ( intval( $user_ID ) > 0 ) {
					$should_count = true;
				}
				break;
		}
		if ( $should_count ) {
			wp_enqueue_script( 'wp-postviews-cache', get_template_directory_uri() . '/inc/postviews/postviews-cache.js', array(), '', true );
			wp_localize_script( 'wp-postviews-cache', 'viewsCacheL10n', array( 'admin_ajax_url' => admin_url( 'admin-ajax.php' ), 'post_id' => intval( $post->ID ) ) );
		}
	}
}

### 确定显示
function begin_should_views_be_displayed($views_options = null) {
	if ($views_options == null) {
		$views_options = get_option('views_options');
	}
	$display_option = 0;
	return ($display_option == 0);
}

### 显示文章浏览统计
function the_views($display = true, $prefix = '', $postfix = '', $always = false) {
	$post_views = intval( get_post_meta( get_the_ID(), 'views', true ) );
	$views_options = get_option('views_options');
	if ($always || begin_should_views_be_displayed($views_options)) {
		$output = $prefix.str_replace( array( '%VIEW_COUNT%', '%VIEW_COUNT_ROUNDED%' ), array( number_format_i18n( $post_views ), begin_postviews_round_number( $post_views) ), stripslashes( $views_options['template'] ) ).$postfix;
		if($display) {
			echo apply_filters('the_views', $output);
		} else {
			return apply_filters('the_views', $output);
		}
	}
	elseif (!$display) {
		return '';
	}
}

### 添加视图自定义栏目
add_action('publish_post', 'begin_add_views_fields');
add_action('publish_page', 'begin_add_views_fields');
function begin_add_views_fields($post_ID) {
	global $wpdb;
	if(!wp_is_post_revision($post_ID)) {
		add_post_meta($post_ID, 'views', 0, true);
	}
}


### 公共变量
add_filter('query_vars', 'begin_views_variables');
function begin_views_variables($public_query_vars) {
	$public_query_vars[] = 'v_sortby';
	$public_query_vars[] = 'v_orderby';
	return $public_query_vars;
}

### 增加文章浏览统计
add_action( 'wp_ajax_postviews', 'begin_increment_views' );
add_action( 'wp_ajax_nopriv_postviews', 'begin_increment_views' );
function begin_increment_views() {
	if( empty( $_GET['postviews_id'] ) )
		return;

	if( !defined( 'WP_CACHE' ) || !WP_CACHE )
		return;

	$views_options = get_option( 'views_options' );

	if( isset( $views_options['use_ajax'] ) && intval( $views_options['use_ajax'] ) === 0 )
		return;

	$post_id = intval( $_GET['postviews_id'] );
	if( $post_id > 0 ) {
		$post_views = get_post_custom( $post_id );
		$post_views = intval( $post_views['views'][0] );
		if (zm_get_option('rand_views')) {
			update_post_meta( $post_id, 'views', ( $post_views + mt_rand(1, zm_get_option('rand_mt')) ) );
			do_action( 'postviews_increment_views_ajax', ( $post_views + mt_rand(1, zm_get_option('rand_mt')) ) );
		} else {
			update_post_meta( $post_id, 'views', ( $post_views + 1 ) );
			do_action( 'postviews_increment_views_ajax', ( $post_views + 1 ) );
		}
		echo ( $post_views + 1 );
		exit();
	}
}

### 后台文章列表添加浏览计数
add_action('manage_posts_custom_column', 'begin_add_postviews_column_content');
add_filter('manage_posts_columns', 'begin_add_postviews_column');
add_action('manage_pages_custom_column', 'begin_add_postviews_column_content');
add_filter('manage_pages_columns', 'begin_add_postviews_column');
function begin_add_postviews_column($defaults) {
	$defaults['views'] = __( '浏览', 'begin' );
	return $defaults;
}


### 浏览次数
function begin_add_postviews_column_content($column_name) {
	if($column_name == 'views') {
		if(function_exists('the_views')) { the_views(true, '', '', true); }
	}
}

### 将数字四舍五入为K（千），M（百万）或B（十亿）
function begin_postviews_round_number( $number, $min_value = 1000, $decimal = 1 ) {
	if( $number < $min_value ) {
		return number_format_i18n( $number );
	}
	$alphabets = array( 1000000000 => 'B', 1000000 => 'M', 1000 => 'K' );
	foreach( $alphabets as $key => $value )
		if( $number >= $key ) {
			return round( $number / $key, $decimal ) . '' . $value;
		}
}

### Function: Parse View Options
function begin_views_options_parse($key) {
	return !empty($_POST[$key]) ? $_POST[$key] : null;
}