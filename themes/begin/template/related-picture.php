<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div id="related-img" class="wow fadeInUp ms" data-wow-delay="0.3s">
	<?php 
		$loop = new WP_Query( array( 'post_type' => 'picture', 'posts_per_page' => zm_get_option('related_n'), 'post__not_in' => array($post->ID) ) );
		while ( $loop->have_posts() ) : $loop->the_post();
	?>
	<div class="r4">
		<div class="related-site">
			<figure class="related-site-img">
				<?php zm_thumbnail(); ?>
			 </figure>
			<div class="related-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
		</div>
	</div>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>