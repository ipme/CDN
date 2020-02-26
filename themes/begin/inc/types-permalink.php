<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// 分类法固定链接
$begin_types = array(
	'bulletin' => 'bulletin',
	'picture' => zm_get_option('img_url'),
	'video' => zm_get_option('video_url'),
	'tao' => zm_get_option('sp_url'),
	'sites' =>  zm_get_option('favorites_url'),
	'show' => zm_get_option('show_url'),
	'product' => 'product',
	'portfolio' => 'portfolio',
	// 可以在此添加其它
);

if (!zm_get_option('begin_types') || (zm_get_option('begin_types') == 'link_id')) {
	add_filter('post_type_link', 'begin_custom_post_type_link_id', 1, 3);
	add_action( 'init', 'begin_custom_post_type_rewrites_init_id' );
}

if (zm_get_option('begin_types') == 'link_name') {
	add_filter('post_type_link', 'begin_custom_post_type_link_name', 1, 3);
	add_action( 'init', 'begin_custom_post_type_rewrites_init_name' );
}

// ID.html
function begin_custom_post_type_link_id( $link, $post = 0 ){
	global $begin_types;
	if ( in_array( $post->post_type,array_keys($begin_types) ) ){
		return home_url( $begin_types[$post->post_type].'/' . $post->ID .'.html' );
	} else {
		return $link;
	}
}

function begin_custom_post_type_rewrites_init_id(){
	global $begin_types;
	foreach( $begin_types as $k => $v ) {
		add_rewrite_rule(
			$v.'/([0-9]+)?.html$',
			'index.php?post_type='.$k.'&p=$matches[1]',
			'top'
		);
		add_rewrite_rule(
			$v.'/([0-9]+)?.html/comment-page-([0-9]{1,})$',
			'index.php?post_type='.$k.'&p=$matches[1]&cpage=$matches[2]',
			'top'
		);
	}
}

// post_name.html
function begin_custom_post_type_link_name( $link, $post = 0 ){
	global $begin_types;
	if ( in_array( $post->post_type,array_keys($begin_types) ) ){
		return home_url( $begin_types[$post->post_type].'/' . $post->post_name .'.html' );
	} else {
		return $link;
	}
}

function begin_custom_post_type_rewrites_init_name(){
	global $begin_types;
	foreach( $begin_types as $k => $v ) {
		add_rewrite_rule(
			$v.'/([一-龥a-zA-Z0-9_-]+)?.html([sS]*)?$',
			'index.php?post_type='.$k.'&name=$matches[1]',
			'top'
		);
		add_rewrite_rule(
			
			$v.'/([一-龥a-zA-Z0-9_-]+)?.html/comment-page-([一-龥a-zA-Z0-9_-]{1,})$',
			'index.php?post_type='.$k.'&name=$matches[1]&cpage=$matches[2]',
			'top'
		);
	}
}