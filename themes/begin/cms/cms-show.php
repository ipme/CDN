<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('products_on')) { ?>
<div class="line-four sort" name="<?php echo zm_get_option('products_on_s'); ?>">
	<?php 
		$args = array('tax_query' => array( array('taxonomy' => 'products', 'field' => 'id', 'terms' => explode(',',zm_get_option('products_id') ))), 'posts_per_page' => zm_get_option('products_n'));
		query_posts($args); while ( have_posts() ) : the_post();
	?>

	<div class="xl4 xm4">
		<div class="picture-cms wow fadeInUp ms" data-wow-delay="0.3s">
			<figure class="picture-cms-img">
				<?php img_thumbnail(); ?>
				<span class="show-t"></span>
			</figure>
			<?php the_title( sprintf( '<h2 class="picture-s-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</div>
	</div>

	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php } ?>