<?php
/*
Template Name: 博客页面
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if (zm_get_option('slider')) { ?>
				<?php
					if ( !is_paged() ) :
						get_template_part( 'template/slider' );
					endif;
				?>
			<?php } ?>
			<?php 
				if ( !is_paged() ) :
				get_template_part( '/template/b-cover' ); 
				endif;
			?>
			<?php
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					$notcat = explode(',',zm_get_option('not_cat_n'));
					$args = array(
						'category__not_in' => $notcat,
					    'ignore_sticky_posts' => 0, 
						'paged' => $paged
					);
				query_posts( $args );
		 	?>

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

	</div><!-- .content-area -->

<?php get_sidebar('blog'); ?>
<?php get_footer(); ?>