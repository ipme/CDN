<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cms_widget_three')) { ?>
<div id="cms-widget-three" class="wow fadeInUp sort" data-wow-delay="0.5s" name="<?php echo zm_get_option('cat_square_s'); ?>">
	<?php if ( ! dynamic_sidebar( 'cms-three' ) ) : ?>
		<aside class="add-widgets">
			<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“杂志三栏小工具”添加小工具</a>
		</aside>
	<?php endif; ?>
	<div class="clear"></div>
</div>
<?php } ?>