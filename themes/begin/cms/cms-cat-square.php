<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cat_square')) { ?>
<div class="cms-cat-square wow fadeInUp sort" data-wow-delay="0.5s" name="<?php echo zm_get_option('cat_square_s'); ?>">
	<?php $display_categories =  explode(',',zm_get_option('cat_square_id') ); foreach ($display_categories as $category) { ?>
	<?php if (zm_get_option('no_cat_child')) { ?>
		<?php query_posts( array('cat' => $category ) ); ?>
		<?php query_posts( array( 'showposts' => zm_get_option('cat_square_n'), 'category__in' => array(get_query_var('cat')), 'offset' => 0, 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } else { ?>
		<?php query_posts( array( 'showposts' => zm_get_option('cat_square_n'), 'cat' => $category, 'offset' => 0, 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } ?>

	<h3 class="cat-square-title">
		<a href="<?php echo get_category_link($category);?>">
		<?php if (zm_get_option('cat_icon')) { ?>
			<i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i>
		<?php } else { ?>
			<span class="title-i"><span></span><span></span><span></span><span></span></span>
		<?php } ?>
		<?php single_cat_title(); ?><span class="more-i"><span></span><span></span><span></span></span></a>
	</h3>
	<div class="cat-g5">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(' ms'); ?>>
				<figure class="thumbnail">
					<?php zm_thumbnail(); ?>
				</figure>
				<header class="entry-header entry-header-square">
					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</header>
			</article>
			<?php endwhile; ?>
			<div class="clear"></div>
			<?php wp_reset_query(); ?>
		</div>
	<?php } ?>
</div>
<?php } ?>