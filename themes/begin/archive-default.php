<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php get_template_part( 'template/cat-top' ); ?>
		<?php if ((zm_get_option('no_child')) && is_category() ) { ?>
			<?php 
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				query_posts(array('category__in' => array(get_query_var('cat')), 'paged' => $paged,));
			?>
		<?php } ?>
			<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template/content', get_post_format() ); ?>

				<?php get_template_part('ad/ads', 'archive'); ?>

			<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'template/content', 'none' ); ?>

			<?php endif; ?>

		</main><!-- .site-main -->

		<div class="pagenav-clear"><?php begin_pagenav(); ?></div>

	</section><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>