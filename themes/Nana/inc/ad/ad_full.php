<?php if ( wp_is_mobile() ) { ?>
	<?php if ( get_option('ygj_ad_fc_m') ) { ?><div class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_ad_fc_m') ); ?></div><?php } ?>
<?php } else { ?>
	<?php if ( get_option('ygj_ad_fc') ) { ?><div class="abc-pc abc-site"><?php echo stripslashes( get_option('ygj_ad_fc') ); ?></div><?php } ?>
<?php } ?>