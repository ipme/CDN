<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action('admin_init', 'zm_icon_init');
function zm_icon_init() {
	$zm_icon_taxonomies = get_taxonomies();
	if (is_array($zm_icon_taxonomies)) {
		foreach ($zm_icon_taxonomies as $zm_icon_taxonomy) {
			add_action($zm_icon_taxonomy.'_add_form_fields', 'zm_add_icon_texonomy_field');
			add_action($zm_icon_taxonomy.'_edit_form_fields', 'zm_icon_edit_texonomy_field');
		}
	}
}

// 新建分类添加图标字段表单
function zm_add_icon_texonomy_field() {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}

	echo '<div class="form-field">
		<label for="taxonomy_icon">' . __('分类图标', 'begin') . '</label>
		<input type="text" name="taxonomy_icon" id="taxonomy_icon" value="" />
		<br/>
		<span class="cat-words">输入图标字体代码</span><br />
	</div>';
}

// 在分类编辑添加图标字段表单
function zm_icon_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}

	$icon_code = zm_taxonomy_icon_code( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_icon">' . __('分类图标', 'begin') . '</label></th>
		<td>' . zm_taxonomy_icon_code( $taxonomy->term_id, 'medium', TRUE ) . '<br/><input type="text" name="taxonomy_icon" id="taxonomy_icon" value="'.$icon_code.'" /><br />
		<span class="cat-words">输入图标字体代码</span><br />
		</td><br />
	</tr>';
}

// 保存分类图标字段
add_action('edit_term','zm_save_taxonomy_icon');
add_action('create_term','zm_save_taxonomy_icon');
function zm_save_taxonomy_icon($term_id) {
	if(isset($_POST['taxonomy_icon']))
		update_option('zm_taxonomy_icon'.$term_id, $_POST['taxonomy_icon'], NULL);
}

// 获取图标
function zm_icon_get_attachment_id_by_code($icon_name) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $icon_name);
	$id = $wpdb->get_var($query);
	return (!empty($id)) ? $id : NULL;
}

// 获取指定term_id分类图标代码
function zm_taxonomy_icon_code($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tag())
			$term_id = get_query_var('tag_id');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}

	$taxonomy_icon_code = get_option('zm_taxonomy_icon'.$term_id);
	if(!empty($taxonomy_icon_code)) {
		$attachment_id = zm_icon_get_attachment_id_by_code($taxonomy_icon_code);
		if(!empty($attachment_id)) {
		$taxonomy_icon_code = $taxonomy_icon_code[0];
		}
	}
	return $taxonomy_icon_code;
}