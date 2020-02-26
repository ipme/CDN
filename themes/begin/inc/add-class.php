<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if (zm_get_option('lightbox_on')) {
add_filter('the_content', 'pirobox_auto', 99);
add_filter('the_excerpt', 'pirobox_auto', 99);
}
function pirobox_auto($content) {
	global $post;
	$pattern = "/<a(.*?)href=('|\")([A-Za-z0-9\/_\.\~\:-]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?)>/i";
	$replacement = '<a$1href=$2$3$4$5$6 data-fancybox="gallery">';
	$content = preg_replace($pattern, $replacement, $content);
	return $content;
}

if (zm_get_option('lazy_e')) {
	add_filter ('the_content', 'lazyload');
	function lazyload($content) {
		$loadimg_url = '';
		if(!is_feed()||!is_robots) {
			$content=preg_replace('/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i',"<img\$1data-original=\"\$2\" src=\"$loadimg_url\"\$3>\n",$content);
		}
		return $content;
	}
}