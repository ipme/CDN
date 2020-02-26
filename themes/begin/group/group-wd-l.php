<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_wd_l')) { ?>
<div class="g-row <?php if (zm_get_option('bg_7')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_wd_l_s'); ?>">
	<div class="g-col">
		<div class="gr-wd-box">
			<?php $display_categories =  explode(',',zm_get_option('group_wd_l_id') ); foreach ($display_categories as $category) { ?>
			<?php query_posts( array( 'showposts' => 1, 'cat' => $category, 'post__not_in' => $do_not_cat ) ); ?>
				<div class="gr-cat-wd">
					<h3 class="gr-cat-wd-title wow fadeInLeft" data-wow-delay="0.3s"><a href="<?php echo get_category_link($category);?>"><?php single_cat_title(); ?><span class="more-i"><span></span><span></span><span></span></span></a></h3>
					<div class="clear"></div>
				</div>

				<div class="gr-wd-b">
					<div class="gr-wd gr-wd-img gr-wd-img-l wow fadeInLeft" data-wow-delay="0.3s">
						<?php query_posts( array ( 'category__in' => array(get_query_var('cat')), 'meta_key' => 'cat_top', 'showposts' => 1, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); $do_not_cat[] = $post->ID;?>
							<?php gr_wd_thumbnail(); ?>
						<?php endwhile; ?>
					</div>

					<div class="gr-wd gr-wd-w gr-wd-l wow fadeInRight" data-wow-delay="0.3s">
					<?php query_posts( array ( 'category__in' => array(get_query_var('cat')), 'meta_key' => 'cat_top', 'showposts' => 1, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); $do_not_cat[] = $post->ID;?>
						<?php the_title( sprintf( '<h3 class="gr-title gr-wd-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

						<p class="wow fadeInUp" data-wow-delay="0.5s">
							<?php if (has_excerpt('')){
									echo wp_trim_words( get_the_excerpt(), 92, '...' );
								} else {
									$content = get_the_content();
									$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
									echo wp_trim_words( $content, 95, '...' );
							    }
							?>
						</p>

						<?php endwhile; ?>

						<ul>
							<?php query_posts( array( 'showposts' => zm_get_option('group_wd_l_id_n'), 'cat' => $category, 'offset' => 0, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_cat ) ); ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php the_title( sprintf( '<li class="list-title wow fadeInUp" data-wow-delay="0.5s"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
							<?php endwhile; ?>
							<?php wp_reset_query(); ?>
						</ul>

					</div>
					<div class="clear"></div>
				</div>
			<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>