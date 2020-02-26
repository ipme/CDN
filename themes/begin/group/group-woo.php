<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('g_product')) { ?>
<?php
/**
 * 企业布局“WOO产品”模块
 */
?>
<div class="g-row <?php if (zm_get_option('bg_5')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('g_product_s'); ?>">
	<div class="g-col">
		<div class="group-features g-woo">
			<div class="group-title wow fadeInDown" data-wow-delay="0.5s">
				<?php if ( zm_get_option('g_product_t') == '' ) { ?>
				<?php } else { ?>
				<h3><?php echo zm_get_option('g_product_t'); ?></h3>
				<?php } ?>
				<div class="group-des"><?php echo zm_get_option('g_product_des'); ?></div>
				<div class="clear"></div>
			</div>
			<div class="section-box">
				<?php 
					$args = array('tax_query' => array( array('taxonomy' => 'product_cat', 'field' => 'id', 'terms' => explode(',',zm_get_option('g_product_id') ))), 'posts_per_page' => zm_get_option('g_product_n'));
					query_posts($args); while ( have_posts() ) : the_post();
				?>
				<div class="g4">
					<div class="box-4 wow fadeInUp" data-wow-delay="0.3s">
						<figure class="picture-cms-img">
							<?php tao_thumbnail(); ?>
							<span class="woo-t"><i class="be be-basket"></i></span>
						</figure>
						<?php the_title( sprintf( '<h3 class="g4-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					</div>
				</div>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
				<?php if ( zm_get_option('g_product_url') == '' ) { ?>
				<?php } else { ?>
					<div class="img-more"><a href="<?php echo zm_get_option('g_product_url'); ?>"><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a></div>
				<?php } ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>