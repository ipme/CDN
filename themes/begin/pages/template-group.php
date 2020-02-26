<?php
/*
Template Name: 公司主页
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>
<style type="text/css">
body {
	background: #fff;
}

#content {
	width: 100%;
	margin: 0 auto;
}

#masthead {
	background: #fff;
}

#primary {
	width: 100%;
	box-shadow: none;
}

#primary .page {
	background: transparent !important;
	padding: 0 !important;
	border: none !important;
	box-shadow: none !important;
}

.bread, .header-sub, .owl-buttons {
	display: none;
}

#slideshow {
	background: #fff;
	margin: -1px auto 0;
}

#menu-box {
	transition-duration: .0s;
}

.ad-site {
	display: none;
}
</style>

<div class="container">
<!-- 幻灯 -->
<?php get_template_part( '/group/group-slider' ); ?>
<div id="group-section">
<?php 
	function group() {
	// 关于
	get_template_part( '/group/group-contact' );
	// 服务
	get_template_part( '/group/group-dean' );
	// 工具
	get_template_part( '/group/group-tool' );
	// 产品
	get_template_part( '/group/group-show' );
	// 项目
	get_template_part( '/group/group-service' );
	// WOO产品
	get_template_part( '/group/group-woo' );
	// 格子图标
	if (zm_get_option('group_ico')) { grid_md_group(); }
	// 简介
	get_template_part( '/group/group-features' );
	// 分类左图
	get_template_part( '/group/group-wd-l' );
	// 分类右图 
	get_template_part( '/group/group-wd-r' );
	// 说明
	get_template_part( '/group/group-explain' );
	// 一栏小工具
	get_template_part( '/group/group-widget-one' );
	// EDD下载
	get_template_part( '/group/group-dow-tab' );
	// 最新文章
	require get_template_directory() . '/group/group-news.php';
	// 三栏小工具
	get_template_part( '/group/group-widget-three' );
	// 新闻资讯A
	require get_template_directory() . '/group/group-cat-a.php';
	// 两栏小工具
	get_template_part( '/group/group-widget-two' );
	// 新闻资讯B
	require get_template_directory() . '/group/group-cat-b.php';
	// 产品案例
	require get_template_directory() . '/group/group-tab.php';
	// 新闻资讯 C
	require get_template_directory() . '/group/group-cat-c.php';
} ?>
<?php group(); ?>
</div>
<div class="clear"></div>

<!-- 滚动 -->
<?php require get_template_directory() . '/group/group-carousel.php'; ?>

</div><!-- container end -->

<?php get_footer(); ?>