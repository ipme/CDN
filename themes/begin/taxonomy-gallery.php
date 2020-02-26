<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
	<?php if (zm_get_option('gallery_fall')) { ?>
	<?php if (zm_get_option('type_cat')) { ?><?php if ( !is_paged() ) :get_template_part( 'template/type-cat' ); endif; ?><?php } ?>
	<section id="post-fall" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php $posts = query_posts($query_string . '&orderby=date&showposts='.zm_get_option('taxonomy_cat_n'));?>
			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('fall wow fadeInUp'); ?> data-wow-delay="0.3s">
				<div class="picture-box ms">
					<figure class="fall-img">
						<?php zm_waterfall_img(); ?>
					</figure>
					<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</div>
			</article><!-- #post -->
			<?php endwhile; ?>
		</main><!-- .site-main -->	
	</section><!-- .content-area -->
	<?php begin_pagenav(); ?>

	<?php } else { ?>

	<section id="picture" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if (zm_get_option('type_cat')) { ?><?php if ( !is_paged() ) :	get_template_part( 'template/type-cat' ); endif; ?><?php } ?>
			<?php $posts = query_posts($query_string . '&orderby=date&showposts='.zm_get_option('taxonomy_cat_n'));?>
			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="picture-box wow fadeInUp ms" data-wow-delay="0.3s">
					<figure class="picture-img">
						<?php img_thumbnail(); ?>
					</figure>
					<?php the_title( sprintf( '<h3 class="picture-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
				</div>
			</article><!-- #post -->
			<?php endwhile; ?>
		</main><!-- .site-main -->
		<?php begin_pagenav(); ?>
	</section><!-- .content-area -->
<?php } ?>
<?php get_footer(); ?>