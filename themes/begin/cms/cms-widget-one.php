<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cms_widget_one')) { ?>
<div id="cms-widget-one" class="wow fadeInUp sort" data-wow-delay="0.5s" name="<?php echo zm_get_option('cms_widget_one_s'); ?>">
	<?php if ( ! dynamic_sidebar( 'cms-one' ) ) : ?>
		<aside class="add-widgets">
			<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“杂志单栏小工具”添加小工具</a>
		</aside>
	<?php endif; ?>
	<div class="clear"></div>
</div>
<?php } ?>