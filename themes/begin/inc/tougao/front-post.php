<?php
add_action('plugins_loaded', 'fep_load_plugin_textdomain');

function fep_start_output_buffers() {
	ob_start();
}

add_action('init', 'fep_start_output_buffers');

function fep_initialize_options() {
	$activation_flag = get_option('fep_misc');

	if ($activation_flag)
		return;

	$fep_restrictions = array(

		'min_tags'           => 1,
		'max_tags'           => 5,
		'max_links'          => 2
	);

	$fep_roles = array(
		'no_check'          => false,
		'instantly_publish' => false,
		'enable_media'      => false
	);

	$fep_misc = array(
		'nofollow_body_links' => false,
		'posts_per_page'      => 10
	);

	update_option('fep_post_restrictions', $fep_restrictions);
	update_option('fep_role_settings', $fep_roles);
	update_option('fep_misc', $fep_misc);
}

register_activation_hook(__FILE__, 'fep_initialize_options');

function fep_messages() {
	$fep_messages = array(
		'unsaved_changes_warning'      => '您有未保存的更改。继续吗?',
		'confirmation_message'         => '您确定吗?',
		'media_lib_string'             => '选择图像',
		'required_field_error'         => '您可能错过一个或多个必需的字段',
		'general_form_error'           => '提交错误，请再试一次!',
		'too_many_article_links_error' => '文章中有太多的超链接',
		'too_few_tags_error'           => "您还没有添加所需数量的标签",
		'too_many_tags_error'          => '标签太多了',
		'featured_image_error'         => '您需要选择一个特色图像',
	);

	return $fep_messages;
}

/**
 * Removes plugin data before uninstalling
 */
function fep_rollback() {
	wp_deregister_style('fep-style');
	wp_deregister_script('fep-script');
	delete_option('fep_post_restrictions');
	delete_option('fep_role_settings');
	delete_option('fep_misc');
	delete_option('fep_messages');
}

register_uninstall_hook(__FILE__, 'fep_rollback');

/**
 * Enqueue scripts and stylesheets
 */
function fep_enqueue_files($posts) {
	if (!is_main_query() || empty($posts))
		return $posts;

	$found = false;
	foreach ($posts as $post) {
		if (has_shortcode($post->post_content, 'fep_article_list') || has_shortcode($post->post_content, 'fep_submission_form')) {
			$found = true;
			break;
		}
	}

	if ($found) {
		wp_enqueue_style( 'fep-style', get_template_directory_uri() . '/inc/tougao/static/style.css', array(), '1.0', 'all');
		wp_enqueue_script( 'fep-script', get_template_directory_uri() . '/inc/tougao/static/scripts.js', array('jquery'));
		wp_localize_script('fep-script', 'fepajaxhandler', array('ajaxurl' => admin_url('admin-ajax.php')));
		$fep_rules = get_option('fep_post_restrictions');
		$fep_roles = get_option('fep_role_settings');
		$fep_rules['check_required'] = (isset($fep_roles['no_check']) && $fep_roles['no_check'] && current_user_can($fep_roles['no_check'])) ? 0 : 1;
		wp_localize_script('fep-script', 'fep_rules', $fep_rules);
		wp_localize_script('fep-script', 'fep_messages', fep_messages());
		$enable_media = (isset($fep_roles['enable_media']) && $fep_roles['enable_media']) ? current_user_can($fep_roles['enable_media']) : 1;
		if ($enable_media)
			wp_enqueue_media();
	}
	return $posts;
}

add_action('the_posts', 'fep_enqueue_files');


/**
 * Scans content for shortcode.
 */
if (!function_exists('has_shortcode')) {
	function has_shortcode($content, $tag)
	{
		if (stripos($content, '[' . $tag . ']') !== false)
			return true;
		return false;
	}
}

/**
 * Inlcuding modules
*/

include('inc/ajax.php');

include('inc/shortcodes.php');

include('inc/options-panel.php');