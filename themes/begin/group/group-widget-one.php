<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_widget_one')) { ?>
<div class="g-row <?php if (zm_get_option('bg_10')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_widget_one_s'); ?>">
	<div class="g-col">
		<div id="group-widget-one" class="group-widget dy fadeInUp" data-wow-delay="0.5s">
			<?php if ( ! dynamic_sidebar( 'group-one' ) ) : ?>
				<aside class="add-widgets">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“公司一栏小工具”添加小工具</a>
					<div class="clear"></div>
				</aside>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>