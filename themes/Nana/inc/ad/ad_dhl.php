<?php if ( wp_is_mobile() ) { ?>
	<?php if ( get_option('ygj_addd_c_m') ) { ?><div id="ad-dhl" class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_addd_c_m') ); ?></div><?php } ?>
<?php } else { ?>
	<?php if ( get_option('ygj_addd_c') ) { ?><div id="ad-dhl" class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_addd_c') ); ?></div><?php } ?>
<?php } ?>