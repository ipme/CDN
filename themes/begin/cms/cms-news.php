<!-- 最新文章 -->
<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('news')) { ?>
<div class="cms-news sort" name="<?php echo zm_get_option('news_s'); ?>">
	<?php if (zm_get_option('cat_all')) { ?>
		<?php require get_template_directory() . '/template/all-cat.php'; ?>
	<?php } ?>
	<?php 
	if (!zm_get_option('news_model') || (zm_get_option("news_model") == 'news_grid')) {
		// 标准模式
		require get_template_directory() . '/cms/cms-news-grid.php';
	}
	if (zm_get_option('news_model') == 'news_normal') {
		// 图文模式
		require get_template_directory() . '/cms/cms-news-normal.php';
	}
	?>
</div>
<?php } ?>