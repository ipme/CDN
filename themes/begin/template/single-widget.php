<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('single_e')) { ?>
<div id="single-widget">
	<div class="wow fadeInUp" data-wow-delay="0.3s">
		<?php if ( ! dynamic_sidebar( 'sidebar-e' ) ) : ?>
			<aside class="add-widgets">
				<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">点此为“正文底部小工具”添加小工具</a>
			</aside>
		<?php endif; ?>
	</div>
	<div class="clear"></div>
</div>
<?php } ?>