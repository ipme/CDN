<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('video_box')) { ?>
<?php if (zm_get_option('video')) { ?>
	<div class="line-four sort" name="<?php echo zm_get_option('video_s'); ?>">
		<?php 
			$args = array('tax_query' => array( array('taxonomy' => 'videos', 'field' => 'id', 'terms' => explode(',',zm_get_option('video_id') ))), 'posts_per_page' => zm_get_option('video_n'));
			query_posts($args); while ( have_posts() ) : the_post();
		?>
		<div class="xl4 xm4">
			<div class="picture-cms wow fadeInUp ms" data-wow-delay="0.3s">
				<figure class="picture-cms-img">
					<?php videos_thumbnail(); ?>
				</figure>
				<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</div>
		</div>

		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</div>
<?php } ?>

<?php if (zm_get_option('video_post')) { ?>
<div class="line-four sort" name="<?php echo zm_get_option('video_s'); ?>">
	<?php query_posts('showposts='.zm_get_option('video_n').'&category__and='.zm_get_option('video_post_id')); while (have_posts()) : the_post(); ?>
	<div class="xl4 xm4">
		<div class="picture-cms wow fadeInUp ms" data-wow-delay="0.3s">
			<figure class="picture-cms-img">
				<?php zm_thumbnail(); ?>
				<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a>
			</figure>
			<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</div>
	</div>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php } ?>
<?php } ?>