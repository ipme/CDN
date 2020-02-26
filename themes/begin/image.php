<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<style type="text/css">
#primary {width: 100%;}
#primary h1 {word-wrap: break-word;}
.single-content img {margin: 0 auto 30px;}
</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<div class="single-content">
						<?php
							$image_size = apply_filters( '', '' );
							echo wp_get_attachment_image( get_the_ID(), $image_size );
						?>
					</div>

						<?php if (zm_get_option('zm_like')) { ?>
							<?php get_template_part( 'template/social' ); ?>
						<?php } else { ?>
							<div id="social"></div>
						<?php } ?>

					<footer class="single-footer">
						<ul class="single-meta">
							<?php edit_post_link('<i class="be be-editor"></i>', '<li class="edit-link">', '</li>' ); ?>
							<?php if( function_exists( 'the_views' ) ) { the_views(true, '<li class="views"><i class="be be-eye"></i> ','</li>');  } ?>
						</ul>

						<div class="single-cat-tag">
							<div class="single-cat">上传日期：<?php the_time( 'Y年m月d日' ); ?></div>
							<div class="single-cat">附件来自：<a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a></div>
						</div>
					</footer><!-- .entry-footer -->
					<div class="clear"></div>
				</div><!-- .entry-content -->
			</article><!-- #post -->

				<?php if (zm_get_option('related_img')) { ?>
					<?php get_template_part( 'template/related-img' ); ?>
				<?php } ?>

				<div id="single-widget">
					<?php dynamic_sidebar( 'sidebar-e' ); ?>
					<div class="clear"></div>
				</div>

			<?php endwhile; ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>