<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cat_grid')) { ?>
<div class="cms-cat-grid wow fadeInUp sort" data-wow-delay="0.5s" name="<?php echo zm_get_option('cat_grid_s'); ?>">
	<?php $display_categories =  explode(',',zm_get_option('cat_grid_id') ); foreach ($display_categories as $category) { ?>
	<?php if (zm_get_option('no_cat_child')) { ?>
		<?php query_posts( array('cat' => $category ) ); ?>
		<?php query_posts( array( 'showposts' => zm_get_option('cat_grid_n'), 'category__in' => array(get_query_var('cat')), 'offset' => 0, 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } else { ?>
		<?php query_posts( array( 'showposts' => zm_get_option('cat_grid_n'), 'cat' => $category, 'offset' => 0, 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } ?>

		<h3 class="cat-grid-title">
			<a href="<?php echo get_category_link($category);?>">
			<?php if (zm_get_option('cat_icon')) { ?>
				<i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i>
			<?php } else { ?>
				<span class="title-i"><span></span><span></span><span></span><span></span></span>
			<?php } ?>
			<?php single_cat_title(); ?><span class="more-i"><span></span><span></span><span></span></span></a>
		</h3>

		<div class="cat-g3">
			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(' ms'); ?>>
				<figure class="thumbnail">
					<?php zm_thumbnail(); ?>
				</figure>
				<header class="entry-header">
					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</header>

				<div class="entry-content">
					<span class="entry-meta">
						<?php begin_grid_meta(); ?>
					</span>
					<div class="clear"></div>
				</div>
			</article>
			<?php endwhile; ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>
	<?php } ?>
</div>
<div class="clear"></div>
<?php } ?>