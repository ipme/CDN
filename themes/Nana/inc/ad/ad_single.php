<?php if ( wp_is_mobile() ) { ?>
	<?php if ( get_option('ygj_single_ad_m') ) { ?><div class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_single_ad_m') ); ?></div><?php } ?>
<?php } else { ?>
	<?php if ( get_option('ygj_single_ad') ) { ?><div class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_single_ad') ); ?></div><?php } ?>
<?php } ?>