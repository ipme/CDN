<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('ad_s')) { ?>
	<?php if ( wp_is_mobile() ) { ?>
		<div class="tg-m tg-site"><?php echo stripslashes( zm_get_option('ad_s_c_m') ); ?></div>
	<?php } else { ?>
		<div class="tg-pc tg-site"><?php echo stripslashes( zm_get_option('ad_s_c') ); ?></div>
	<?php } ?>
<?php } ?>