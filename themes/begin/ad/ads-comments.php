<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('ad_c')) { ?>
<div class="wow fadeInUp" data-wow-delay="0.3s">
	<?php if ( wp_is_mobile() ) { ?>
		<?php if ( zm_get_option('ad_c_c_m') ) { ?><div class="tg-m tg-site"><?php echo stripslashes( zm_get_option('ad_c_c_m') ); ?></div><?php } ?>
	<?php } else { ?>
		<?php if ( zm_get_option('ad_c_c') ) { ?><div class="tg-pc tg-site"><?php echo stripslashes( zm_get_option('ad_c_c') ); ?></div><?php } ?>
	<?php } ?>
</div>
<?php } ?>