<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('grid_cat_a')) { ?>
<?php $display_categories = explode(',',zm_get_option('grid_cat_a_id') ); foreach ($display_categories as $category) { ?>
	<?php query_posts( array('cat' => $category ) ); ?>
	<?php query_posts( array( 'showposts' => zm_get_option('grid_cat_a_n'), 'category__in' => array(get_query_var('cat')), 'offset' => 0, 'post__not_in' => $do_not_duplicate ) ); ?>
	<div class="grid-cat-box wow fadeInUp" data-wow-delay="0.3s">
		<div class="grid-cat-title-box">
			<h3 class="grid-cat-title"><a href="<?php echo get_category_link($category);?>" title="<?php _e( '更多', 'begin' ); ?>"><?php single_cat_title(); ?></a></h3>
			<?php if (zm_get_option('grid_cat_a_child')) { ?>
			<ul class="grid-cat_chi-child">
				<?php wp_list_categories('child_of=' . get_category_id($category) . '&depth=0&hierarchical=0&hide_empty=0&title_li=&orderby=id&order=ASC');?>
			</ul>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="grid-cat-site grid-cat-4">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
					<div class="grid-cat-bx4 sup ms">
						<figure class="picture-img">

							<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
								<?php zm_thumbnail_link(); ?>
							<?php } else { ?>
								<?php zm_thumbnail(); ?>
							<?php } ?>

							<?php if ( has_post_format('video') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a></div><?php } ?>
							<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
							<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
							<?php if ( has_post_format('link') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-link"></i></a></div><?php } ?>
						</figure>

						<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
							<?php $direct = get_post_meta($post->ID, 'direct', true); ?>
							<h2 class="grid-title"><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></h2>
						<?php } else { ?>
							<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php } ?>

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
									<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
									<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
									<?php if ( get_post_meta($post->ID, 'mark', true) ) { ?><span class="t-mark grid-inf-mark"><?php $mark = get_post_meta($post->ID, 'mark', true);{ echo $mark;}; ?></span><?php } ?>
								</span>
							<?php } ?>
			 			</span>

			 			<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; ?>
			<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php } ?>