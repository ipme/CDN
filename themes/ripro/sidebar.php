<?php
	if ( ! is_active_sidebar( 'sidebar' ) ) {
		return;
	}
?>

<aside class="widget-area">
	<?php dynamic_sidebar( 'sidebar' ); ?>
</aside>