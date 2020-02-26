<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
if (!zm_get_option('layout') || (zm_get_option('layout') == 'blog')) {
	get_template_part( 'template/blog');
}
if (zm_get_option('layout') == 'img') {
	get_template_part( 'template/grid');
}
if (zm_get_option('layout') == 'grid') {
	get_template_part( 'template/grid-cat');
}
if (zm_get_option('layout') == 'cms') {
	get_template_part( 'template/cms');
}
if (zm_get_option('layout') == 'group') {
	get_template_part( 'template/group');
}
?>