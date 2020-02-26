<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( get_post_meta($post->ID, 'sidebar_l', true) ) { ?>
<div id="sidebar-l" class="widget-area all-sidebar">
<?php } else { ?>
<div id="sidebar" class="widget-area all-sidebar">
<?php } ?>

	<?php if ( is_page('template-cms') ) : ?>
		<?php dynamic_sidebar( 'cms-page' ); ?>
	<?php endif; ?>

	<?php if (is_single() || is_page() ) : ?>
		<?php if ( ! dynamic_sidebar( 'sidebar-s' ) ) : ?>
			<aside id="add-widgets" class="widget widget_text">
				<h3 class="widget-title"><i class="be be-warning"></i>添加小工具</h3>
				<div class="textwidget">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“正文侧边栏”添加小工具</a>
				</div>
			</aside>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( is_archive() || is_search() || is_404() ) : ?>
		<?php if ( ! dynamic_sidebar( 'sidebar-a' ) ) : ?>
			<aside id="add-widgets" class="widget widget_text">
				<h3 class="widget-title"><i class="be be-warning"></i>添加小工具</h3>
				<div class="textwidget">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“分类归档侧边栏”添加小工具</a>
				</div>
			</aside>
		<?php endif; ?>
	<?php endif; ?>
</div>

<div class="clear"></div>