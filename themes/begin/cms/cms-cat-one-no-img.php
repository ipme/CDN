<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cat_one_on_img')) { ?>
<div class="line-one sort" name="<?php echo zm_get_option('cat_one_on_img_s'); ?>">
	<?php $display_categories =  explode(',',zm_get_option('cat_one_on_img_id') ); foreach ($display_categories as $category) { ?>
	<?php if (zm_get_option('no_cat_child')) { ?>
		<?php query_posts( array('cat' => $category ) ); ?>
		<?php query_posts( array( 'showposts' => 2, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } else { ?>
		<?php query_posts( array( 'showposts' => 2, 'cat' => $category, 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } ?>

		<div class="cat-container wow fadeInUp ms" data-wow-delay="0.3s">
			<h3 class="cat-title"><a href="<?php echo get_category_link($category);?>">
				<?php if (zm_get_option('cat_icon')) { ?>
					<i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i>
				<?php } else { ?>
					<?php title_i(); ?>
				<?php } ?>
				<?php single_cat_title(); ?><?php more_i(); ?></a>
			</h3>
			<div class="clear"></div>
			<div class="cat-site">
				<ul class="cat-one-list">
					<?php if (zm_get_option('no_cat_child')) { ?>
						<?php query_posts( array(  'showposts' => zm_get_option('cat_one_on_img_n'), 'cat' => $category, 'offset' => 0, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_duplicate ) ); ?>
					<?php } else { ?>
						<?php query_posts( array( 'showposts' => zm_get_option('cat_one_on_img_n'), 'cat' => $category, 'offset' => 0, 'post__not_in' => $do_not_duplicate ) ); ?>
					<?php } ?>

					<?php while ( have_posts() ) : the_post(); ?>
						<?php if (zm_get_option('list_date')) { ?><li class="list-date"><?php the_time('m/d'); ?></li><?php } ?>
						<?php if ( get_post_meta($post->ID, 'mark', true) ) {
							the_title( sprintf( '<li class="list-cat-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a><span class="t-mark">' . $mark = get_post_meta($post->ID, 'mark', true) . '</span></li>' );
						} else {
							the_title( sprintf( '<li class="list-cat-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' );
						} ?>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
	<?php } ?>
</div>
<?php } ?>