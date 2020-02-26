<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// 删除数据
$one_delete =
array(
	"post_img" => array("name" => "post_img"),
	"hot" => array("name" => "hot"),
	"cms_top" => array(	"name" => "cms_top"),
	"cat_top" => array("name" => "cat_top"),
	"show" => array("name" => "show"),
	"go_url" => array("name" => "show_url"),
	"direct" => array("name" => "direct"),
	"direct_btn" => array("name" => "direct_btn"),
	"link_inf" => array("name" => "link_inf"),
	"from" => array("name" => "from"),
	"copyright" => array("name" => "copyright"),
	"file_os" => array("name" => "file_os"),
	"file_inf" => array("name" => "file_inf"),
	"small" => array("name" => "small"),
	"product" => array("name" => "product"),
	"pricex" => array("name" => "pricex"),
	"pricey" => array("name" => "pricey"),
	"taourl" => array("name" => "taourl"),
	"discount" => array("name" => "discount"),
	"discounturl" => array("name" => "discounturl"),
	"sites_link" => array("name" => "sites_link"),
	"sites_img_link" => array("name" => "sites_img_link"),
	"order" => array("name" => "sites_order"),
	"show_order" => array("name" => "show_order"),
	"guide_img" => array("name" => "guide_img"),
	"group_slider_url" => array("name" => "group_slider_url"),
	"header_img" => array("name" => "header_img"),
	"small_img" => array("name" => "small_img"),
	"pr_b" => array("name" => "pr_b"),
	"pr_a" => array("name" => "pr_a"),
	"pr_c" => array("name" => "pr_c"),
	"pr_d" => array("name" => "pr_d"),
	"pr_e" => array("name" => "pr_e"),
	"pr_f" => array("name" => "pr_f"),
	"mark" => array("name" => "mark"),
	"baidu_pan_btn" => array("name" => "baidu_pan_btn"),
	"down_local_btn" => array("name" => "down_local_btn"),
	"down_official_btn" => array("name" => "down_official_btn"),
	"sidebar_l" => array("name" => "sidebar_l"),
	"menu_post" => array("name" => "menu_post"),
	"no_abstract" => array("name" => "no_abstract"),
	"slide_title" => array("name" => "slide_title"),
	"down_link_much" => array("name" => "down_link_much"),
	"no_slide_title" => array("name" => "no_slide_title"),
	"special" => array("name" => "special"),
	"user_only" => array("name" => "user_only"),
	"special_img_n" => array("name" => "special_img_n"),
	"no_today" => array("name" => "no_today"),
	"related_special_id" => array("name" => "related_special_id"),
	"gw_ico" => array("name" => "gw_ico"),
	"gw_img" => array("name" => "gw_img"),
	"gw_title" => array("name" => "gw_title"),
	"gw_content" => array("name" => "gw_content"),
	"gw_link" => array("name" => "gw_link"),
	"not_tts" => array("name" => "not_tts"),
	"shot_img" => array("name" => "shot_img"),
	"tool_ico" => array("name" => "tool_ico"),
	"tool_button" => array("name" => "tool_button"),
	"tool_url" => array("name" => "tool_url"),
	"not_more" => array("name" => "not_more"),
);

function save_one_delete($post_id) {
	global $post, $one_delete;
	foreach ($one_delete as $meta_box) {
		$data = $_POST[$meta_box['name'] . ''];
		if ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('save_post', 'save_one_delete');