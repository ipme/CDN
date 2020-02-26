<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_cat_a')) { ?>
<div class="g-row <?php if (zm_get_option('bg_14')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_cat_a_s'); ?>">
	<div class="g-col">
		<div class="group-cat">
			<?php $display_categories =  explode(',',zm_get_option('group_cat_a_id') ); foreach ($display_categories as $category) { ?>
			<?php query_posts( array( 'showposts' => 1, 'cat' => $category, 'post__not_in' => $do_not_cat ) ); ?>

			<div class="gr2">
				<div class="gr-cat-box">
					<h3 class="gr-cat-title"><a class="wow fadeInDown" data-wow-delay="0.5s" href="<?php echo get_category_link($category);?>" title="<?php echo strip_tags(category_description($category)); ?>"><?php single_cat_title(); ?></a></h3>
					<div class="gr-cat-more wow fadeInDown" data-wow-delay="0.7s"><a href="<?php echo get_category_link($category);?>"><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a></div>
					<div class="clear"></div>
					<div class="gr-cat-site">
						<?php if (zm_get_option('group_cat_a_top')) { ?>
							
							<?php query_posts( array ( 'category__in' => array(get_query_var('cat')), 'meta_key' => 'cat_top', 'showposts' => 1, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); $do_not_cat[] = $post->ID;?>
								<div id="post-<?php the_ID(); ?>" class="gr-img-t wow fadeInUp" data-wow-delay="0.3s">
									<figure class="gr-thumbnail"><?php zm_long_thumbnail(); ?></figure>
									<?php the_title( sprintf( '<h2 class="gr-title-img wow fadeInUp" data-wow-delay="0.7s"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									<div class="clear"></div>
								</div>
							<?php endwhile; ?>

							<div class="clear"></div>
							<div class="gr-cat-img">
								<?php query_posts( array( 'showposts' => zm_get_option('group_cat_a_n'), 'cat' => $category, 'offset' => 0, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_cat ) ); ?>
								<?php while ( have_posts() ) : the_post(); ?>
								<div class="cat-gr2">
									<div id="post-<?php the_ID(); ?>" class="gr-img wow fadeInUp" data-wow-delay="0.3s">
										<figure class="gr-a-thumbnail picture-cms-img"><?php zm_thumbnail(); ?></figure>
										<?php the_title( sprintf( '<div class="gr-img-title wow fadeIn" data-wow-delay="0.7s"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></div>' ); ?>
										<div class="clear"></div>
									</div>
								</div>
								<?php endwhile; ?>
								<?php wp_reset_query(); ?>
							</div>


						<?php } else { ?>

							<?php while ( have_posts() ) : the_post(); ?>
								<div id="post-<?php the_ID(); ?>" class="gr-img-t wow fadeInUp" data-wow-delay="0.5s">
									<figure class="gr-thumbnail"><?php zm_long_thumbnail(); ?></figure>
									<?php the_title( sprintf( '<h2 class="gr-title-img wow fadeInUp" data-wow-delay="0.7s"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									<div class="clear"></div>
								</div>
							<?php endwhile; ?>

							<div class="clear"></div>
							<div class="gr-cat-img">
								<?php query_posts( array( 'showposts' => zm_get_option('group_cat_a_n'), 'cat' => $category, 'offset' => 1, 'post__not_in' => $do_not_cat ) ); ?>
								<?php while ( have_posts() ) : the_post(); ?>
								<div id="post-<?php the_ID(); ?>" class="cat-gr2 wow fadeInUp" data-wow-delay="0.5s">
									<div class="gr-img">
										<figure class="gr-a-thumbnail picture-cms-img"><?php zm_thumbnail(); ?></figure>
										<?php the_title( sprintf( '<div class="gr-img-title wow fadeIn" data-wow-delay="0.7s"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></div>' ); ?>
										<div class="clear"></div>
									</div>
								</div>
								<?php endwhile; ?>
								<?php wp_reset_query(); ?>
							</div>

						<?php } ?>
					</div>
				</div>

			</div>
			<?php } ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>