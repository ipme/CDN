<?php
/*
Template Name: 空白模板
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<style type="text/css">
#primary {
	width: 100%;
}

#page .page {
	position: relative;
	background: transparent;
	margin: 0 0 10px 0;
	padding: 0;
	border: none;
	box-shadow: none;
	border-radius: 0;
}

.paper-content img {
	max-width: 100%;
	width: auto\9;
	height: auto;
	vertical-align: middle;
	display: block;
}
</style>

<div id="primary" class="paper-area">
	<main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="paper-content">
				<div class="paper-content">
					<?php the_content(); ?>
				</div>
			</div>
		</article>
	<?php endwhile; ?>
	</main>
</div>

<?php get_footer(); ?>