<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_tool')) { ?>
<?php
/**
 * “工具”模块
 */
?>

<div class="g-row <?php if (zm_get_option('bg_20')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('tool_s'); ?>">
	<div class="g-col">
		<div class="group-tool-box">
			<div class="group-title wow fadeInDown" data-wow-delay="0.3s">
				<?php if ( zm_get_option('tool_t') == '' ) { ?>
				<?php } else { ?>
					<h3><?php echo zm_get_option('tool_t'); ?></h3>
				<?php } ?>
				<div class="group-des"><?php echo zm_get_option('tool_des'); ?></div>
				<div class="clear"></div>
			</div>
			<?php $posts = get_posts( array( 'post_type' => 'any', 'meta_key' => 'tool_ico', 'numberposts' => '16') ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
			<?php 
				$tool_ico = get_post_meta($post->ID, 'tool_ico', true);
				$tool_button = get_post_meta($post->ID, 'tool_button', true);
				$tool_url = get_post_meta($post->ID, 'tool_url', true);
			?>
			<div class="sx4">
				<div class="group-tool sup wow fadeInUp" data-wow-delay="0.3s">
					<div class="group-tool-img"><div class="group-tool-img-top"></div><?php zm_long_thumbnail(); ?></div>
					<div class="group-tool-pu">
						<div class="group-tool-ico"><i class="<?php echo $tool_ico; ?>"></i></div>
						<?php if ( get_post_meta($post->ID, 'tool_button', true) ) { ?>
							<h3 class="group-tool-title wow fadeIn" data-wow-delay="0.5s"><?php the_title(); ?></h3>
						<?php } else { ?>
							<a href="<?php the_permalink(); ?>" target="_blank" rel="external nofollow"><h3 class="group-tool-title wow fadeIn" data-wow-delay="0.5s"><?php the_title(); ?></h3></a>
						<?php } ?>
			
						<p class="group-tool-p">
							<?php 
								$content = get_the_content();
								$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
								echo wp_trim_words( $content, 42, '...' );
							?>
						</p>

						<?php if ( get_post_meta($post->ID, 'tool_button', true) ) { ?>
							<div class="group-tool-link"><a href="<?php if ( get_post_meta($post->ID, 'tool_url', true) ) { ?><?php echo $tool_url; ?><?php } else { ?><?php the_permalink(); ?><?php } ?>" target="_blank" rel="external nofollow"><?php echo $tool_button; ?></a></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php endforeach; endif; ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>