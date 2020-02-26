<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_contact')) { ?>
<?php
/**
 * 企业布局“关于”模块
 */
?>
<div class="contact <?php if (zm_get_option('bg_1')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_contact_s'); ?>">
	<div class="g-row">
		<div class="g-col">
			<div class="section-box">
				<div class="group-title wow fadeInDown" data-wow-delay="0.5s">
					<?php if ( zm_get_option('group_contact_t') == '' ) { ?>
					<?php } else { ?>
						<h3><?php echo zm_get_option('group_contact_t'); ?></h3>
					<?php } ?>
					<div class="clear"></div>
				</div>
				<?php
					$args = array(
						'post_type' => 'page', 
						'p' => zm_get_option('contact_p'),
					);
					query_posts($args);
				?>
				<?php while (have_posts()) : the_post(); ?>
					<div class="group-contact">
						<?php if (zm_get_option('tr_contact')) { ?>
						<div class="group-contact-main single-content wow fadeIn" data-wow-delay="0.9s">
						<?php } else { ?>
						<div class="group-contact-main-all single-content wow fadeIn" data-wow-delay="0.9s">
						<?php } ?>
							<?php global $more; $more = 0; the_content( '' ); ?>
						</div>
						<div class="clear"></div>
						<div class="group-contact-more">
							<span class="group-more">
								<?php if ( zm_get_option('group_more_url') == '' ) { ?>
									<a class="wow fadeInLeft" data-wow-delay="0.8s" href="<?php the_permalink(); ?>" target="_blank" rel="bookmark"><i class="be be-stack"></i><?php echo zm_get_option('group_more_z'); ?></a>
								<?php } else { ?>
									<a class="wow fadeInLeft" data-wow-delay="0.8s" href="<?php echo zm_get_option('group_more_url'); ?>" rel="bookmark" target="_blank"><i class="be be-stack"></i> <?php echo zm_get_option('group_more_z'); ?></a>
								<?php } ?>
							</span>
							<span class="group-phone"><a class="wow fadeInRight" data-wow-delay="0.8s" href="<?php echo  zm_get_option('group_contact_url'); ?>" rel="bookmark" target="_blank"><i class="be be-phone"></i> <?php echo zm_get_option('group_contact_z'); ?></a></span>
							<div class="clear"></div>
						</div>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>