<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template/content', get_post_format() ); ?>

				<?php get_template_part('ad/ads', 'archive'); ?>

			<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'template/content', 'none' ); ?>

			<?php endif; ?>

		</main><!-- .site-main -->

		<?php begin_pagenav(); ?>

	</section><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>