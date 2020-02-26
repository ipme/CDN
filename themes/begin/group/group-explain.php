<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_explain')) { ?>
<?php
/**
 * 公司布局“说明”模块
 */
?>
<div class="explain <?php if (zm_get_option('bg_9')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_explain_s'); ?>">
	<div class="g-row">
		<div class="g-col">
			<div class="section-box">
				<?php
					$args = array(
						'post_type' => 'page', 
						'p' => zm_get_option('explain_p'),
					);
					query_posts($args);
				?>
				<?php while (have_posts()) : the_post(); ?>
				<div class="group-title wow fadeInDown" data-wow-delay="0.5s">
					<?php if ( zm_get_option('group_explain_t') == '' ) { ?>
					<?php } else { ?>
						<h3><a href="<?php the_permalink(); ?>" target="_blank" rel="bookmark"><?php echo zm_get_option('group_explain_t'); ?></a></h3>
					<?php } ?>
					<div class="clear"></div>
				</div>

					<div class="group-explain">
						<div class="group-explain-main single-content wow fadeInUp" data-wow-delay="0.5s">
							<?php global $more; $more = 0; the_content( '' ); ?>
						</div>
						<div class="clear"></div>
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