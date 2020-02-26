<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('grid_widget_one')) { ?>
<div id="cms-widget-one" class="wow fadeInUp" data-wow-delay="0.5s">
	<?php if ( ! dynamic_sidebar( 'cms-one' ) ) : ?>
		<aside class="add-widgets">
			<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“杂志单栏小工具”添加小工具</a>
		</aside>
	<?php endif; ?>
	<div class="clear"></div>
</div>
<?php } ?>