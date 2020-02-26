<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">

					<header class="entry-header">
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
							<div class="archive-content">
								<?php if (has_excerpt()){ ?>
									<?php the_excerpt() ?>
								<?php } else { echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 90,"..."); } ?>
							</div>
							<span class="title-l"></span>
							<?php get_template_part( 'inc/new' ); ?>
							<span class="entry-meta">
								<?php echo get_the_term_list( $post->ID,  'notice', '' ); ?>
								<span class="date"><?php the_time( 'Y年m月d日' ) ?> </span>
								<?php if ( post_password_required() ) { ?>
									<span class="comment"><a href="#comments">密码保护</a></span>
								<?php } else { ?>
									<span class="comment"><?php comments_popup_link( '发表评论', '评论 1 ', '评论 % ' ); ?></span>
								<?php } ?> 
								<?php if( function_exists( 'the_views' ) ) { print '<span class="views"> 阅读 '; the_views(); print '</span>';  } ?>
							</span>
						
					</div><!-- .entry-content -->

					<?php if ( ! is_single() ) : ?>
						<span class="entry-more"><a href="<?php the_permalink(); ?>" rel="bookmark">阅读全文</a></span>
					<?php endif; ?>
				</article><!-- #post -->

			<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

		</main><!-- .site-main -->

		<?php begin_pagenav(); ?>

	</section><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>