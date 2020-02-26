<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('dean')) { ?>
<?php
/**
 * 企业布局“服务”模块
 */
?>
<div class="g-row <?php if (zm_get_option('bg_2')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('dean_s'); ?>">
	<div class="g-col">
		<div class="deanm">
			<div class="group-title wow fadeInDown" data-wow-delay="0.3s">
				<?php if ( zm_get_option('dean_t') == '' ) { ?>
				<?php } else { ?>
					<h3><?php echo zm_get_option('dean_t'); ?></h3>
				<?php } ?>
				<div class="group-des"><?php echo zm_get_option('dean_des'); ?></div>
				<div class="clear"></div>
			</div>
			<div class="deanm-main wow fadeInUp" data-wow-delay="0.3s">
				<?php $posts = get_posts( array( 'post_type' => 'any', 'meta_key' => 'pr_a', 'numberposts' => '16') ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
					<?php 
						$pr_a = get_post_meta($post->ID, 'pr_a', true);
						$pr_b = get_post_meta($post->ID, 'pr_b', true);
						$pr_c = get_post_meta($post->ID, 'pr_c', true);
						$pr_d = get_post_meta($post->ID, 'pr_d', true);
						$pr_e = get_post_meta($post->ID, 'pr_e', true);
						$pr_f = get_post_meta($post->ID, 'pr_f', true);
					?>
				<div class="deanm sup deanmove">
					<?php the_title( sprintf( '<div class="de-t wow fadeIn" data-wow-delay="0.5s"><a href="%s" rel="bookmark">', esc_url( $pr_d ) ), '</a></div>' ); ?>
					<div class="clear"></div>
					<div class="de-a wow fadeIn" data-wow-delay="0.7s"><?php echo $pr_b; ?></div>
					<div class="deanquan sup">
						<a href="<?php echo $pr_d; ?>" target="_blank">
							<div class="de-back">
								<?php if ( get_post_meta($post->ID, 'pr_f', true) ) { ?><img src="<?php echo get_template_directory_uri().'/prune.php?src='.$pr_f.'&w=200&h=200&zc=1'; ?>" alt="<?php the_title(); ?>"><?php } else { ?><img src="http://wx1.sinaimg.cn/large/0066LGKLly1fgbmwz3draj305u05uabc.jpg" alt="广告也精彩" /><?php } ?>
								<div class="de-b wow fadeIn" data-wow-delay="0.8s"><?php echo $pr_a; ?></div>
							</div>
						</a>
					</div>
					<div class="de-c wow fadeIn" data-wow-delay="0.9s"><?php echo $pr_c; ?></div>
					<div class="de-button wow fadeIn" data-wow-delay="1s">
						<a href="<?php echo $pr_d; ?>" target="_blank"><?php if ( get_post_meta($post->ID, 'pr_e', true) ) { ?><?php echo $pr_e; ?><?php } else { ?><i class="be be-stack"></i>按钮名称<?php } ?></a>
					</div>
				</div>
				<?php endforeach; endif; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>