<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_post')) { ?>
<div class="g-row <?php if (zm_get_option('bg_21')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_post_s'); ?>">
	<div class="g-col">
		<div class="group-post-box">
			<?php
				$posts = get_posts( array(
					'post_type' => 'any',
					'include' => explode(',',zm_get_option('group_post_id') ),
					'ignore_sticky_posts' => 1
				) );
			?>
			<?php if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
			<article id="post-<?php the_ID(); ?>" class="group-post-list grl wow fadeInUp" data-wow-delay="0.3s">
				<div class="group-post-img sup">
					<?php gr_wd_thumbnail(); ?>
				</div>
				
				<div class="group-post-content">
					<?php the_title( sprintf( '<h3 class="group-post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

					<div class="group-post-excerpt" data-wow-delay="0.5s">
						<?php if (has_excerpt('')){
								echo wp_trim_words( get_the_excerpt(), 180, '...' );
							} else {
								// the_excerpt('');
								$content = get_the_content();
								$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
								echo wp_trim_words( $content, 180, '...' );
						    }
						?>
					</div>
					<div class="group-post-more"><a href="<?php the_permalink(); ?>" rel="bookmark">详细查看</a></div>
				</div>
				<div class="clear"></div>
			</article>
			<?php endforeach; endif; ?>
			<?php wp_reset_query(); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>