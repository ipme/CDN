<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// 小工具
if (function_exists('register_sidebar')){
	register_sidebar( array(
		'name'          => '博客布局侧边栏',
		'id'            => 'sidebar-h',
		'description'   => '显示在首页博客布局侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="title-i"><span></span><span></span><span></span><span></span></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '正文侧边栏',
		'id'            => 'sidebar-s',
		'description'   => '显示在文章正文及页面侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="title-i"><span></span><span></span><span></span><span></span></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '分类归档侧边栏',
		'id'            => 'sidebar-a',
		'description'   => '显示在文章归档页、搜索、404页侧边栏 ',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="title-i"><span></span><span></span><span></span><span></span></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志布局侧边栏',
		'id'            => 'cms-s',
		'description'   => '只显示在杂志布局侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="title-i"><span></span><span></span><span></span><span></span></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志单栏小工具',
		'id'            => 'cms-one',
		'description'   => '显示在首页CMS杂志布局',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="title-i"><span></span><span></span><span></span><span></span></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志两栏小工具',
		'id'            => 'cms-two',
		'description'   => '显示在首页CMS杂志布局',
		'before_widget' => '<div class="xl2"><aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title"><span class="title-i"><span></span><span></span><span></span><span></span></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志三栏小工具',
		'id'            => 'cms-three',
		'description'   => '显示在首页CMS杂志布局',
		'before_widget' => '<div class="xl3"><aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title"><span class="title-i"><span></span><span></span><span></span><span></span></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '正文底部小工具',
		'id'            => 'sidebar-e',
		'description'   => '显示在正文底部',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="s-icon"></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '公司主页“一栏”小工具',
		'id'            => 'group-one',
		'description'   => '显示在公司主页布局',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '公司主页“两栏”小工具',
		'id'            => 'group-two',
		'description'   => '显示在公司主页布局',
		'before_widget' => '<div class="xl2"><aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '公司主页“三栏”小工具',
		'id'            =>  'group-three',
		'description'   => '显示在公司主页布局',
		'before_widget' => '<div class="xl3"><aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '头部小工具',
		'id'            => 'header-widget',
		'description'   => '显示在页脚',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="s-icon"></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '页脚小工具',
		'id'            => 'sidebar-f',
		'description'   => '显示在页脚',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="s-icon"></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '菜单页面',
		'id'            => 'all-cat',
		'description'   => '只适用于菜单页面模板，添加自定义菜单小工具',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '网址侧边栏',
		'id'            => 'favorites',
		'description'   => '只适用于网址分类法页面 ',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="title-i"><span></span><span></span><span></span><span></span></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '网址单栏小工具',
		'id'            => 'favorites-one',
		'description'   => '显示网址页面',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp ms" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

register_nav_menus(
	array(
		'navigation' => '主要菜单',
		'header' => '顶部菜单',
		'mobile' => '移动端菜单',
		'footer' => '移动端底部',
		'search' => '搜索推荐'
	)
);

add_theme_support( 'custom-background' );
add_theme_support( 'post-formats', array(
	'aside', 'image', 'video', 'quote', 'link'
) );
require get_template_directory() . '/inc/loader.php';
require get_template_directory() . '/inc/inc.php';
if (zm_get_option('languages_en')) {
add_action('after_setup_theme', 'begin_theme_setup');
function begin_theme_setup(){
	load_theme_textdomain( 'begin', get_template_directory() . '/languages' );
}
}
if ( ! zm_get_option('wp_title') ) {
	add_theme_support( 'title-tag' );

/** 兼容模式
function theme_slug_render_title() {
    echo '<title>' . wp_title( '|', false, 'right' ) . "</title>\n";
}
add_action( 'wp_head', 'theme_slug_render_title' );
 */
}
if (function_exists( 'is_shop' )) {
	add_theme_support( 'woocommerce' );
}
if (zm_get_option('visual_editor')) {
	add_editor_style( 'css/editor-style.css' );
}

add_theme_support( 'automatic-feed-links' );
show_admin_bar(false);
function default_menu() {
	if (current_user_can('manage_options')) {
		echo '<ul class="nav-menu down-menu"><li><a href="'.home_url().'/wp-admin/nav-menus.php">点此设置主要菜单</a></li></ul>';
	}
}
function default_top_menu() {
	if (current_user_can('manage_options')) {
		echo '<ul class="top-menu"><li><a href="'.home_url().'/wp-admin/nav-menus.php">点此设置顶部菜单</a></li></ul>';
	}
}

function search_menu() {
	if (current_user_can('manage_options')) {
		echo '<ul class="search-menu"><li><a href="'.home_url().'/wp-admin/nav-menus.php">设置搜索推荐</a></li></ul>';
	}
}

if (is_admin()) :
function options_css() {
	wp_enqueue_style( 'options', get_template_directory_uri() . '/css/options.css', array(), version );
	if(get_bloginfo('version') >= 5.3 ) {
		wp_enqueue_style( '53', get_template_directory_uri() . '/css/wp53.css', array(), version );
	}
}
add_action( 'init', 'options_css', 20 );
endif;

function begin_scripts() {
	$my_theme = wp_get_theme();
	$theme_version = $my_theme->get( 'Version' );
	wp_enqueue_style( 'begin-style', get_stylesheet_uri(), array(), esc_attr( $theme_version ) );
	wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts/fonts.css', array(), version );
	if ( zm_get_option('iconfont_url')) {
		wp_enqueue_style( 'iconfontd', 'https:'.zm_get_option('iconfont_url'), array(), version );
	}
	if (zm_get_option('highlight')) {
		if ( is_singular() ) {
			wp_enqueue_style( 'highlight', get_template_directory_uri() . '/css/highlight.css', array(), version );
		}
	}
	if (function_exists( 'is_shop' )) {
		wp_enqueue_style( 'woo', get_template_directory_uri() . '/css/woo.css', array(), version );
	}
	if (function_exists( 'edd_get_actions' )) {
		wp_enqueue_style( 'edd', get_template_directory_uri() . '/css/edd.css', array(), version );
	}
	if (function_exists( 'dwqa_breadcrumb' )) {
		wp_enqueue_style( 'dw', get_template_directory_uri() . '/css/dw.css', array(), version );
	}
	if (function_exists( 'is_bbpress' )) {
		wp_enqueue_style( 'bbpress', get_template_directory_uri() . '/css/bbp.css', array(), version );
	}
	if ( !is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js', array(), '1.10.1', false );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'html5', get_theme_file_uri( '/js/html5.js' ), array(), version, false );
		wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );
		wp_enqueue_script( 'superfish', get_template_directory_uri() . "/js/superfish.js", array(), version, true );
		wp_enqueue_script( 'script', get_template_directory_uri() . '/js/begin-script.js', array(), version, true );
		if (zm_get_option('login')) {
			wp_enqueue_script( 'validate.min', get_template_directory_uri() . '/js/validate.min.js', array(), version, true );
		}
		if (zm_get_option('gb2')) {
			wp_enqueue_script( 'gb2big5', get_template_directory_uri() . "/js/gb2big5.js", array(), version, true );
		}
	}
	wp_enqueue_script( 'owl', get_template_directory_uri() . "/js/owl.carousel.min.js", array(), version, true );
	wp_enqueue_script( 'share.min', get_template_directory_uri() . '/js/jquery.share.min.js', array(), version, true);
	if (zm_get_option('wow_no')) {
		wp_enqueue_script( 'wow', get_template_directory_uri() . '/js/wow.js', array(), '0.1.9', true );
	}
	if (zm_get_option('sidebar_sticky')) {
		wp_enqueue_script( 'sticky', get_template_directory_uri() . '/js/sticky.js', array(), '1.6.0', true );
	}
	wp_enqueue_script( 'jquery-ias', get_template_directory_uri() . '/js/jquery-ias.js', array(), '2.2.1', true );
	wp_enqueue_script( 'lazyload', get_template_directory_uri() . '/js/jquery.lazyload.js', array(), version, true );
	if ( zm_get_option('infinite_post') && !is_singular() && !is_paged() ) {
		wp_enqueue_script( 'infinite-post', get_template_directory_uri() . '/js/infinite-post.js', array(), version, true );
	}
	if ( zm_get_option('infinite_comment') && is_singular() ) {
		wp_enqueue_script( 'infinite-comment', get_template_directory_uri() . '/js/infinite-comment.js', array(), version, true );
	}
	if ( zm_get_option('copyright_pro') && ! current_user_can('level_10') ) {
		wp_enqueue_script( 'copyrightpro', get_template_directory_uri() . '/js/copyrightpro.js', array(), version, false );
	}
	if ( is_singular() ) {
		if (zm_get_option('lightbox_on')) {
		wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/fancybox.js', array(), version, true);
		}
		if (zm_get_option('qq_info')) {
		wp_enqueue_script( 'qqinfo', get_template_directory_uri() . '/js/getqqinfo.js', array(), version, true );
		}
		wp_localize_script( 'script', 'wpl_ajax_url', admin_url() . "admin-ajax.php");
	}
	if (zm_get_option('comment_ajax')) {
		if ( is_singular() ) {
			if (zm_get_option('qt')) {
				wp_enqueue_script( 'comments-ajax-qt', get_template_directory_uri() . '/js/comments-ajax-qt.js', array(), version, true);
			} else {
				wp_enqueue_script( 'comments-ajax', get_template_directory_uri() . '/js/comments-ajax.js', array(), version, true);
			}
		}
	}
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'begin_scripts' );
// 全部结束