<?php
$current_user = wp_get_current_user();
$post = false;
$post_id = -1;
$featured_img_html = '';
if (isset($_GET['fep_id']) && isset($_GET['fep_action']) && $_GET['fep_action'] == 'edit') {
	$post_id = $_GET['fep_id'];
	$p = get_post($post_id, 'ARRAY_A');
	if ($p['post_author'] != $current_user->ID) return __("您没有权限编辑这篇文章。", 'frontend-publishing');
	$category = get_the_category($post_id);
	$tags = wp_get_post_tags($post_id, array('fields' => 'names'));
	$featured_img = get_post_thumbnail_id($post_id);
	$featured_img_html = (!empty($featured_img)) ? wp_get_attachment_image($featured_img, array(200, 200)) : '';
	$post = array(
		'title'            => $p['post_title'],
		'content'          => $p['post_content'],
		'about_the_author' => get_post_meta($post_id, 'about_the_author', true)
	);
	if (isset($category[0]) && is_array($category))
		$post['category'] = $category[0]->cat_ID;
	if (isset($tags) && is_array($tags))
		$post['tags'] = implode(', ', $tags);
}
?>

<div id="fep-new-post">
	<div id="fep-message" class="warning"></div>
	<form id="fep-submission-form">
		<label for="fep-post-title">文章标题</label>
		<input type="text" name="post_title" id="fep-post-title" value="<?php echo ($post) ? $post['title'] : ''; ?>">
		<label for="fep-post-content">文章内容</label>
		<?php
		$enable_media = (isset($fep_roles['enable_media']) && $fep_roles['enable_media']) ? current_user_can($fep_roles['enable_media']) : 1;
		wp_editor($post['content'], 'fep-post-content', $settings = array('textarea_name' => 'post_content', 'textarea_rows' => 7, 'media_buttons' => $enable_media));
		wp_nonce_field('fepnonce_action', 'fepnonce');
		?>

		<?php if (!$fep_misc['thumbnail_required']): ?>
		<?php else: ?>
			<div id="fep-featured-image">
				<div id="fep-featured-image-container"><?php echo $featured_img_html; ?></div>
				<a id="fep-featured-image-link" href="#">添加特色图像</a>
				<input type="hidden" id="fep-featured-image-id" value="<?php echo (!empty($featured_img)) ? $featured_img : '-1'; ?>"/>
			</div>
		<?php endif; ?>

		<label for="fep-category">选择分类</label>
		<?php 
			$notcat = explode(',',zm_get_option('not_front_cat'));
			wp_dropdown_categories(array('id' => 'fep-category', 'hide_empty' => 0, 'name' => 'post_category', 'orderby' => 'name', 'selected' => $post['category'], 'hierarchical' => true,  'exclude' => $notcat));
		?>
		<label for="fep-tags">标签</label>
		<input type="text" name="post_tags" id="fep-tags" value="<?php echo ($post) ? $post['tags'] : ''; ?>">
		<input type="hidden" name="post_id" id="fep-post-id" value="<?php echo $post_id ?>">
		<button type="button" id="fep-submit-post" class="active-btn">提 交</button>
	</form>
</div>
<script type="text/javascript">function renovates(){ document.location.reload();}</script>