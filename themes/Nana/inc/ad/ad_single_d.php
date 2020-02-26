<?php if ( wp_is_mobile() ) { ?>
	<?php if ( get_option('ygj_adtc_m') ) { ?><div class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_adtc_m') ); ?></div><?php } ?>
<?php } else { ?>
	<?php if ( get_option('ygj_adtc') ) { ?><div class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_adtc') ); ?></div><?php } ?>
<?php } ?>