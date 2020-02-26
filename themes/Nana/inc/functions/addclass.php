<?php
add_filter('the_content', 'pirobox_auto', 99);
add_filter('the_excerpt', 'pirobox_auto', 99);
function pirobox_auto($content) {
	global $post;
	$pattern = "/<a(.*?)href=('|\")([A-Za-z0-9\/_\.\~\:-]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?)>/i";
	$replacement = '<a$1href=$2$3$4$5$6 class="cboxElement" rel="example_group"'.$post->ID.'>';
	$content = preg_replace($pattern, $replacement, $content);
	return $content;
}
?>