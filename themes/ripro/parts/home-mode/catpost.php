<?php

$mode_catpost = _cao('mode_catpost');



foreach ($mode_catpost['catcms'] as $key => $cms) { 

	$args = array(
	    'cat'            => $cms['category'],
	    'ignore_sticky_posts' => true,
	    'post_status'         => 'publish',
	    'posts_per_page'      => $cms['count'],
	    'orderby'      => $cms['orderby'],
	);

	$data = new WP_Query($args);
	$category = get_category( $cms['category'] ); ?>
	<div class="section pb-0">
	  <div class="container">
	  	<h3 class="section-title">
	  		<span><i class="fa fa-th"></i> <a href="<?php echo esc_url( get_category_link( $category->cat_ID ) ); ?>"><?php echo $category->cat_name; ?></a></span>
	  	</h3>
	  	<?php _the_cao_ads('ad_list_header', 'list-header');?>
		<div class="row cat-posts-wrapper">
		    <?php while ( $data->have_posts() ) : $data->the_post();
		      get_template_part( 'parts/template-parts/content',$cms['latest_layout'] );
		    endwhile; ?>
		</div>
		<?php _the_cao_ads('ad_list_footer', 'list-footer');?>
	  </div>
	</div>

	<?php 
	wp_reset_postdata();
}
?>