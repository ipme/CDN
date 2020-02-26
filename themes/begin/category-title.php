<?php 
/**
 * 倒序标题分类
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

	<section id="novel" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
				global $query_string;
				query_posts( $query_string . '&order=ASC&showposts=1000' );
			?>
			<?php while ( have_posts() ) : the_post(); ?>
			<article class="novel-main wow fadeInUp" data-wow-delay="0.3s">
				<div class="novel-box">
					<?php the_title( sprintf( '<h2 class="novel-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		 			<div class="clear"></div>
				</div>
			</article>
			<?php endwhile;?>
		</main><!-- .site-main -->
		<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</section><!-- .content-area -->
	<!-- <?php begin_pagenav(); ?> -->
<?php get_footer(); ?>