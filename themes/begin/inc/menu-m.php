<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div id="mobile-nav">
	<div class="off-mobile-nav wow fadeInRight animated" data-wow-delay="0.4s"></div>
	<div class="mobile-nav-box">
		<?php
			wp_nav_menu( array(
				'theme_location'	=> 'mobile',
				'menu_class'		=> 'mobile-menu',
				'fallback_cb'		=> 'default_menu'
			) );
		?>
	</div>
	<div class="clear"></div>
	<div class="mobile-nav-b"></div>
</div>