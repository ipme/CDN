<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function search_results() {
global $wp_query;
echo get_search_query() . '<i class="be be-arrowright"></i>';
echo _e('找到', 'begin');
echo '&nbsp;'. $wp_query->found_posts . '&nbsp;';
echo _e('个相关内容', 'begin');
}