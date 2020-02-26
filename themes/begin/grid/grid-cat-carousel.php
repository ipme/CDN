<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('grid_carousel')) { ?>
<div class="grid-carousel-box wow fadeInUp" data-wow-delay="0.3s">
	<?php
		$cat = get_category(zm_get_option('grid_carousel_id'));
		$cat_links=get_category_link($cat->term_id); 
		$args=array( 'include' => zm_get_option('grid_carousel_id') );
		$cats = get_categories($args);
		foreach ( $cats as $cat ) {
			query_posts( 'cat=' . $cat->cat_ID );
	?>
	<div class="grid-cat-title-box">
		<h3 class="grid-cat-title"><a href="<?php echo $cat_links; ?>" title="<?php _e( '更多', 'begin' ); ?>"><?php single_cat_title(); ?></a></h3>
	</div>
	<?php } wp_reset_query(); ?>
	<div class="clear"></div>
	<div id="grid-carousel" class="slider-rolling">
		<?php $loop = new WP_Query( array( 'category__and' => zm_get_option('grid_carousel_id'), 'posts_per_page' => zm_get_option('grid_carousel_n'), 'post__not_in' => get_option( 'sticky_posts') ) );while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('grid-carousel-main sup'); ?> >
			<div class="grid-scrolling-thumbnail"><?php zm_thumbnail_scrolling(); ?></div>
			<div class="clear"></div>
			<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<span class="grid-inf">
				<?php if ( get_post_meta($post->ID, 'link_inf', true) ) { ?>
					<span class="link-inf"><?php $link_inf = get_post_meta($post->ID, 'link_inf', true);{ echo $link_inf;}?></span>
					<span class="grid-inf-l">
					<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-favorite"></i> ','</span>' ); } ?>
					<?php if ( get_post_meta($post->ID, 'mark', true) ) { ?><span class="t-mark grid-inf-mark"><?php $mark = get_post_meta($post->ID, 'mark', true);{ echo $mark;}; ?></span><?php } ?>
					</span>
				<?php } else { ?>
					<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
					<span class="grid-inf-l">
						<span class="date"><i class="be be-schedule"></i> <?php the_time( 'm/d' ); ?></span>
						<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
						<?php if ( get_post_meta($post->ID, 'mark', true) ) { ?><span class="t-mark grid-inf-mark"><?php $mark = get_post_meta($post->ID, 'mark', true);{ echo $mark;}; ?></span><?php } ?>
					</span>
				<?php } ?>
			</span>
			<div class="clear"></div>
		</div>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</div>
</div>
<?php } ?>