<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cms_widget_two')) { ?>
<div id="cms-widget-two" class="wow fadeInUp sort" data-wow-delay="0.5s" name="<?php echo zm_get_option('cms_widget_two_s'); ?>">
	<?php if ( ! dynamic_sidebar( 'cms-two' ) ) : ?>
		<aside class="add-widgets">
			<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“杂志两栏小工具”添加小工具</a>
		</aside>
	<?php endif; ?>
	<div class="clear"></div>
</div>
<?php } ?>