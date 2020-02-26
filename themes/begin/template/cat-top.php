<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cat_top') &&  !is_paged()) { ?>
	<?php query_posts( array ( 'category__in' => array(get_query_var('cat')), 'meta_key' => 'cat_top', 'showposts' => 4, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>
		<div class="cat-top"><?php get_template_part( 'template/content', get_post_format() ); ?></div>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
<?php } ?>