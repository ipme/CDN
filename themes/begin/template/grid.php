<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

	<?php if (zm_get_option('slider')) { ?>
		<?php
			if ( !is_paged() ) :
				get_template_part( 'template/slider' );
			endif;
		?>
	<?php } ?>

	<?php if (zm_get_option('cms_top')) { ?>
		<?php
			if ( !is_paged() ) :
				get_template_part( 'template/img-top' );
			endif;
		?>
	<?php } ?>

	<?php if (zm_get_option('cat_all')) { ?>
		<?php require get_template_directory() . '/template/all-cat.php'; ?>
	<?php } ?>

	<?php 
		if ( !is_paged() ) :
		get_template_part( '/template/b-cover' ); 
		endif;
	?>

	<?php
		if ( !is_paged() ) :
			get_template_part( '/inc/filter-general' );
		endif;
	?>

	<section id="picture" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if (zm_get_option('order_by')) {  begin_orderby(); }?>
			<?php if (zm_get_option('cms_top')) { ?>
				<?php
					$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					$notcat = explode(',',zm_get_option('not_cat_n'));
					$args = array(
						'category__not_in' => $notcat,
					    'ignore_sticky_posts' => 0, 
						'paged' => $paged,
						'meta_query' => array(
							array(
								'key' => 'cms_top',
								'compare' => 'NOT EXISTS'
							)
						)
					);
					query_posts( $args );
					begin_order();
			 	?>
			<?php } else { ?>
				<?php
					$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					$notcat = explode(',',zm_get_option('not_cat_n'));
					$args = array(
						'category__not_in' => $notcat,
					    'ignore_sticky_posts' => 0, 
						'paged' => $paged
					);
					query_posts( $args );
					begin_order();
			 	?>
			<?php } ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
				<div class="picture-box sup ms">
					<figure class="picture-img">
						<?php if (zm_get_option('hide_box')) { ?>
							<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-box"></div></a>
							<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>">
								<div class="hide-excerpt">
									<?php if (has_excerpt('')){
											echo wp_trim_words( get_the_excerpt(), 30, '...' );
										} else {
											$content = get_the_content();
											$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
											echo wp_trim_words( $content, 30, '...' );
								        }
									?>
								</div>
							</a>
						<?php } ?>

						<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
							<?php $direct = get_post_meta($post->ID, 'direct', true); ?>
							<?php zm_thumbnail_link(); ?>
						<?php } else { ?>
						<?php zm_grid_thumbnail(); ?>
						<?php } ?>

						<?php if ( has_post_format('video') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a></div><?php } ?>
						<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
						<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
					</figure>
					<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<span class="grid-inf">
						<?php if ( has_post_format('link') ) { ?>
							<?php if ( get_post_meta($post->ID, 'link_inf', true) ) { ?>
								<span class="link-inf"><?php $link_inf = get_post_meta($post->ID, 'link_inf', true);{ echo $link_inf;}?></span>
							<?php } else { ?>
								<span class="g-cat"><?php zm_category(); ?></span>
							<?php } ?>
						<?php } else { ?>
							<span class="g-cat"><?php zm_category(); ?></span>
						<?php } ?>
						<span class="grid-inf-l">
							<?php if ( !has_post_format('link') ) { ?><span class="date"><i class="be be-schedule"></i> <?php the_time( 'm/d' ); ?></span><?php } ?>
							<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
							<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
						</span>
		 			</span>
		 			<div class="clear"></div>
				</div>
			</article>
			<?php endwhile;?>
		</main><!-- .site-main -->
		<?php begin_pagenav(); ?>
	</section><!-- .content-area -->

<?php get_footer(); ?>