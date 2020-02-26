<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_widget_two')) { ?>
<div class="g-row <?php if (zm_get_option('bg_15')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_widget_two_s'); ?>">
	<div class="g-col">
		<div id="group-widget-two" class="group-widget dy fadeInUp" data-wow-delay="0.5s">
			<?php if ( ! dynamic_sidebar( 'group-two' ) ) : ?>
				<aside class="add-widgets">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“公司两栏小工具”添加小工具</a>
					<div class="clear"></div>
				</aside>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>