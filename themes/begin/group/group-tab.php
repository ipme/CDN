<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_tab')) { ?>
<div class="g-row <?php if (zm_get_option('bg_17')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_tab_s'); ?>">
	<div class="g-col">
		<div class="group-tab-site">
			<div id="group-tab" class="group-tab-product">
			    <h3 class="group-tab-hd wow fadeInUp" data-wow-delay="0.3s">
				    <span class="group-tab-hd-con"><?php echo zm_get_option('anli_t'); ?></span>
				    <span class="group-tab-hd-con"><span><i class="be be-arrowright"></i></span><?php echo zm_get_option('cp_t'); ?></span>
				    <?php if ( zm_get_option('sb_t') == '' ) { ?><?php } else { ?><span class="group-tab-hd-con"><span><i class="be be-arrowright"></i></span><?php echo zm_get_option('sb_t'); ?></span><?php } ?>
				    <?php if ( zm_get_option('by_t') == '' ) { ?><?php } else { ?><span class="group-tab-hd-con"><span><i class="be be-arrowright"></i></span><?php echo zm_get_option('by_t'); ?></span><?php } ?>
			    </h3>
				<div class="clear"></div>

				<div class="group-tab-bd group-dom-display wow fadeIn" data-wow-delay="0.5s">

					<div class="group-tab-bd-con group-current">
						<?php query_posts( array( 'showposts' => zm_get_option('group_tab_n'), 'cat' => zm_get_option('anli_id'), 'post__not_in' => $do_not_duplicate ) ); while (have_posts()) : the_post(); ?>

						<div class="xl4 xm4">
							<div id="post-<?php the_ID(); ?>" class="picture">
								<figure class="picture-cms-img">
									<?php zm_thumbnail_a(); ?>
								</figure>
								<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<div class="group-tab-meta">
									<div class="group-date"><?php time_ago( $time_type ='post' ); ?></div>
									<?php if( function_exists( 'the_views' ) ) { the_views( true, '<div class="group-views"><i class="be be-eye"></i> ','</div>' ); } ?>
									<div class="clear"></div>
								</div>
							</div>
						</div>

						<?php endwhile; ?>
						<div class="clear"></div>
						<div class="group-tab-more">
							<?php
							$cat=get_term_by('id', zm_get_option('anli_id'), 'category');
							$cat_links=get_category_link($cat->term_id);
							?>
							<a href="<?php echo $cat_links; ?>" title="<?php echo $cat->name; ?>"><?php _e( '更多', 'begin' ); ?></a>
						</div>
						<?php wp_reset_query(); ?>
						<div class="clear"></div>
					</div>

					<div class="group-tab-bd-con">
						<?php query_posts( array( 'showposts' => zm_get_option('group_tab_n'), 'cat' => zm_get_option('cp_id'), 'post__not_in' => $do_not_duplicate ) ); while (have_posts()) : the_post(); ?>

						<div class="xl4 xm4">
							<div id="post-<?php the_ID(); ?>" class="picture">
								<figure class="picture-cms-img">
									<?php zm_thumbnail_a(); ?>
								</figure>
								<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<div class="group-tab-meta">
									<div class="group-date"><?php time_ago( $time_type ='post' ); ?></div>
									<?php if( function_exists( 'the_views' ) ) { the_views( true, '<div class="group-views"><i class="be be-eye"></i> ','</div>' ); } ?>
									<div class="clear"></div>
								</div>
							</div>
						</div>

						<?php endwhile; ?>
						<div class="clear"></div>
						<div class="group-tab-more">
							<?php
							$cat=get_term_by('id', zm_get_option('cp_id'), 'category');
							$cat_links=get_category_link($cat->term_id);
							?>
							<a href="<?php echo $cat_links; ?>" title="<?php echo $cat->name; ?>"><?php _e( '更多', 'begin' ); ?></a>
						</div>
						<?php wp_reset_query(); ?>
						<div class="clear"></div>
					</div>

                    <?php if ( zm_get_option('sb_t')) { ?>
					<div class="group-tab-bd-con">
						<?php query_posts( array( 'showposts' => zm_get_option('group_tab_n'), 'cat' => zm_get_option('sb_id'), 'post__not_in' => $do_not_duplicate ) ); while (have_posts()) : the_post(); ?>

						<div class="xl4 xm4">
							<div id="post-<?php the_ID(); ?>" class="picture">
								<figure class="picture-cms-img">
									<?php zm_thumbnail_a(); ?>
								</figure>
								<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<div class="group-tab-meta">
									<div class="group-date"><?php time_ago( $time_type ='post' ); ?></div>
									<?php if( function_exists( 'the_views' ) ) { the_views( true, '<div class="group-views"><i class="be be-eye"></i> ','</div>' ); } ?>
									<div class="clear"></div>
								</div>
							</div>
						</div>

						<?php endwhile; ?>
						<div class="clear"></div>
						<div class="group-tab-more">
							<?php
							$cat=get_term_by('id', zm_get_option('sb_id'), 'category');
							$cat_links=get_category_link($cat->term_id);
							?>
							<a href="<?php echo $cat_links; ?>" title="<?php echo $cat->name; ?>"><?php _e( '更多', 'begin' ); ?></a>
						</div>
						<?php wp_reset_query(); ?>
						<div class="clear"></div>
					</div>
					<?php } ?>

                    <?php if ( zm_get_option('by_t')) { ?>
					<div class="group-tab-bd-con">
						<?php query_posts( array( 'showposts' => zm_get_option('group_tab_n'), 'cat' => zm_get_option('by_id'), 'post__not_in' => $do_not_duplicate ) ); while (have_posts()) : the_post(); ?>

						<div class="xl4 xm4">
							<div id="post-<?php the_ID(); ?>" class="picture">
								<figure class="picture-cms-img">
									<?php zm_thumbnail_a(); ?>
								</figure>
								<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<div class="group-tab-meta">
									<div class="group-date"><?php time_ago( $time_type ='post' ); ?></div>
									<?php if( function_exists( 'the_views' ) ) { the_views( true, '<div class="group-views"><i class="be be-eye"></i> ','</div>' ); } ?>
									<div class="clear"></div>
								</div>
							</div>
						</div>

						<?php endwhile; ?>
						<div class="clear"></div>
						<div class="group-tab-more">
							<?php
							$cat=get_term_by('id', zm_get_option('by_id'), 'category');
							$cat_links=get_category_link($cat->term_id);
							?>
							<a href="<?php echo $cat_links; ?>" title="<?php echo $cat->name; ?>"><?php _e( '更多', 'begin' ); ?></a>
						</div>
						<?php wp_reset_query(); ?>
						<div class="clear"></div>
					</div>
					<?php } ?>

				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>