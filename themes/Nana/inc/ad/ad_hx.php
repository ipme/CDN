<?php if ( wp_is_mobile() ) { ?>
	<?php if ( get_option('ygj_adh_cx_m') ) { ?><div class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_adh_cx_m') ); ?></div><?php } ?>
<?php } else { ?>
	<?php if ( get_option('ygj_adh_cx') ) { ?><div class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_adh_cx') ); ?></div><?php } ?>
<?php } ?>