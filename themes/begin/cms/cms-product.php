<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('product_h')) { ?>
<div class="line-tao sort" name="<?php echo zm_get_option('product_h_s'); ?>">
	<?php 
		$args = array('tax_query' => array( array('taxonomy' => 'product_cat', 'field' => 'id', 'terms' => explode(',',zm_get_option('product_h_id') ))), 'posts_per_page' => zm_get_option('product_h_n'));
		query_posts($args); while ( have_posts() ) : the_post();
	?>

	<div class="xl4 xm4">
		<div class="tao-h wow fadeInUp ms" data-wow-delay="0.3s">
			<figure class="tao-h-img">
				<?php
				do_action( 'woocommerce_before_shop_loop_item' );
				do_action( 'woocommerce_before_shop_loop_item_title' );
				?>
			</figure>
			<?php the_title( sprintf( '<h3 class="woo-h-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
		</div>
	</div>

	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php } ?>