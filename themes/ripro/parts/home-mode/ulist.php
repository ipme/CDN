<div class="section" style=" padding-bottom: 20px; ">
	  	<div class="container">
			<div class="row">
			
<?php
$mode_ulistpost = _cao('mode_ulistpost');

foreach ($mode_ulistpost['catulist'] as $key => $cms) { 

	$args = array(
	    'cat'            => $cms['category'],
	    'ignore_sticky_posts' => true,
	    'post_status'         => 'publish',
	    'posts_per_page'      => $cms['count'],
	    'orderby'      => $cms['orderby'],
	);

	$data = new WP_Query($args);
	$category = get_category( $cms['category'] );
	$this_i = 0; ?>
	<div class="col-12 col-sm-6">
		<div class="uposts">
		<div class="codesign-list lazyload visible" data-bg="<?php echo esc_url( $cms['bgimg'] ); ?>">
			<h4 class="codeisgn-h4"><i class="fa fa-circle-o"></i>  <a<?php echo _target_blank();?> href="<?php echo esc_url( get_category_link( $category->cat_ID ) ); ?>"><?php echo $category->cat_name; ?> &gt;</a></h4>
			<span class="codesign-esc"><p><?php echo $cms['desc'];?></p></span>
			<div class="codesign-cover"></div>
		</div>
	    <?php while ( $data->have_posts() ) : $data->the_post();
	    $this_i++;
	    echo '<div class="hentry"><h2 class="title"><span class="post-num num-'.$this_i.'">'.$this_i.'</span><a'._target_blank().' href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h2>';
	    echo '<div class="meta"><span>热度('._get_post_views().')</span></div></div>';
	    endwhile; ?>
	    </div>
    </div>
	<?php 
	wp_reset_postdata();
}
?>

		</div>
	</div>
</div>