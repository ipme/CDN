<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('service')) { ?>
<?php
/**
 * 企业布局“服务宗旨”模块
 */
?>
<div id="service-bg" class="g-row <?php if (zm_get_option('bg_4')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('service_s'); ?>">
	<div class="g-col">
		<div class="group-service-box">
			<div class="group-title group-service-title wow fadeInDown" data-wow-delay="0.5s">
					<?php if ( zm_get_option('service_t') == '' ) { ?>
					<?php } else { ?>
						<h3><?php echo zm_get_option('service_t'); ?></h3>
					<?php } ?>
					<?php if ( zm_get_option('service_des') ) { ?><div class="group-des"><?php echo zm_get_option('service_des'); ?></div><?php } ?>
				<div class="clear"></div>
			</div>
			<div class="group-service group-service-c">
				<div class="group-service-des">
					<img class="wow fadeInDown" data-wow-delay="0.5s" src="<?php echo zm_get_option('service_c_img'); ?>" alt="service" />
					<?php $posts = get_posts( array( 'post_type' => 'any', 'include' =>zm_get_option('service_c_id')) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
					<div class="clear"></div>
					<?php if ( zm_get_option('service_c_id') == '' ) { ?>
					<?php } else { ?>
						<div class="group-service-content wow fadeInUp" data-wow-delay="0.3s">
							<?php 
								$content = get_the_content();
								$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
								echo wp_trim_words( $content, 200, '' );
							?>
						</div>
					<?php } ?>
					<?php endforeach; endif; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
				</div>
			</div>

			<div class="group-service group-service-l">
				<div class="service-box">
					<?php $posts = get_posts( array( 'post_type' => 'any', 'include' =>zm_get_option('service_l_id')) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
					<div class="p4">
						<div class="p-4 wow fadeInLeft" data-wow-delay="0.3s">
							<figure class="service-thumbnail">
								<?php tao_thumbnail(); ?>
							</figure>
							<h3 class="p4-title"><?php echo wp_trim_words( get_the_title(), 120 ); ?>
								<div class="p4-content">
									<?php 
										$content = get_the_content();
										$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
										echo wp_trim_words( $content, 50, '' );
									?>
								</div>
							</h3>
						</div>
					</div>
					<?php endforeach; endif; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
				</div>
			</div>

			<div class="group-service group-service-r">
				<div class="service-box">
					<?php $posts = get_posts( array( 'post_type' => 'any', 'include' =>zm_get_option('service_r_id')) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
					<div class="p4">
						<div class="p-4 wow fadeInRight" data-wow-delay="0.5s">
							<figure class="service-thumbnail">
								<?php tao_thumbnail(); ?>
							</figure>
							<h3 class="p4-title"><?php echo wp_trim_words( get_the_title(), 120 ); ?>
								<div class="p4-content">
									<?php 
										$content = get_the_content();
										$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
										echo wp_trim_words( $content, 50, '' );
									?>
								</div>
							</h3>
						</div>
					</div>
					<?php endforeach; endif; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
				</div>
			</div>

			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php add_action('wp_footer', 'service_bg'); ?>
<?php } ?>