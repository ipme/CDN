<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('ad_h_t')) { ?>
<?php if (zm_get_option('ad_h_t_h')) { ?>
<?php if ( is_front_page()){ ?>
	<div class="header-sub">
		<?php if ( wp_is_mobile() ) { ?>
			<?php if ( zm_get_option('ad_ht_m') ) { ?><div class="tg-m tg-site"><?php echo stripslashes( zm_get_option('ad_ht_m') ); ?></div><?php } ?>
		<?php } else { ?>
			<?php if ( zm_get_option('ad_ht_c') ) { ?><div class="tg-pc tg-site"><?php echo stripslashes( zm_get_option('ad_ht_c') ); ?></div><?php } ?>
		<?php } ?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php } else { ?>
	<div class="header-sub">
		<?php if ( wp_is_mobile() ) { ?>
			<?php if ( zm_get_option('ad_ht_m') ) { ?><div class="tg-m tg-site"><?php echo stripslashes( zm_get_option('ad_ht_m') ); ?></div><?php } ?>
		<?php } else { ?>
			<?php if ( zm_get_option('ad_ht_c') ) { ?><div class="tg-pc tg-site"><?php echo stripslashes( zm_get_option('ad_ht_c') ); ?></div><?php } ?>
		<?php } ?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php } ?>

<?php if (zm_get_option('ad_h')) { ?>
<?php if (zm_get_option('ad_h_h')) { ?>
<?php if ( is_front_page()){ ?>
	<div class="header-sub">
	<?php if ( wp_is_mobile() ) { ?>
		 <?php if ( zm_get_option('ad_h_c_m') ) { ?><div class="tg-l-m tg-site"><?php echo stripslashes( zm_get_option('ad_h_c_m') ); ?></div><?php } ?>
	<?php } else { ?>
		<?php if ( zm_get_option('ad_h_c') ) { ?><div class="tg-l tg-site"><?php echo stripslashes( zm_get_option('ad_h_c') ); ?></div><?php } ?>
	<?php } ?>

	<?php if ( wp_is_mobile() ) { ?>
	<?php } else { ?>
		<?php if ( zm_get_option('ad_h_cr') ) { ?><div class="tg-r tg-site"><?php echo stripslashes( zm_get_option('ad_h_cr') ); ?></div><?php } ?>
	<?php } ?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php } else { ?>

	<div class="header-sub">
	<?php if ( wp_is_mobile() ) { ?>
		 <?php if ( zm_get_option('ad_h_c_m') ) { ?><div class="tg-l-m tg-site"><?php echo stripslashes( zm_get_option('ad_h_c_m') ); ?></div><?php } ?>
	<?php } else { ?>
		<?php if ( zm_get_option('ad_h_c') ) { ?><div class="tg-l tg-site"><?php echo stripslashes( zm_get_option('ad_h_c') ); ?></div><?php } ?>
	<?php } ?>

	<?php if ( wp_is_mobile() ) { ?>
	<?php } else { ?>
		<?php if ( zm_get_option('ad_h_cr') ) { ?><div class="tg-r tg-site"><?php echo stripslashes( zm_get_option('ad_h_cr') ); ?></div><?php } ?>
	<?php } ?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php } ?>