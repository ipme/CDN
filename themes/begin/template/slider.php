<?php 
// 首页幻灯
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('slider')) { ?>
<div id="slideshow" class="wow fadeInUp ms" data-wow-delay="0.3s">
	<div id="slider-home" class="slider-home">
		<?php if (zm_get_option('show_order')) { ?>
			<?php
				$posts = get_posts( array(
					'numberposts' => zm_get_option('slider_n'),
					'post_type' => 'any',
					'meta_key' => 'show',
					'meta_key' => 'show_order',
					'orderby' => 'meta_value',
					'order' => 'date',
					'ignore_sticky_posts' => 1
				) );
			?>
		<?php } else { ?>
			<?php
				$posts = get_posts( array(
					'numberposts' => zm_get_option('slider_n'),
					'post_type' => 'any',
					'meta_key' => 'show',
					'ignore_sticky_posts' => 1
				) );
			?>
		<?php } ?>
		<?php if($posts) : foreach( $posts as $post ) : setup_postdata( $post );$do_not_duplicate[] = $post->ID; $do_show[] = $post->ID; ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
				<?php $image = get_post_meta($post->ID, 'show', true); ?>
				<?php $go_url = get_post_meta($post->ID, 'show_url', true); ?>
				<?php if (zm_get_option('show_img_crop')) { ?>
					<?php if ( get_post_meta($post->ID, 'show_url', true) ) : ?>
					<a href="<?php echo $go_url; ?>" target="_blank"><img src="<?php echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_h_w').'&h='.zm_get_option('img_h_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" alt="<?php the_title(); ?>" /></a>
					<?php else: ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark"><img src="<?php echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_h_w').'&h='.zm_get_option('img_h_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" alt="<?php the_title(); ?>" /></a>
					<?php endif; ?>
				<?php } else { ?>
					<?php if ( get_post_meta($post->ID, 'show_url', true) ) : ?>
					<a href="<?php echo $go_url; ?>" target="_blank"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" /></a>
					<?php else: ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" /></a>
					<?php endif; ?>
				<?php } ?>

				<?php if ( get_post_meta($post->ID, 'no_slide_title', true) ) : ?>
				<?php else: ?>
					<?php if ( get_post_meta($post->ID, 'slide_title', true) ) : ?>
					<?php $slide_title = get_post_meta($post->ID, 'slide_title', true); ?>
						<p class="slider-home-title slider-home-title-custom"><?php echo $slide_title; ?></p>
					<?php else: ?>
						<p class="slider-home-title"><?php the_title(); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endforeach; endif; ?>
		<?php wp_reset_query(); ?>
	</div>
	<div class="clear"></div>
</div>
<?php } ?>