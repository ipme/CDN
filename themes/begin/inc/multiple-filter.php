<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if (zm_get_option('filters_a')) {
	// 筛选标签A
	add_action( 'init', 'create_filtersa' );
	function create_filtersa() {
	$labels = array(
		'name' => '筛选A',
		'singular_name' => 'filtersa' ,
		'search_items' => '搜索标签',
		'edit_item' => '编辑标签',
		'update_item' => '更新标签',
		'add_new_item' => '添加新标签',
		);

	register_taxonomy( 'filtersa',array( 'post','picture','video','tao','show' ),array(
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'filtersa' ),
		'labels' => $labels
		));
	}
}

if (zm_get_option('filters_b')) {
	// 筛选标签B
	add_action( 'init', 'create_filtersb' );
	function create_filtersb() {
	$labels = array(
		'name' => '筛选B',
		'singular_name' => 'filtersb' ,
		'search_items' => '搜索标签',
		'edit_item' => '编辑标签',
		'update_item' => '更新标签',
		'add_new_item' => '添加新标签',
		);

	register_taxonomy( 'filtersb',array( 'post','picture','video','tao','show' ),array(
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'filtersb' ),
		'labels' => $labels
		));
	}
}

if (zm_get_option('filters_c')) {
	// 筛选标签C
	add_action( 'init', 'create_filtersc' );
	function create_filtersc() {
	$labels = array(
		'name' => '筛选C',
		'singular_name' => 'filtersc' ,
		'search_items' => '搜索标签',
		'edit_item' => '编辑标签',
		'update_item' => '更新标签',
		'add_new_item' => '添加新标签',
		);

	register_taxonomy( 'filtersc',array( 'post','picture','video','tao','show' ),array(
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'filtersc' ),
		'labels' => $labels
		));
	}
}

if (zm_get_option('filters_d')) {
	// 筛选标签D
	add_action( 'init', 'create_filtersd' );
	function create_filtersd() {
	$labels = array(
		'name' => '筛选D',
		'singular_name' => 'filtersd' ,
		'search_items' => '搜索标签',
		'edit_item' => '编辑标签',
		'update_item' => '更新标签',
		'add_new_item' => '添加新标签',
		);

	register_taxonomy( 'filtersd',array( 'post','picture','video','tao','show' ),array(
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'filtersd' ),
		'labels' => $labels
		));
	}
}

if (zm_get_option('filters_e')) {
	// 筛选标签E
	add_action( 'init', 'create_filterse' );
	function create_filterse() {
	$labels = array(
		'name' => '筛选E',
		'singular_name' => 'filterse' ,
		'search_items' => '搜索标签',
		'edit_item' => '编辑标签',
		'update_item' => '更新标签',
		'add_new_item' => '添加新标签',
		);

	register_taxonomy( 'filterse',array( 'post','picture','video','tao','show' ),array(
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'filterse' ),
		'labels' => $labels
		));
	}
}