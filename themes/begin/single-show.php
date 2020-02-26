<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/show.css" />
<div class="container">
	<?php get_template_part( '/show/show-slider' ); ?>
	<div id="group-section">
		<?php get_template_part( '/show/show-dean' ); ?>
		<?php get_template_part( '/show/show-content' ); ?>
		<?php get_template_part( '/show/show-contact' ); ?>
		<?php get_template_part( '/show/show-comments' ); ?>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
$("#group-section .g-row:even").addClass("g-line");
</script>

<?php get_footer(); ?>