<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
/*
Template Name: 站内信
*/
wp_head();
?>

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/front-end-pm.css" />
<style type="text/css">
.fep-table > div > div {
	vertical-align: middle;
}
.fep-table div {
	padding: 5px;
}
</style>
<?php while ( have_posts() ) : the_post(); ?>
	<div class="front-content">
		<?php the_content(); ?>
	</div>
<?php endwhile; ?>
<?php wp_footer(); ?>