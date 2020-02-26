<?php
/**
 * 杂志布局
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<?php 
if (!zm_get_option('slider_l') || (zm_get_option("slider_l") == 'slider_w')) {
	require get_template_directory() . '/template/slider.php';
}

if (zm_get_option('cms_slider_sticky')) {
	echo '<div id="primary-cms">';
}

if (zm_get_option('cms_no_s')) {
	echo '<div id="primary" class="content-area">';
} else {
	echo '<div id="cms-primary" class="content-area">';
 }
echo '<main id="main" class="site-main" role="main">';
if (zm_get_option('slider_l') == 'slider_n') {
	require get_template_directory() . '/template/slider.php';
}
get_template_part( '/cms/cms-top' );
get_template_part( '/cms/cat-special' );
get_template_part( '/cms/cat-cover' );
require get_template_directory() . '/cms/cms-news.php';
get_template_part( '/inc/filter-home' );
get_template_part( '/cms/cms-widget-one' );
require get_template_directory() . '/cms/cms-cat-one-5.php';
require get_template_directory() . '/cms/cms-cat-one-no-img.php';
require get_template_directory() . '/cms/cms-cat-one-10.php';
get_template_part( '/cms/cms-picture' );
get_template_part( '/cms/cms-widget-two' );
require get_template_directory() . '/cms/cms-cat-small.php';
get_template_part( '/cms/cms-video' );
get_template_part( '/cms/cms-tab' );
echo '</main>';
echo '</div>';

if (zm_get_option('cms_no_s')) {
	echo get_sidebar('cms'); 
if (zm_get_option('cms_slider_sticky')) {
	echo '</div>';
}
}else {
	echo '<div class="clear"></div>';
}
echo '<div id="below-main">';
get_template_part( '/cms/cms-show' );
get_template_part( '/cms/cms-tool' );
if (zm_get_option('grid_ico_cms')) { grid_md_cms(); }
get_template_part( '/cms/cms-widget-three' );
require get_template_directory() . '/cms/cms-cat-square.php';
require get_template_directory() . '/cms/cms-cat-grid.php';
get_template_part( '/cms/cms-scrolling' );
require get_template_directory() . '/cms/cms-cat-big.php';
get_template_part( '/cms/cms-tao' );
get_template_part( '/cms/cms-dow-tab' );
get_template_part( '/cms/cms-product' );
require get_template_directory() . '/cms/cms-cat-big-n.php'; 
echo '</div>';
 ?>

<?php get_footer(); ?>