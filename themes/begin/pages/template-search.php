<?php
/*
Template Name: 搜索页面
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<div id="ad-search" class="content-area ad-search">
	<main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php if ( is_single() ) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php else : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp post ms'); ?> data-wow-delay="0.3s">
		<?php endif; ?>
			<?php if ( get_post_meta($post->ID, 'header_img', true) || get_post_meta($post->ID, 'header_bg', true) ) { ?>
			<?php } else { ?>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
			<?php } ?>

			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
					<?php search_class(); ?>
				</div>
				<div class="ad-searchbar-h"></div>
				<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
				<div class="clear"></div>
			</div>
		</article>
		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template( '', true ); ?>
		<?php endif; ?>

	<?php endwhile; ?>

	</main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>