<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="grid-cat-box grid-cat-top wow fadeInUp ms" data-wow-delay="0.3s">
	<div class="grid-cat-site grid-cat-4">
		<?php query_posts( array ( 'meta_key' => 'cms_top', 'showposts' => zm_get_option('cms_top_n'), 'post__not_in' => get_option( 'sticky_posts') ) ); while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
				<div class="grid-cat-bx4 sup ms">
					<figure class="picture-img">
						<?php zm_thumbnail(); ?>
						<?php if ( has_post_format('video') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a></div><?php } ?>
						<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
						<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
					</figure>

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
	</div>
	<div class="clear"></div>
</div>