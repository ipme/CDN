<?php

/**
 * Matches submitted content against restrictions set in the options panel
 */
function fep_post_has_errors($content) {
	$fep_plugin_options = get_option('fep_post_restrictions');
	$fep_messages = fep_messages();
	$max_links = $fep_plugin_options['max_links'];
	$min_tags = $fep_plugin_options['min_tags'];
	$max_tags = $fep_plugin_options['max_tags'];
	$error_string = '';
	$format = '%s<br/>';

	if ( ($min_tags && empty($content['post_tags']))) {
		$error_string .= sprintf($format, $fep_messages['required_field_error']);
	}

	$tags_array = explode(',', $content['post_tags']);
	$stripped_content = strip_tags($content['post_content']);
	if (substr_count($content['post_content'], '</a>') > $max_links)
		$error_string .= sprintf($format, $fep_messages['too_many_article_links_error']);
	if (!empty($content['post_tags']) && count($tags_array) < $min_tags)
		$error_string .= sprintf($format, $fep_messages['too_few_tags_error']);
	if (count($tags_array) > $max_tags)
		$error_string .= sprintf($format, $fep_messages['too_many_tags_error']);

	if (str_word_count($error_string) < 2)
		return false;
	else
		return $error_string;
}

/**
 * Ajax function for fetching a featured image
 */
function fep_fetch_featured_image() {
	$image_id = $_POST['img'];
	echo wp_get_attachment_image($image_id, array(200, 200));
	die();
}

add_action('wp_ajax_fep_fetch_featured_image', 'fep_fetch_featured_image');

/**
 * Ajax function for deleting a post
 */
function fep_delete_posts() {
	try {
		if (!wp_verify_nonce($_POST['delete_nonce'], 'fepnonce_delete_action'))
			throw new Exception(__('对不起!您没有通过安全检查', 'frontend-publishing'), 1);

		if (!current_user_can('delete_post', $_POST['post_id']))
			throw new Exception(__("您没有权限删除这篇文章", 'frontend-publishing'), 1);

		$result = wp_delete_post($_POST['post_id'], true);
		if (!$result)
			throw new Exception(__("这篇文章不能被删除", 'frontend-publishing'), 1);

		$data['success'] = true;
		$data['message'] = __('这篇文章已被成功删除!', 'frontend-publishing');
	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = $ex->getMessage();
	}
	die(json_encode($data));
}

add_action('wp_ajax_fep_delete_posts', 'fep_delete_posts');
add_action('wp_ajax_nopriv_fep_delete_posts', 'fep_delete_posts');

/**
 * Ajax function for adding a new post.
 */
function fep_process_form_input() {
	$fep_messages = fep_messages();
	try {
		if (!wp_verify_nonce($_POST['post_nonce'], 'fepnonce_action'))
			throw new Exception(
				__("对不起!您没有通过安全检查", 'frontend-publishing'),
				1
			);

		if ($_POST['post_id'] != -1 && !current_user_can('edit_post', $_POST['post_id']))
			throw new Exception(
				__("您没有权限编辑这篇文章。", 'frontend-publishing'),
				1
			);

		$fep_role_settings = get_option('fep_role_settings');
		$fep_misc = get_option('fep_misc');

		if ($fep_role_settings['no_check'] && current_user_can($fep_role_settings['no_check']))
			$errors = false;
		else
			$errors = fep_post_has_errors($_POST);

		if ($errors)
			throw new Exception($errors, 1);

		if ($fep_misc['nofollow_body_links'])
			$post_content = wp_rel_nofollow($_POST['post_content']);
		else
			$post_content = $_POST['post_content'];

		$current_post = empty($_POST['post_id']) ? null : get_post($_POST['post_id']);
		$current_post_date = is_a($current_post, 'WP_Post') ? $current_post->post_date : '';

		$new_post = array(
			'post_title'     => sanitize_text_field($_POST['post_title']),
			'post_category'  => array($_POST['post_category']),
			'tags_input'     => sanitize_text_field($_POST['post_tags']),
			'post_content'   => wp_kses_post($post_content),
			'post_date'      => $current_post_date,
			'comment_status' => get_option('default_comment_status')
		);

		if ($fep_role_settings['instantly_publish'] && current_user_can($fep_role_settings['instantly_publish'])) {
			$post_action = __('published', 'frontend-publishing');
			$new_post['post_status'] = 'publish';
		} else {
			$post_action = __('submitted', 'frontend-publishing');
			$new_post['post_status'] = 'pending';
		}

		if ($_POST['post_id'] != -1) {
			$new_post['ID'] = $_POST['post_id'];
			$post_action = __('updated', 'frontend-publishing');
		}

		$new_post_id = wp_insert_post($new_post, true);
		if (is_wp_error($new_post_id))
			throw new Exception($new_post_id->get_error_message(), 1);

		if ($_POST['featured_img'] != -1)
			set_post_thumbnail($new_post_id, $_POST['featured_img']);

		$data['success'] = true;
		$data['post_id'] = $new_post_id;
		$data['message'] = sprintf(
			'%s<br/><a href="#" id="fep-continue-editing">%s</a><br/>
			<a title="关闭投稿页面" href="javascript:close();">点此关闭</a><br/><br/>
			<button id="fep-submit-post" class="btn-continue" type="button" onclick="renovates()">再写一篇</button><br/>',
			sprintf(__('您的文章已提交!', 'frontend-publishing'), $post_action),
			'继续编辑'
		);
	} catch (Exception $ex) {
		$data['success'] = false;
		$data['message'] = sprintf(
			'<strong>%s</strong><br/>%s',
			$fep_messages['general_form_error'],
			$ex->getMessage()
		);
	}
	die(json_encode($data));
}

add_action('wp_ajax_fep_process_form_input', 'fep_process_form_input');
add_action('wp_ajax_nopriv_fep_process_form_input', 'fep_process_form_input');