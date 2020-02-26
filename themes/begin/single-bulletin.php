<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp post ms'); ?> data-wow-delay="0.3s">

					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->

					<?php begin_single_meta(); ?>

					<div class="entry-content">
						<div class="single-content">
							<?php get_template_part('ad/ads', 'single'); ?>
							<?php the_content(); ?>
							<?php get_template_part( 'inc/file' ); ?>
							<?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) : ?><style>#primary {width: 100%;}#sidebar,.r-hide {display: none;}</style><?php endif; ?>
							<?php if (zm_get_option('tts_play') && zm_get_option('meta_b') && !get_post_meta($post->ID, 'not_tts', true)) { ?><?php require get_template_directory() . '/inc/tts.php'; ?><?php } ?>
						</div>

						<?php wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="be be-arrowleft"></i></span>', 'nextpagelink' => "")); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => '<span><i class="be be-arrowright"></i></span> ')); ?>

						<?php if (zm_get_option('zm_like')) { ?>
							<?php get_template_part( 'template/social' ); ?>
						<?php } else { ?>
							<div id="social"></div>
						<?php } ?>

						<footer class="single-footer">
							<div class="single-cat-tag">
								<div class="single-cat"><i class="be be-sort"></i><?php echo get_the_term_list( $post->ID,  'notice', '' ); ?></div>
							</div>
						</footer><!-- .entry-footer -->

						<div class="clear"></div>
					</div><!-- .entry-content -->


				</article><!-- #post -->

				<?php if (zm_get_option('copyright')) { ?>
					<?php get_template_part( 'template/copyright' ); ?>
				<?php } ?>

				<nav class="nav-single wow fadeInUp" data-wow-delay="0.3s">
					<?php
						if (get_previous_post()) { previous_post_link( '%link','<span class="meta-nav ms"><span class="post-nav"><i class="be be-arrowleft"></i> ' . sprintf(__( '上一篇', 'begin' )) . '</span><br/>%title</span>' ); } else { echo "<span class='meta-nav ms'><span class='post-nav'>" . sprintf(__( '没有了', 'begin' )) . "<br/></span>" . sprintf(__( '已是最后文章', 'begin' )) . "</span>"; }
						if (get_next_post()) { next_post_link( '%link', '<span class="meta-nav ms"><span class="post-nav">' . sprintf(__( '下一篇', 'begin' )) . ' <i class="be be-arrowright"></i></span><br/>%title</span>' ); } else { echo "<span class='meta-nav ms'><span class='post-nav'>" . sprintf(__( '没有了', 'begin' )) . "<br/></span>" . sprintf(__( '已是最新文章', 'begin' )) . "</span>"; }
					?>
					<div class="clear"></div>
				</nav>
				<?php if (zm_get_option('meta_nav_lr')) : ?>
				<?php
					the_post_navigation( array(
						'next_text' => '<span class="meta-nav-l" aria-hidden="true"><i class="be be-arrowright"></i></span>',
						'prev_text' => '<span class="meta-nav-r" aria-hidden="true"><i class="be be-arrowleft"></i></span>',
					) );
				?>
				<?php endif; ?>
				<?php if ( comments_open() || get_comments_number() ) : ?>
					<?php comments_template( '', true ); ?>
				<?php endif; ?>

			<?php endwhile; ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>