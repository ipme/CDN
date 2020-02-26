<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
define('ZM_IMAGE_PLACEHOLDER', get_template_directory_uri()."/img/placeholder.png");

add_action('admin_init', 'cat_cover_init');
function cat_cover_init() {
	$cat_taxonomies = get_taxonomies();
	if (is_array($cat_taxonomies)) {
		foreach ($cat_taxonomies as $cat_taxonomy) {
			add_action($cat_taxonomy.'_add_form_fields', 'cat_add_texonomy_field');
			add_action($cat_taxonomy.'_edit_form_fields', 'cat_edit_texonomy_field');
			add_filter( 'manage_edit-' . $cat_taxonomy . '_columns', 'cat_taxonomy_columns' );
			add_filter( 'manage_' . $cat_taxonomy . '_custom_column', 'cat_taxonomy_column', 10, 3 );
		}
	}
}

function cat_cover_add_style() {
	echo '<style type="text/css" media="screen">
		th.column-thumb_cover {width:60px;}
		.form-field img.taxonomy-cover {border:1px solid #eee;max-width:150px;max-height:150px;}
		.inline-edit-row fieldset .thumb_cover label span.title {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.column-thumb_cover span {width:48px;height:48px;display:inline-block;}
		.inline-edit-row fieldset .thumb_cover img,.column-thumb_cover img {width:48px;height:48px;}
	</style>';
}

// 添加图片字段表单
function cat_add_texonomy_field() {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}

	echo '<div class="form-field">
		<label for="taxonomy_cover">' . __('分类封面', 'begin') . '</label>
		<input type="text" name="taxonomy_cover" id="taxonomy_cover" value="" />
		<br/>
		<button class="cat_upload_cover_button button">' . __('添加封面', 'begin') . '</button>
	</div>'.cat_script();
}

// 在分类编辑添加图片字段表单
function cat_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}

	if (cat_cover_url( $taxonomy->term_id, NULL, TRUE ) == ZM_IMAGE_PLACEHOLDER) 
		$cover_url = "";
	else
		$cover_url = cat_cover_url( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_cover">' . __('分类封面', 'begin') . '</label></th>
		<td><img class="taxonomy-cover" src="' . cat_cover_url( $taxonomy->term_id, 'medium', TRUE ) . '"/><br/><input type="text" name="taxonomy_cover" id="taxonomy_cover" value="'.$cover_url.'" /><br />
		<button class="cat_upload_cover_button button">' . __('添加封面', 'begin') . '</button>
		<button class="cat_remove_cover_button button">' . __('删除封面', 'begin') . '</button>
		</td>
	</tr>'.cat_script();
}

// 使用wordpress
function cat_script() {
	return '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var wordpress_ver = "'.get_bloginfo("version").'", upload_button;
			$(".cat_upload_cover_button").click(function(event) {
				upload_button = $(this);
				var frame;
				if (wordpress_ver >= "3.5") {
					event.preventDefault();
					if (frame) {
						frame.open();
						return;
					}
					frame = wp.media();
					frame.on( "select", function() {
						// Grab the selected attachment.
						var attachment = frame.state().get("selection").first();
						frame.close();
						if (upload_button.parent().prev().children().hasClass("tax_list")) {
							upload_button.parent().prev().children().val(attachment.attributes.url);
							upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
						}
						else
							$("#taxonomy_cover").val(attachment.attributes.url);
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=cover&amp;TB_iframe=true");
					return false;
				}
			});
			
			$(".cat_remove_cover_button").click(function() {
				$(".taxonomy-cover").attr("src", "'.ZM_IMAGE_PLACEHOLDER.'");
				$("#taxonomy_cover").val("");
				$(this).parent().siblings(".title").children("img").attr("src","' . ZM_IMAGE_PLACEHOLDER . '");
				$(".inline-edit-col :input[name=\'taxonomy_cover\']").val("");
				return false;
			});
			
			if (wordpress_ver < "3.5") {
				window.send_to_editor = function(html) {
					imgurl = $("img",html).attr("src");
					if (upload_button.parent().prev().children().hasClass("tax_list")) {
						upload_button.parent().prev().children().val(imgurl);
						upload_button.parent().prev().prev().children().attr("src", imgurl);
					}
					else
						$("#taxonomy_cover").val(imgurl);
					tb_remove();
				}
			}
			
			$(".editinline").click(function() {	
			    var tax_id = $(this).parents("tr").attr("id").substr(4);
			    var thumb_cover = $("#tag-"+tax_id+" .thumb_cover img").attr("src");

				if (thumb_cover != "' . ZM_IMAGE_PLACEHOLDER . '") {
					$(".inline-edit-col :input[name=\'taxonomy_cover\']").val(thumb_cover);
				} else {
					$(".inline-edit-col :input[name=\'taxonomy_cover\']").val("");
				}
				
				$(".inline-edit-col .title img").attr("src",thumb_cover);
			});
	    });
	</script>';
}

// 保存分类图像
add_action('edit_term','cat_save_taxonomy_cover');
add_action('create_term','cat_save_taxonomy_cover');
function cat_save_taxonomy_cover($term_id) {
	if(isset($_POST['taxonomy_cover']))
		update_option('cat_taxonomy_cover'.$term_id, $_POST['taxonomy_cover'], NULL);
}

// 获取图像网址
function cat_get_attachment_id_by_url($cover_src) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $cover_src);
	$id = $wpdb->get_var($query);
	return (!empty($id)) ? $id : NULL;
}

// 获取指定term_id分类封面图片
function cat_cover_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
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

	$taxonomy_cover_url = get_option('cat_taxonomy_cover'.$term_id);
	if(!empty($taxonomy_cover_url)) {
		$attachment_id = cat_get_attachment_id_by_url($taxonomy_cover_url);
		if(!empty($attachment_id)) {
		$taxonomy_cover_url = wp_get_attachment_image_src($attachment_id, $size);
		$taxonomy_cover_url = $taxonomy_cover_url[0];
		}
	}

	if ($return_placeholder)
		return ($taxonomy_cover_url != '') ? $taxonomy_cover_url : ZM_IMAGE_PLACEHOLDER;
	else
		return $taxonomy_cover_url;
}

// 分类快速编辑添加图片表单
function cat_quick_edit_custom_box($column_name, $screen, $name) {
	if ($column_name == 'thumb_cover') 
		echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="Thumbnail"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_cover" value="" class="tax_list" /></span>
				<span class="input-text-wrap">
					<button class="cat_upload_cover_button button">' . __('添加封面', 'begin') . '</button>
					<button class="cat_remove_cover_button button">' . __('删除封面', 'begin') . '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}

// 在分类列表管理添加缩略图
function cat_taxonomy_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['thumb_cover'] = __('分类封面', 'categories-covers');
	unset( $columns['cb'] );
	return array_merge( $new_columns, $columns );
}

// 缩略图列值添加到类别管理
function cat_taxonomy_column( $columns, $column, $id ) {
	if ( $column == 'thumb_cover' )
		$columns = '<span><img src="' . cat_cover_url($id, 'thumbnail', TRUE) . '" alt="' . __('Thumbnail', 'begin') . '" class="wp-post-cover" /></span>';

	return $columns;
}

// 更改“插入到”到“使用此图像”
function cat_change_insert_button_text($safe_text, $text) {
	return str_replace("Insert into Post", "Use this cover", $text);
}

// 列表中图像添加样式
if (strpos( $_SERVER['SCRIPT_NAME'], 'term.php' ) > 0 ) {
	add_action( 'admin_head', 'cat_cover_add_style' );
}
if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 ) {
	add_action( 'admin_head', 'cat_cover_add_style' );
	// add_action('quick_edit_custom_box', 'cat_quick_edit_custom_box', 10, 3);
	add_filter("attribute_escape", "cat_change_insert_button_text", 10, 2);
}

// term_id显示分类图像
function cat_taxonomy_cover($term_id = NULL, $size = 'full', $attr = NULL, $echo = TRUE) {
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

	$taxonomy_cover_url = get_option('cat_taxonomy_cover'.$term_id);
	if(!empty($taxonomy_cover_url)) {
		$attachment_id = cat_get_attachment_id_by_url($taxonomy_cover_url);
		if(!empty($attachment_id))
			$taxonomy_cover = wp_get_attachment_cover($attachment_id, $size, FALSE, $attr);
		else {
			$cover_attr = '';
			if(is_array($attr)) {
				if(!empty($attr['class']))
					$cover_attr .= ' class="'.$attr['class'].'" ';
				if(!empty($attr['alt']))
					$cover_attr .= ' alt="'.$attr['alt'].'" ';
				if(!empty($attr['width']))
					$cover_attr .= ' width="'.$attr['width'].'" ';
				if(!empty($attr['height']))
					$cover_attr .= ' height="'.$attr['height'].'" ';
				if(!empty($attr['title']))
					$cover_attr .= ' title="'.$attr['title'].'" ';
			}
			$taxonomy_cover = '<img src="'.$taxonomy_cover_url.'" '.$cover_attr.'/>';
		}
	}

	if ($echo)
		echo $taxonomy_cover;
	else
		return $taxonomy_cover;
}