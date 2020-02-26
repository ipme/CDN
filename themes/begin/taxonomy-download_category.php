<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
	<section id="picture" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php $posts = query_posts($query_string . '&orderby=date&showposts='.zm_get_option('taxonomy_cat_n'));?>
			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="edd-box wow fadeInUp ms" data-wow-delay="0.3s">
					<figure class="picture-img">
						<?php tao_thumbnail(); ?>
					</figure>
					<?php the_title( sprintf( '<h3 class="picture-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					<div class="group-tab-meta">
						<div class="group-date"><?php the_time( 'm月d日' ); ?></div>
						<?php if( function_exists( 'the_views' ) ) { the_views( true, '<div class="group-views"><i class="be be-eye"></i> ','</div>' ); } ?>
						<div class="clear"></div>
					</div>
				</div>
			</article><!-- #post -->
			<?php endwhile; ?>
		</main><!-- .site-main -->
		<?php begin_pagenav(); ?>
	</section><!-- .content-area -->

<?php get_footer(); ?>