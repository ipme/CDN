<?php
/*
Template Name: 图片分类
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>

<section id="picture" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php if (zm_get_option('type_cat')) { ?>
			<?php
			$terms = get_terms("gallery");
			$count = count($terms);
			if ( $count > 0 ){
				echo '<div class="type-cat">';
				foreach ( $terms as $term ) {
					echo '<span class="lx7 wow fadeInUp" data-wow-delay="0.3s"><span>';
					echo '<a href="' . get_term_link( $term ) . '" >' . $term->name . '</a>';
					echo '</span></span>';
				}
				echo '<div class="clear"></div></div>';
			}
			?>
		<?php } ?>
		<?php
		$taxonomy = 'gallery'; 
		$terms = get_terms($taxonomy); foreach ($terms as $cat) {
		$catid = $cat->term_id;
		$args = array(
			'showposts' => zm_get_option('custom_cat_n'),
			'tax_query' => array( array( 'taxonomy' => $taxonomy, 'terms' => $catid, 'include_children' => false ) )
		);
		$query = new WP_Query($args);
		if( $query->have_posts() ) { ?>
		<div class="clear"></div>
		<h3 class="grid-cat wow fadeInUp ms" data-wow-delay="0.3s"><a href="<?php echo get_term_link( $cat ); ?>" ><?php echo $cat->name; ?></a></h3>
		<div class="clear"></div>
		<?php while ($query->have_posts()) : $query->the_post();?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="picture-box wow fadeInUp ms" data-wow-delay="0.3s">
					<figure class="picture-img">
						<?php zm_thumbnail(); ?>
					</figure>
					<?php the_title( sprintf( '<h3 class="picture-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
				</div>
				<div class="clear"></div>
			</article>
		<?php endwhile; ?>
		<?php } wp_reset_query(); ?>
		<?php } ?>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>