<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_products')) { ?>
<?php
/**
 * 企业布局“产品日志”模块
 */
?>
<div class="g-row <?php if (zm_get_option('bg_3')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_products_s'); ?>">
	<div class="g-col">
		<div class="group-features">
			<div class="group-title wow fadeInDown" data-wow-delay="0.3s">
				<?php if ( zm_get_option('group_products_t') == '' ) { ?>
				<?php } else { ?>
				<h3><?php echo zm_get_option('group_products_t'); ?></h3>
				<?php } ?>
				<div class="group-des"><?php echo zm_get_option('group_products_des'); ?></div>
				<div class="clear"></div>
			</div>
			<div class="section-box">
				<?php 
					$args = array('tax_query' => array( array('taxonomy' => 'products', 'field' => 'id', 'terms' => explode(',',zm_get_option('group_products_id') ))), 'posts_per_page' => zm_get_option('group_products_n'));
					query_posts($args); while ( have_posts() ) : the_post();
				?>
				<div class="g4">
					<div class="box-4 wow fadeInUp" data-wow-delay="0.3s">
						<figure class="picture-cms-img">
							<?php img_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h3 class="g4-title wow fadeIn" data-wow-delay="0.7s"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					</div>
				</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
				<?php if ( zm_get_option('group_products_url') == '' ) { ?>
				<?php } else { ?>
					<div class="img-more"><a href="<?php echo zm_get_option('group_products_url'); ?>"><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a></div>
				<?php } ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>