<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cms_special')) { ?>
<div class="cat-cover-box sort" name="1">
	<?php $posts = get_posts( array( 'post_type' => 'any', 'include' => zm_get_option('cms_special_id'), 'ignore_sticky_posts' => 1 ) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
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
<?php } ?>