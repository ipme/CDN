<?php
/*
The settings page
*/

function fep_menu_item()
{
	global $fep_settings_page_hook;
	$fep_settings_page_hook = add_submenu_page(
		'options-general.php',
		'投稿设置 Settings',
		'投稿设置',
		'manage_options',
		'front_settings',
		'fep_render_settings_page'
	);
}

add_action('admin_menu', 'fep_menu_item');

function fep_scripts_styles($hook)
{
	global $fep_settings_page_hook;
	if ($fep_settings_page_hook != $hook)
		return;
		wp_enqueue_style( 'options_panel_stylesheet', get_template_directory_uri() . '/inc/tougao/static/options-panel.css', array(), version );
		wp_enqueue_script( 'options_panel_script', get_template_directory_uri() . '/inc/tougao/static/options-panel.js', array('jquery'));
	wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
}

add_action('admin_enqueue_scripts', 'fep_scripts_styles');

function fep_render_settings_page()
{
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2 class="tg"><span class="dashicons dashicons-welcome-write-blog"></span>投稿设置</h2>
		<?php settings_errors(); ?>
		<div class="clearfix paddingtop20">
			<div class="first ninecol">
				<form method="post" action="options.php">
					<?php settings_fields('front_settings'); ?>
					<?php do_meta_boxes('fep_metaboxes', 'advanced', null); ?>
					<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); ?>
					<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false); ?>
				</form>
			</div>
		</div>
	</div>
<?php }

function fep_create_options()
{
	add_settings_section('fep_restrictions_section', null, null, 'front_settings');
	add_settings_section('fep_role_section', null, null, 'front_settings');
	add_settings_section('fep_misc_section', null, null, 'front_settings');

	add_settings_field(
		'tag_count', '', 'fep_render_settings_field', 'front_settings', 'fep_restrictions_section',
		array(
			'title' => '标签数',
			'desc'  => '标签数量要求',
			'id'    => 'tag_count',
			'type'  => 'multitext',
			'items' => array(
				'min_tags' => '最少',
				'max_tags' => '最多',
			),
			'group' => 'fep_post_restrictions'
		)
	);

	add_settings_field(
		'max_links', '', 'fep_render_settings_field', 'front_settings', 'fep_restrictions_section',
		array(
			'title' => '文章中允许的超链接数',
			'desc'  => '',
			'id'    => 'max_links',
			'type'  => 'text',
			'group' => 'fep_post_restrictions'
		)
	);

	add_settings_field(
		'thumbnail_required', '', 'fep_render_settings_field', 'front_settings', 'fep_restrictions_section',
		array(
			'title' => '允许特色图像',
			'desc'  => '',
			'id'    => 'thumbnail_required',
			'type'  => 'checkbox',
			'group' => 'fep_misc'
		)
	);

	$user_roles = array(
		0                      => '没有角色',
		'update_core'          => '管理员',
		'moderate_comments'    => '编辑',
		'edit_published_posts' => '作者',
		'edit_posts'           => '投稿者',
		'read'                 => '订阅者',
	);

	add_settings_field(
		'no_check', '', 'fep_render_settings_field', 'front_settings', 'fep_role_section',
		array(
			'title'   => '禁用检查',
			'desc'    => '等于或者高于当前选择角色时，将不检查',
			'id'      => 'no_check',
			'type'    => 'select',
			'options' => $user_roles,
			'group'   => 'fep_role_settings'
		)
	);

	add_settings_field(
		'instantly_publish', '', 'fep_render_settings_field', 'front_settings', 'fep_role_section',
		array(
			'title'   => '立即发布贴子',
			'desc'    => '等于或者高于当前选择角色时，文章将立即发表无需审核',
			'id'      => 'instantly_publish',
			'type'    => 'select',
			'options' => $user_roles,
			'group'   => 'fep_role_settings'
		)
	);

	$media_roles = $user_roles;
	$media_roles[0] = __('所有人', 'frontend-publishing');
	add_settings_field(
		'enable_media', '', 'fep_render_settings_field', 'front_settings', 'fep_role_section',
		array(
			'title'   => '显示媒体按钮',
			'desc'    => '等于或者高于当前选择角色时，将显示媒体按钮',
			'id'      => 'enable_media',
			'type'    => 'select',
			'options' => $media_roles,
			'group'   => 'fep_role_settings'
		)
	);

	add_settings_field(
		'nofollow_body_links', '', 'fep_render_settings_field', 'front_settings', 'fep_misc_section',
		array(
			'title' => '文章中的链接添加Nofollow',
			'desc'  => '文章中所有链接自动添加nofollow属性',
			'id'    => 'nofollow_body_links',
			'type'  => 'checkbox',
			'group' => 'fep_misc'
		)
	);

	add_settings_field(
		'disable_login_redirection', '', 'fep_render_settings_field', 'front_settings', 'fep_misc_section',
		array(
			'title' => '未登录时访问投稿页面',
			'desc'  => '提示登录后方可投稿',
			'id'    => 'disable_login_redirection',
			'type'  => 'checkbox',
			'group' => 'fep_misc'
		)
	);

	add_settings_field(
		'posts_per_page', '', 'fep_render_settings_field', 'front_settings', 'fep_misc_section',
		array(
			'title' => '作者所有文章页面显示篇数',
			'desc'  => '新建页面添加短代码 [fep_article_list]将显示当前登录者所有的文章',
			'id'    => 'posts_per_page',
			'type'  => 'text',
			'group' => 'fep_misc'
		)
	);

	// Finally, we register the fields with WordPress
	register_setting('front_settings', 'fep_post_restrictions', 'fep_settings_validation');
	register_setting('front_settings', 'fep_role_settings', 'fep_settings_validation');
	register_setting('front_settings', 'fep_misc', 'fep_settings_validation');

} // end sandbox_initialize_theme_options 
add_action('admin_init', 'fep_create_options');

function fep_settings_validation($input) {
	return $input;
}

function fep_add_meta_boxes() {
	add_meta_box("fep_post_restrictions_metabox", '发表文章设置', "fep_metaboxes_callback", "fep_metaboxes", 'advanced', 'default', array('settings_section' => 'fep_restrictions_section'));
	add_meta_box("fep_role_settings_metabox", '角色设置', "fep_metaboxes_callback", "fep_metaboxes", 'advanced', 'default', array('settings_section' => 'fep_role_section'));
	add_meta_box("fep_misc_metabox", '其它设置', "fep_metaboxes_callback", "fep_metaboxes", 'advanced', 'default', array('settings_section' => 'fep_misc_section'));
}

add_action('admin_init', 'fep_add_meta_boxes');

function fep_metaboxes_callback($post, $args) {
	do_settings_fields("front_settings", $args['args']['settings_section']);
	submit_button('保存更改', 'secondary');
}

function fep_render_settings_field($args) {
	$option_value = get_option($args['group']);
	?>
	<div class="row clearfix">
		<div class="col colone"><?php echo $args['title']; ?></div>
		<div class="col coltwo">
			<?php if ($args['type'] == 'text'): ?>
				<input type="text" id="<?php echo $args['id'] ?>"
					   name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>"
					   value="<?php echo (isset($option_value[ $args['id'] ])) ? esc_attr($option_value[ $args['id'] ]) : ''; ?>">
			<?php elseif ($args['type'] == 'select'): ?>
				<select name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>" id="<?php echo $args['id']; ?>">
					<?php foreach ($args['options'] as $key => $option) { ?>
						<option <?php if (isset($option_value[ $args['id'] ])) selected($option_value[ $args['id'] ], $key);
						echo 'value="' . $key . '"'; ?>><?php echo $option; ?></option><?php } ?>
				</select>
			<?php elseif ($args['type'] == 'checkbox'): ?>
				<input type="hidden" name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>" value="0"/>
				<input type="checkbox" name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>"
					   id="<?php echo $args['id']; ?>"
					   value="true" <?php if (isset($option_value[ $args['id'] ])) checked($option_value[ $args['id'] ], 'true'); ?> />
			<?php elseif ($args['type'] == 'textarea'): ?>
				<textarea name="<?php echo $args['group'] . '[' . $args['id'] . ']'; ?>"
						  type="<?php echo $args['type']; ?>" cols=""
						  rows=""><?php echo isset($option_value[ $args['id'] ]) ? stripslashes(esc_textarea($option_value[ $args['id'] ])) : ''; ?></textarea>
			<?php elseif ($args['type'] == 'multicheckbox'):
				foreach ($args['items'] as $key => $checkboxitem):
					?>
					<input type="hidden" name="<?php echo $args['group'] . '[' . $args['id'] . '][' . $key . ']'; ?>"
						   value="0"/>
					<label
						for="<?php echo $args['group'] . '[' . $args['id'] . '][' . $key . ']'; ?>"><?php echo $checkboxitem; ?></label>
					<input type="checkbox" name="<?php echo $args['group'] . '[' . $args['id'] . '][' . $key . ']'; ?>"
						   id="<?php echo $args['group'] . '[' . $args['id'] . '][' . $key . ']'; ?>" value="1"
						   <?php if ($key == 'reason'){ ?>checked="checked" disabled="disabled"<?php } else {
						checked($option_value[ $args['id'] ][ $key ]);
					} ?> />
				<?php endforeach; ?>
			<?php elseif ($args['type'] == 'multitext'):
				foreach ($args['items'] as $key => $textitem):
					?>
					<label for="<?php echo $args['group'] . '[' . $key . ']'; ?>"><?php echo $textitem; ?></label>
					<input type="text" id="<?php echo $args['group'] . '[' . $key . ']'; ?>" class="multitext"
						   name="<?php echo $args['group'] . '[' . $key . ']'; ?>"
						   value="<?php echo (isset($option_value[ $key ])) ? esc_attr($option_value[ $key ]) : ''; ?>">
				<?php endforeach; endif; ?>
		</div>
		<div class="col colthree">
			<small><?php echo $args['desc'] ?></small>
		</div>
	</div>
	<?php
}
?>