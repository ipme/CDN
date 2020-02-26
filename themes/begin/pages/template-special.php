<?php
/*
Template Name: 专题模板
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<?php if ( get_post_meta($post->ID, 'sidebar_l', true) ) { ?>
<section id="primary-l" class="content-area">
<?php } else { ?>
<section id="primary" class="content-area">
<?php } ?>
	<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if ( is_single() ) : ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php else : ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp post'); ?> data-wow-delay="0.3s">
			<?php endif; ?>

				<header class="entry-header">
					<?php if ( get_post_meta($post->ID, 'header_img', true) || get_post_meta($post->ID, 'header_bg', true) ) { ?>
						<div class="entry-title-clear"></div>
					<?php } else { ?>
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<?php } ?>

				</header><!-- .entry-header -->

				<div class="entry-content">
					<div class="single-content">
						<?php the_content(); ?>
						<?php if (zm_get_option('xzh_gz')) { ?>
							 <script>cambrian.render('tail')</script>
						<?php } ?>
						<?php get_template_part( 'inc/file' ); ?>
						<?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) : ?><style>#primary {width: 100%;}#sidebar, .r-hide, .s-hide {display: none;}</style><?php endif; ?>
						<?php if ( get_post_meta($post->ID, 'down_link_much', true) ) : ?><style>.down-link {float: left;}</style><?php endif; ?>

						<?php wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span>上一页</span>', 'nextpagelink' => "")); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => "<span>下一页</span>")); ?>
					</div>
					<div class="clear"></div>
					<?php if ( get_post_meta($post->ID, 'no_abstract', true) ) : ?><style>.abstract {display: none;}</style><?php endif; ?>
				</div><!-- .entry-content -->
				<footer class="page-meta-zt">
					<?php begin_page_meta_zt(); ?>
				</footer><!-- .entry-footer -->
				<div class="clear"></div>
			</article><!-- #page -->
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
		<!-- 正文结束 -->

		<!-- 图片显示4篇 -->
		<div class="line-four">
			<?php 
				$special = get_post_meta($post->ID, 'special', true);
				if ( get_post_meta($post->ID, 'special_img_n', true) ) {
					$special_img_n = get_post_meta($post->ID, 'special_img_n', true);
				} else {
					$special_img_n = 4;
				}
				$loop = new WP_Query( array( 'tag' => $special, 'posts_per_page' => $special_img_n, 'post__not_in' => get_option( 'sticky_posts') ) );
				while ( $loop->have_posts() ) : $loop->the_post(); $do_not_img[] = $post->ID;
			?>
			<div class="xl4 xm4">
				<div class="picture-cms wow fadeInUp ms" data-wow-delay="0.3s">
					<figure class="picture-cms-img">
						<?php zm_thumbnail(); ?>
					</figure>
					<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</div>
			</div>

			<?php endwhile; ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>

		<!-- 网格显示2篇
		<div class="cms-news-grid-container">
			<?php 
				$special = get_post_meta($post->ID, 'special', true);
				$loop = new WP_Query( array( 'tag' => $special, 'posts_per_page' => 2, 'post__not_in' => get_option( 'sticky_posts'), 'post__not_in' => $do_not_img ) );
				while ( $loop->have_posts() ) : $loop->the_post(); $do_not_grid[] = $post->ID;
			?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
					<figure class="thumbnail">
						<?php zm_thumbnail(); ?>
					</figure>
					<header class="entry-header">
						<?php if ( get_post_meta($post->ID, 'mark', true) ) {
							the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a><span class="t-mark">' . $mark = get_post_meta($post->ID, 'mark', true) . '</span></h2>' );
						} else {
							the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
						} ?>
					</header>

					<div class="entry-content">
						<div class="archive-content">
							<?php if (has_excerpt('')){
									echo wp_trim_words( get_the_excerpt(), 30, '...' );
								} else {
									$content = get_the_content();
									$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
									echo wp_trim_words( $content, 35, '...' );
						        }
							?>
						</div>
						<span class="entry-meta">
							<?php begin_entry_meta(); ?>
						</span>
						<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		 -->

		<!-- 排除上面的专题文章 -->
		<?php 
			$special = get_post_meta($post->ID, 'special');
			$loop = new WP_Query( array( 'tag' => $special, 'posts_per_page' => 100, 'post__not_in' => get_option( 'sticky_posts'), 'post__not_in' => $do_not_img ) );
			while ( $loop->have_posts() ) : $loop->the_post();
		?>
		<?php get_template_part( 'template/content', get_post_format() ); ?>
		<?php get_template_part('ad/ads', 'archive'); ?>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>

		<!-- 相关专题 -->
		<?php if ( get_post_meta($post->ID, 'related_special_id', true) )  : ?>
		<div class="cat-cover-box">
			<h3 class="related-special"><?php _e( '推荐专题', 'begin' ); ?></h3>
			<div class="clear"></div>
			<?php 
				$related_special_id = get_post_meta($post->ID, 'related_special_id', true);
				$posts = get_posts( array( 'post_type' => 'any', 'include' => $related_special_id) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post );
			 ?>
				<div class="cover4x">
					<div class="cat-cover-main wow fadeInUp ms" data-wow-delay="0.3s">
						<div class="cat-cover-img">
							<a href="<?php echo get_permalink(); ?>" rel="bookmark">
								<div class="special-mark"><?php _e( '专题', 'begin' ); ?></div>
								<figure class="cover-img">
									<?php 
										$image = get_post_meta($post->ID, 'thumbnail', true);
										echo '<img src=';
										if (zm_get_option('special_thumbnail')) {
											echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_sp_w').'&h='.zm_get_option('img_sp_h').'&a='.zm_get_option('crop_top').'&zc=1';
										} else {
											echo $image;
										}
										echo ' alt="'.$post->post_title .'" />'; 
									?>
								</figure>
								<div class="cover-des-box"><div class="cover-des"><?php $description = get_post_meta($post->ID, 'description', true);{echo $description;} ?></div></div>
							</a>
							<div class="clear"></div>
						</div>
						<a href="<?php echo get_permalink(); ?>" rel="bookmark"><h4 class="cat-cover-title"><?php the_title(); ?></h4></a>
					</div>
				</div>
			<?php endforeach; endif; ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>
		<?php endif; ?>

		<!-- 评论 -->
		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template( '', true ); ?>
		<?php endif; ?>

	</main>
</section><!-- #primary -->

<?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) { ?>
<style>#slideshow {margin: 0 0 -2px 0;}#slider-title {border-radius: 2px 2px 0 0;}</style>
<?php } else { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>