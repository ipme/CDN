<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="filter-box wow fadeInUp ms" data-wow-delay="0.3s">
	<div class="filter-t"><i class="be be-sort"></i><span><?php echo zm_get_option('filter_t'); ?></span></div>
		<div class="filter-box-main">
		<?php require get_template_directory() . '/inc/filter-core.php'; ?>
		<div class="clear"></div>
	</div>
</div>