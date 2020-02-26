<?php

/**
 * Creates shortcode fep_submission_form
 *
 * @return string: HTML content for the shortcode
 */
function fep_add_new_post() {
	$fep_misc = get_option('fep_misc');
	$fep_roles = get_option('fep_role_settings');
	if (!is_user_logged_in()) {
		if (isset($fep_misc['disable_login_redirection']) && $fep_misc['disable_login_redirection']) {
			return sprintf(
				'您需要 %s 方可投稿。',
				// '<a href="' . wp_login_url(get_permalink()) . '" title="登录">登录</a>'
				'<span class="toubtn show-layer" data-show-layer="login-layer" role="button">'.sprintf(__( '登录', 'begin' )).'</span>'
			);
		} else auth_redirect();
	}

	ob_start();
	require get_template_directory() . '/inc/tougao/inc/submission-form.php';
	return ob_get_clean();
}

add_shortcode('fep_submission_form', 'fep_add_new_post');

/**
 * Creates shortcode fep_article_list
 */
function fep_manage_posts() {
	$fep_misc = get_option('fep_misc');
	if (!is_user_logged_in()) {
		return'您无权查看文章列表。';
	}

	ob_start();
	if (isset($_GET['fep_id']) && isset($_GET['fep_action']) && $_GET['fep_action'] == 'edit') {
		require get_template_directory() . '/inc/tougao/inc/submission-form.php';
	} else {
		require get_template_directory() . '/inc/tougao/inc/post-tabs.php';
	}
	return ob_get_clean();
}

add_shortcode('fep_article_list', 'fep_manage_posts');

?>