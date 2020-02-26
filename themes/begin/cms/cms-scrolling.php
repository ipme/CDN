<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('flexisel')) { ?>
<div class="slider-rolling-box wow fadeInUp sort ms" data-wow-delay="0.3s" name="<?php echo zm_get_option('flexisel_s'); ?>">
	<div id="slider-rolling" class="slider-rolling">
		<?php 
			if (zm_get_option('gallery_post')) {
				$loop = new WP_Query(array('tax_query' => array( array('taxonomy' => 'gallery', 'field' => 'id', 'terms' => explode(',',zm_get_option('gallery_id') ))), 'posts_per_page' => zm_get_option('flexisel_n')) );
			} else {
				$loop = new WP_Query( array( 'meta_key' => zm_get_option('key_n'), 'posts_per_page' => zm_get_option('flexisel_n'), 'post__not_in' => get_option( 'sticky_posts') ) );
			}
			while ( $loop->have_posts() ) : $loop->the_post();
		?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('scrolling-img'); ?> >
			<div class="scrolling-thumbnail"><?php zm_thumbnail_scrolling(); ?></div>
			<?php the_title( sprintf( '<h2 class="carousel-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</div>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</div>
</div>
<?php } ?>