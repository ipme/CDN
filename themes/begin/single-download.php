<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp post sm'); ?> data-wow-delay="0.3s">

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<div class="single-content">
						<div class="dow-goods">
							<figure class="dow-img">
								<?php tao_thumbnail(); ?>
							</figure>
							<div class="dow-inf">
								<div class="dow-meta">
									<span class="date">日期：<?php the_time('Y年m月d日') ?></span>
									<span class="category">分类：<?php echo get_the_term_list($post->ID,  'download_category', '', ', ', ''); ?></span>
									<span class="comment"><?php echo get_the_term_list($post->ID,  'download_tag', '标签：', ', ', ''); ?></span>
								</div>
								<?php if ( function_exists('ascent_light_edd_buy_button') ) { ascent_light_edd_buy_button( 'product-cta-bottom' ); } ?>
							</div>
							<div class="clear"></div>
						</div>

						<div class="clear"></div>

						<?php the_content(); ?>
						<?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) : ?><style>#primary {width: 100%;}#sidebar,.r-hide {display: none;}</style><?php endif; ?>
						<div class="clear"></div>
						<?php wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="be be-arrowleft"></i></span>', 'nextpagelink' => "")); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => '<span><i class="be be-arrowright"></i></span> ')); ?>
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
							</div>
						</footer><!-- .entry-footer -->

					<div class="clear"></div>
				</div><!-- .entry-content -->


			</article><!-- #post -->

			<?php if (zm_get_option('related_img')) { ?>
				<?php get_template_part( 'template/single-dow' ); ?>
			<?php } ?>

				<?php get_template_part('ad/ads', 'comments'); ?>

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
		<?php endwhile; ?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<div id="sidebar" class="widget-area">
	<aside id="widget_edd_cart" class="widget widget_edd_cart_widget wow" data-wow-delay="0.3s">
		<h3 class="widget-title"><span class="title-i"><span class="title-i-t"></span><span class="title-i-b"></span><span class="title-i-b"></span><span class="title-i-t"></span></span>购物车</h3>
		<ul><?php edd_shopping_cart( true ); ?></ul>
		<div class="clear"></div>
	</aside>

	<aside id="widget_edd_cart" class="widget widget_edd_cart_widget wow" data-wow-delay="0.3s">
		<h3 class="widget-title"><span class="title-i"><span class="title-i-t"></span><span class="title-i-b"></span><span class="title-i-b"></span><span class="title-i-t"></span></span>您可能还会喜欢</h3>
		<ul>
			<?php $loop = new WP_Query( array( 'post_type' => 'download', 'posts_per_page' => 10, 'orderby' => 'rand') ); while ( $loop->have_posts() ) : $loop->the_post(); ?>
			<div class="img-box">
				<div class="img-x2">
					<figure class="insets">
						<?php zm_thumbnail(); ?>
					</figure>
				</div>
			</div>
			<?php endwhile;?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</ul>
		<div class="clear"></div>
	</aside>
</div>
<?php get_footer(); ?>