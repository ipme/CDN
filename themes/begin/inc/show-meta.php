<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$new_meta_show_boxes =
array(
	"custom_title" => array(
		"name" => "custom_title",
		"std" => "",
		"title" => "SEO自定义文章标题",
		"type"=>"text"),

	"description" => array(
		"name" => "description",
		"std" => "",
		"title" => "文章描述，留空则自动截取首段一定字数作为文章描述",
		"type"=>"textarea"),

	"keywords" => array(
		"name" => "keywords",
		"std" => "",
		"title" => "文章关键词，多个关键词用半角逗号隔开",
		"type"=>"text"),

	"down_link_much" => array(
		"name" => "down_link_much",
		"std" => "",
		"title" => "多栏按钮",
		"type"=>"checkbox"),
);

// 面板内容
function new_meta_show_boxes() {
	global $post, $new_meta_show_boxes;
	//获取保存
	foreach ($new_meta_show_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'] . '', true);
		if ($meta_box_value != "")
		//将默认值替换为已保存的值
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		//选择类型输出不同的html代码
		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'radio':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				$counter = 1;
				foreach ($meta_box['buttons'] as $radiobutton) {
					$checked = "";
					if (isset($meta_box['std']) && $meta_box['std'] == $counter) {
						$checked = 'checked = "checked"';
					}
					echo '<input ' . $checked . ' type="radio" class="kcheck" value="' . $counter . '" name="' . $meta_box['name'] . '_value"/>' . $radiobutton;
					$counter++;
				}
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><label><input type="checkbox" class="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '' . $meta_box['title'] . '</label><br />';
				break;
			}
		}
}
function create_meta_show_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('new-meta-boxes', '产品设置', 'new_meta_show_boxes', 'show', 'normal', 'high');
	}
}
function save_show_postdata($post_id) {
	global $post, $new_meta_show_boxes;
	foreach ($new_meta_show_boxes as $meta_box) {
		if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = $_POST[$meta_box['name'] . ''];
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_meta_show_box');
add_action('save_post', 'save_show_postdata');

// 产品幻灯1
$new_meta_show_h_a_boxes =
array(
	"s_a_img_d" => array(
		"name" => "s_a_img_d",
		"std" => "",
		"title" => "大背景图地址",
		"type"=>"text"),

	"s_a_img_x" => array(
		"name" => "s_a_img_x",
		"std" => "",
		"title" => "浮动层小图地址",
		"type"=>"text"),

	"s_a_t_a" => array(
		"name" => "s_a_t_a",
		"std" => "",
		"title" => "第一行文字",
		"type"=>"text"),

	"s_a_t_b" => array(
		"name" => "s_a_t_b",
		"std" => "",
		"title" => "第二行文字（大字）",
		"type"=>"text"),

	"s_a_t_c" => array(
		"name" => "s_a_t_c",
		"std" => "",
		"title" => "第三行文字",
		"type"=>"text"),

	"s_a_n_b" => array(
		"name" => "s_a_n_b",
		"std" => "",
		"title" => "按钮名称",
		"type"=>"text"),

	"s_a_n_b_l" => array(
		"name" => "s_a_n_b_l",
		"std" => "",
		"title" => "按钮链接",
		"type"=>"text"),
);

// 面板内容
function new_meta_show_h_a_boxes() {
	global $post, $new_meta_show_h_a_boxes;
	//获取保存
	foreach ($new_meta_show_h_a_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'] . '', true);
		if ($meta_box_value != "")
		//将默认值替换为已保存的值
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		//选择类型输出不同的html代码
		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'radio':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				$counter = 1;
				foreach ($meta_box['buttons'] as $radiobutton) {
					$checked = "";
					if (isset($meta_box['std']) && $meta_box['std'] == $counter) {
						$checked = 'checked = "checked"';
					}
					echo '<input ' . $checked . ' type="radio" class="kcheck" value="' . $counter . '" name="' . $meta_box['name'] . '_value"/>' . $radiobutton;
					$counter++;
				}
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><input type="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '<label>' . $meta_box['title'] . '</label><br />';
				break;
			}
		}
}
function create_show_h_a_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('create_show_h_a_meta_box', '产品头部图片设置', 'new_meta_show_h_a_boxes', 'show', 'normal', 'high');
	}
}
function save_show_h_a_postdata($post_id) {
	global $post, $new_meta_show_h_a_boxes;
	foreach ($new_meta_show_h_a_boxes as $meta_box) {
		if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = $_POST[$meta_box['name'] . ''];
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_show_h_a_meta_box');
add_action('save_post', 'save_show_h_a_postdata');

// 产品简介a
$new_meta_show_i_a_boxes =
array(
	"ss_a_a" => array(
		"name" => "ss_a_a",
		"std" => "",
		"title" => "第一行",
		"type"=>"text"),

	"ss_a_b" => array(
		"name" => "ss_a_b",
		"std" => "",
		"title" => "第二行",
		"type"=>"text"),

	"ss_a_c" => array(
		"name" => "ss_a_c",
		"std" => "",
		"title" => "第三行",
		"type"=>"text"),

	"ss_a_d" => array(
		"name" => "ss_a_d",
		"std" => "",
		"title" => "第四行",
		"type"=>"text"),

	"ss_a_e" => array(
		"name" => "ss_a_e",
		"std" => "",
		"title" => "图片地址",
		"type"=>"text"),
);

// 面板内容
function new_meta_show_i_a_boxes() {
	global $post, $new_meta_show_i_a_boxes;
	//获取保存
	foreach ($new_meta_show_i_a_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'] . '', true);
		if ($meta_box_value != "")
		//将默认值替换为已保存的值
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		//选择类型输出不同的html代码
		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'radio':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				$counter = 1;
				foreach ($meta_box['buttons'] as $radiobutton) {
					$checked = "";
					if (isset($meta_box['std']) && $meta_box['std'] == $counter) {
						$checked = 'checked = "checked"';
					}
					echo '<input ' . $checked . ' type="radio" class="kcheck" value="' . $counter . '" name="' . $meta_box['name'] . '_value"/>' . $radiobutton;
					$counter++;
				}
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><input type="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '<label>' . $meta_box['title'] . '</label><br />';
				break;
			}
		}
}
function create_show_i_a_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('create_show_i_a_meta_box', '产品简介设置-1', 'new_meta_show_i_a_boxes', 'show', 'normal', 'high');
	}
}
function save_show_i_a_postdata($post_id) {
	global $post, $new_meta_show_i_a_boxes;
	foreach ($new_meta_show_i_a_boxes as $meta_box) {
		if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = $_POST[$meta_box['name'] . ''];
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_show_i_a_meta_box');
add_action('save_post', 'save_show_i_a_postdata');


// 产品简介b
$new_meta_show_i_b_boxes =
array(
	"ss_b_a" => array(
		"name" => "ss_b_a",
		"std" => "",
		"title" => "第一行",
		"type"=>"text"),

	"ss_b_b" => array(
		"name" => "ss_b_b",
		"std" => "",
		"title" => "第二行",
		"type"=>"text"),

	"ss_b_c" => array(
		"name" => "ss_b_c",
		"std" => "",
		"title" => "第三行",
		"type"=>"text"),

	"ss_b_d" => array(
		"name" => "ss_b_d",
		"std" => "",
		"title" => "第四行",
		"type"=>"text"),

	"ss_b_e" => array(
		"name" => "ss_b_e",
		"std" => "",
		"title" => "图片地址",
		"type"=>"text"),
);

// 面板内容
function new_meta_show_i_b_boxes() {
	global $post, $new_meta_show_i_b_boxes;
	//获取保存
	foreach ($new_meta_show_i_b_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'] . '', true);
		if ($meta_box_value != "")
		//将默认值替换为已保存的值
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		//选择类型输出不同的html代码
		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'radio':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				$counter = 1;
				foreach ($meta_box['buttons'] as $radiobutton) {
					$checked = "";
					if (isset($meta_box['std']) && $meta_box['std'] == $counter) {
						$checked = 'checked = "checked"';
					}
					echo '<input ' . $checked . ' type="radio" class="kcheck" value="' . $counter . '" name="' . $meta_box['name'] . '_value"/>' . $radiobutton;
					$counter++;
				}
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><input type="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '<label>' . $meta_box['title'] . '</label><br />';
				break;
			}
		}
}
function create_show_i_b_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('create_show_i_b_meta_box', '产品简介设置-2', 'new_meta_show_i_b_boxes', 'show', 'normal', 'high');
	}
}
function save_show_i_b_postdata($post_id) {
	global $post, $new_meta_show_i_b_boxes;
	foreach ($new_meta_show_i_b_boxes as $meta_box) {
		if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = $_POST[$meta_box['name'] . ''];
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_show_i_b_meta_box');
add_action('save_post', 'save_show_i_b_postdata');

// 产品简介c
$new_meta_show_i_c_boxes =
array(
	"ss_c_a" => array(
		"name" => "ss_c_a",
		"std" => "",
		"title" => "第一行",
		"type"=>"text"),

	"ss_c_b" => array(
		"name" => "ss_c_b",
		"std" => "",
		"title" => "第二行",
		"type"=>"text"),

	"ss_c_c" => array(
		"name" => "ss_c_c",
		"std" => "",
		"title" => "第三行",
		"type"=>"text"),

	"ss_c_d" => array(
		"name" => "ss_c_d",
		"std" => "",
		"title" => "第四行",
		"type"=>"text"),

	"ss_c_e" => array(
		"name" => "ss_c_e",
		"std" => "",
		"title" => "图片地址",
		"type"=>"text"),
);

// 面板内容
function new_meta_show_i_c_boxes() {
	global $post, $new_meta_show_i_c_boxes;
	//获取保存
	foreach ($new_meta_show_i_c_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'] . '', true);
		if ($meta_box_value != "")
		//将默认值替换为已保存的值
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		//选择类型输出不同的html代码
		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'radio':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				$counter = 1;
				foreach ($meta_box['buttons'] as $radiobutton) {
					$checked = "";
					if (isset($meta_box['std']) && $meta_box['std'] == $counter) {
						$checked = 'checked = "checked"';
					}
					echo '<input ' . $checked . ' type="radio" class="kcheck" value="' . $counter . '" name="' . $meta_box['name'] . '_value"/>' . $radiobutton;
					$counter++;
				}
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><input type="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '<label>' . $meta_box['title'] . '</label><br />';
				break;
			}
		}
}
function create_show_i_c_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('create_show_i_c_meta_box', '产品简介设置-3', 'new_meta_show_i_c_boxes', 'show', 'normal', 'high');
	}
}
function save_show_i_c_postdata($post_id) {
	global $post, $new_meta_show_i_c_boxes;
	foreach ($new_meta_show_i_c_boxes as $meta_box) {
		if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = $_POST[$meta_box['name'] . ''];
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_show_i_c_meta_box');
add_action('save_post', 'save_show_i_c_postdata');

// 产品简介d
$new_meta_show_i_d_boxes =
array(
	"ss_d_a" => array(
		"name" => "ss_d_a",
		"std" => "",
		"title" => "第一行",
		"type"=>"text"),

	"ss_d_b" => array(
		"name" => "ss_d_b",
		"std" => "",
		"title" => "第二行",
		"type"=>"text"),

	"ss_d_c" => array(
		"name" => "ss_d_c",
		"std" => "",
		"title" => "第三行",
		"type"=>"text"),

	"ss_d_d" => array(
		"name" => "ss_d_d",
		"std" => "",
		"title" => "第四行",
		"type"=>"text"),

	"ss_d_e" => array(
		"name" => "ss_d_e",
		"std" => "",
		"title" => "图片地址",
		"type"=>"text"),
);

// 面板内容
function new_meta_show_i_d_boxes() {
	global $post, $new_meta_show_i_d_boxes;
	//获取保存
	foreach ($new_meta_show_i_d_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'] . '', true);
		if ($meta_box_value != "")
		//将默认值替换为已保存的值
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		//选择类型输出不同的html代码
		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'radio':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				$counter = 1;
				foreach ($meta_box['buttons'] as $radiobutton) {
					$checked = "";
					if (isset($meta_box['std']) && $meta_box['std'] == $counter) {
						$checked = 'checked = "checked"';
					}
					echo '<input ' . $checked . ' type="radio" class="kcheck" value="' . $counter . '" name="' . $meta_box['name'] . '_value"/>' . $radiobutton;
					$counter++;
				}
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><input type="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '<label>' . $meta_box['title'] . '</label><br />';
				break;
			}
		}
}
function create_show_i_d_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('create_show_i_d_meta_box', '产品简介设置-4', 'new_meta_show_i_d_boxes', 'show', 'normal', 'high');
	}
}
function save_show_i_d_postdata($post_id) {
	global $post, $new_meta_show_i_d_boxes;
	foreach ($new_meta_show_i_d_boxes as $meta_box) {
		if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = $_POST[$meta_box['name'] . ''];
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_show_i_d_meta_box');
add_action('save_post', 'save_show_i_d_postdata');

// 标题及其它
$new_meta_show_q_boxes =
array(
	"s_j_t" => array(
		"name" => "s_j_t",
		"std" => "",
		"title" => "简介标题",
		"type"=>"text"),

	"s_j_e" => array(
		"name" => "s_j_e",
		"std" => "",
		"title" => "简介描述",
		"type"=>"text"),

	"s_c_t" => array(
		"name" => "s_c_t",
		"std" => "正文标题",
		"title" => "正文标题",
		"type"=>"text"),

	"s_f_t" => array(
		"name" => "s_f_t",
		"std" => "附加模块标题",
		"title" => "附加模块标题",
		"type"=>"text"),

	"s_f_e" => array(
		"name" => "s_f_e",
		"std" => "",
		"title" => "附加模块内容",
		"type"=>"text"),

	"s_f_n_a" => array(
		"name" => "s_f_n_a",
		"std" => "<i class='be be-stack'></i> 详细查看",
		"title" => "附加模块按钮A文字",
		"type"=>"text"),

	"s_f_n_a_l" => array(
		"name" => "s_f_n_a_l",
		"std" => "",
		"title" => "附加模块按钮A链接",
		"type"=>"text"),

	"s_f_n_b" => array(
		"name" => "s_f_n_b",
		"std" => "<i class='be be-phone'></i> 联系方式",
		"title" => "附加模块按钮b文字",
		"type"=>"text"),

	"s_f_n_b_l" => array(
		"name" => "s_f_n_b_l",
		"std" => "",
		"title" => "附加模块按钮B链接",
		"type"=>"text"),
);

// 面板内容
function new_meta_show_q_boxes() {
	global $post, $new_meta_show_q_boxes;
	//获取保存
	foreach ($new_meta_show_q_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'] . '', true);
		if ($meta_box_value != "")
		//将默认值替换为已保存的值
		$meta_box['std'] = $meta_box_value;
		echo '<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
		//选择类型输出不同的html代码
		switch ($meta_box['type']) {
			case 'title':
				echo '<h4>' . $meta_box['title'] . '</h4>';
			break;
			case 'text':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<span class="form-field"><input type="text" size="40" name="' . $meta_box['name'] . '" value="' . $meta_box['std'] . '" /></span><br />';
			break;
			case 'textarea':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				echo '<textarea id="seo-excerpt" cols="40" rows="2" name="' . $meta_box['name'] . '">' . $meta_box['std'] . '</textarea><br />';
			break;
			case 'radio':
				echo '<h4>' . $meta_box['title'] . '</h4>';
				$counter = 1;
				foreach ($meta_box['buttons'] as $radiobutton) {
					$checked = "";
					if (isset($meta_box['std']) && $meta_box['std'] == $counter) {
						$checked = 'checked = "checked"';
					}
					echo '<input ' . $checked . ' type="radio" class="kcheck" value="' . $counter . '" name="' . $meta_box['name'] . '_value"/>' . $radiobutton;
					$counter++;
				}
			break;
			case 'checkbox':
				if (isset($meta_box['std']) && $meta_box['std'] == 'true') $checked = 'checked = "checked"';
				else $checked = '';
				echo '<br /><input type="checkbox" name="' . $meta_box['name'] . '" value="true"  ' . $checked . ' />';
				echo '<label>' . $meta_box['title'] . '</label><br />';
				break;
			}
		}
}
function create_show_q_meta_box() {
	global $theme_name;
	if (function_exists('add_meta_box')) {
		add_meta_box('create_show_q_meta_box', '标题及其它', 'new_meta_show_q_boxes', 'show', 'normal', 'high');
	}
}
function save_show_q_postdata($post_id) {
	global $post, $new_meta_show_q_boxes;
	foreach ($new_meta_show_q_boxes as $meta_box) {
		if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) {
			return $post_id;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
		$data = $_POST[$meta_box['name'] . ''];
		if (get_post_meta($post_id, $meta_box['name'] . '') == "") add_post_meta($post_id, $meta_box['name'] . '', $data, true);
		elseif ($data != get_post_meta($post_id, $meta_box['name'] . '', true)) update_post_meta($post_id, $meta_box['name'] . '', $data);
		elseif ($data == "") delete_post_meta($post_id, $meta_box['name'] . '', get_post_meta($post_id, $meta_box['name'] . '', true));
	}
}
add_action('admin_menu', 'create_show_q_meta_box');
add_action('save_post', 'save_show_q_postdata');