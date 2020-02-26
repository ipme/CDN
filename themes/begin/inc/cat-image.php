<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
define('ZM_IMAGE_PLACEHOLDER', get_template_directory_uri()."/img/placeholder.png");

add_action('admin_init', 'zm_init');
function zm_init() {
	$zm_taxonomies = get_taxonomies();
	if (is_array($zm_taxonomies)) {
		foreach ($zm_taxonomies as $zm_taxonomy) {
			add_action($zm_taxonomy.'_add_form_fields', 'zm_add_texonomy_field');
			add_action($zm_taxonomy.'_edit_form_fields', 'zm_edit_texonomy_field');
			add_filter( 'manage_edit-' . $zm_taxonomy . '_columns', 'zm_taxonomy_columns' );
			add_filter( 'manage_' . $zm_taxonomy . '_custom_column', 'zm_taxonomy_column', 10, 3 );
		}
	}
}

function zm_add_style() {
	echo '<style type="text/css" media="screen">
		th.column-thumb {width:60px;}
		.form-field img.taxonomy-image {border:1px solid #eee;max-width:300px;max-height:300px;}
		.inline-edit-row fieldset .thumb label span.title {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.column-thumb span {width:48px;height:48px;display:inline-block;}
		.inline-edit-row fieldset .thumb img,.column-thumb img {width:48px;height:48px;}
	</style>';
}


// 添加图片字段表单
function zm_add_texonomy_field() {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	echo '<div class="form-field">
		<label for="taxonomy_image">' . __('分类图片', 'begin') . '</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
		<br/>
		<button class="zm_upload_image_button button">' . __('添加图片', 'begin') . '</button>
	</div>'.zm_script();
}

// 在分类编辑添加图片字段表单
function zm_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	if (zm_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) == ZM_IMAGE_PLACEHOLDER) 
		$image_url = "";
	else
		$image_url = zm_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">' . __('分类图片', 'begin') . '</label></th>
		<td><img class="taxonomy-image" src="' . zm_taxonomy_image_url( $taxonomy->term_id, 'medium', TRUE ) . '"/><br/><input type="text" name="taxonomy_image" id="taxonomy_image" value="'.$image_url.'" /><br />
		<button class="zm_upload_image_button button">' . __('添加图片', 'begin') . '</button>
		<button class="zm_remove_image_button button">' . __('删除图片', 'begin') . '</button>
		</td>
	</tr>'.zm_script();
}

// 使用wordpress上传
function zm_script() {
	return '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var wordpress_ver = "'.get_bloginfo("version").'", upload_button;
			$(".zm_upload_image_button").click(function(event) {
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
							$("#taxonomy_image").val(attachment.attributes.url);
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
					return false;
				}
			});
			
			$(".zm_remove_image_button").click(function() {
				$(".taxonomy-image").attr("src", "'.ZM_IMAGE_PLACEHOLDER.'");
				$("#taxonomy_image").val("");
				$(this).parent().siblings(".title").children("img").attr("src","' . ZM_IMAGE_PLACEHOLDER . '");
				$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
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
						$("#taxonomy_image").val(imgurl);
					tb_remove();
				}
			}
			
			$(".editinline").click(function() {	
			    var tax_id = $(this).parents("tr").attr("id").substr(4);
			    var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");

				if (thumb != "' . ZM_IMAGE_PLACEHOLDER . '") {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
				} else {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				}
				
				$(".inline-edit-col .title img").attr("src",thumb);
			});
	    });
	</script>';
}

// 保存分类图像
add_action('edit_term','zm_save_taxonomy_image');
add_action('create_term','zm_save_taxonomy_image');
function zm_save_taxonomy_image($term_id) {
	if(isset($_POST['taxonomy_image']))
		update_option('zm_taxonomy_image'.$term_id, $_POST['taxonomy_image'], NULL);
}

// 获取图像网址
function zm_get_attachment_id_by_url($image_src) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $image_src);
	$id = $wpdb->get_var($query);
	return (!empty($id)) ? $id : NULL;
}

// get taxonomy image url for the given term_id (Place holder image by default)
function zm_taxonomy_image_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
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
	
	$taxonomy_image_url = get_option('zm_taxonomy_image'.$term_id);
	if(!empty($taxonomy_image_url)) {
		$attachment_id = zm_get_attachment_id_by_url($taxonomy_image_url);
		if(!empty($attachment_id)) {
		$taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
		$taxonomy_image_url = $taxonomy_image_url[0];
		}
	}

	if ($return_placeholder)
		return ($taxonomy_image_url != '') ? $taxonomy_image_url : ZM_IMAGE_PLACEHOLDER;
	else
		return $taxonomy_image_url;
}


// 分类快速编辑添加图片表单
function zm_quick_edit_custom_box($column_name, $screen, $name) {
	if ($column_name == 'thumb') 
		echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="Thumbnail"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_image" value="" class="tax_list" /></span>
				<span class="input-text-wrap">
					<button class="zm_upload_image_button button">' . __('添加图片', 'begin') . '</button>
					<button class="zm_remove_image_button button">' . __('删除图片', 'begin') . '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}

// 在分类列表管理添加缩略图
function zm_taxonomy_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['thumb'] = __('分类图片', 'categories-images');

	unset( $columns['cb'] );

	return array_merge( $new_columns, $columns );
}


// 缩略图列值添加到类别管理
function zm_taxonomy_column( $columns, $column, $id ) {
	if ( $column == 'thumb' )
		$columns = '<span><img src="' . zm_taxonomy_image_url($id, 'thumbnail', TRUE) . '" alt="' . __('Thumbnail', 'begin') . '" class="wp-post-image" /></span>';
	
	return $columns;
}

// 更改“插入到”到“使用此图像”
function zm_change_insert_button_text($safe_text, $text) {
	return str_replace("Insert into Post", "Use this image", $text);
}

// 列表中图像添加样式
if (strpos( $_SERVER['SCRIPT_NAME'], 'term.php' ) > 0 ) {
	add_action( 'admin_head', 'zm_add_style' );
}
if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 ) {
	add_action( 'admin_head', 'zm_add_style' );
	add_action('quick_edit_custom_box', 'zm_quick_edit_custom_box', 10, 3);
	add_filter("attribute_escape", "zm_change_insert_button_text", 10, 2);
}

// term_id显示分类图像
function zm_taxonomy_image($term_id = NULL, $size = 'full', $attr = NULL, $echo = TRUE) {
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
	
	$taxonomy_image_url = get_option('zm_taxonomy_image'.$term_id);
	if(!empty($taxonomy_image_url)) {
		$attachment_id = zm_get_attachment_id_by_url($taxonomy_image_url);
		if(!empty($attachment_id))
			$taxonomy_image = wp_get_attachment_image($attachment_id, $size, FALSE, $attr);
		else {
			$image_attr = '';
			if(is_array($attr)) {
				if(!empty($attr['class']))
					$image_attr .= ' class="'.$attr['class'].'" ';
				if(!empty($attr['alt']))
					$image_attr .= ' alt="'.$attr['alt'].'" ';
				if(!empty($attr['width']))
					$image_attr .= ' width="'.$attr['width'].'" ';
				if(!empty($attr['height']))
					$image_attr .= ' height="'.$attr['height'].'" ';
				if(!empty($attr['title']))
					$image_attr .= ' title="'.$attr['title'].'" ';
			}
			$taxonomy_image = '<img src="'.$taxonomy_image_url.'" '.$image_attr.'/>';
		}
	}

	if ($echo)
		echo $taxonomy_image;
	else
		return $taxonomy_image;
}