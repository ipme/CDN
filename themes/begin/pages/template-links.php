<?php
/*
Template Name: 友情链接
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>
<div id="content-links" class="content-area">
	<main id="main" class="link-area">
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="link-page">
			<div class="link-content">
				<?php echo begin_get_link_items(); ?>
			</div>
		</article>
		<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>