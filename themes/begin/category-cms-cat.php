<?php
/**
 * 分类杂志布局
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<!-- 最新文章调用篇数，修改下面"posts_per_page' =>"后面的数字 -->
		<?php query_posts( array( "category__in" => array(get_query_var("cat")), 'posts_per_page' => 4, 'ignore_sticky_posts' => 1) ); ?>
		<?php while ( have_posts() ) : the_post();$do_not_duplicate[] = $post->ID; ?>
			<?php get_template_part( 'template/content', get_post_format() ); ?>
		<?php endwhile; ?>
		<div class="clear"></div>
		<!-- <?php get_template_part('ad/ads', 'cms'); ?> -->
		<div class="line-small">

			<!-- 调用指定分类，修改下面"array(6,8,10,5)"中数字为分类ID -->
			<?php $display_categories =  array(6,8,10,5); foreach ($display_categories as $category) { ?>

			<?php query_posts( array( 'showposts' => 1, 'cat' => $category, 'post__not_in' => $do_not_duplicate ) ); ?>
			<div class="xl2 xm2">
				<div class="cat-container wow fadeInUp" data-wow-delay="0.3s">
					<h3 class="cat-title"><a href="<?php echo get_category_link($category);?>" title="<?php echo strip_tags(category_description($category)); ?>"><span class="title-i"><span></span><span></span><span></span><span></span></span><?php single_cat_title(); ?><span class="more-i"><span></span><span></span><span></span></span></a></h3>
					<div class="clear"></div>
					<div class="cat-site">
						<?php while ( have_posts() ) : the_post(); ?>
							<figure class="small-thumbnail"><?php zm_long_thumbnail(); ?></figure>
							<?php the_title( sprintf( '<h2 class="entry-small-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>	
						<?php endwhile; ?>
						<div class="clear"></div>

						<ul class="cat-list">
							<!-- 分类文章列表篇数，修改下面"'showposts' => 8"中的数字 -->
							<?php query_posts( array( 'showposts' => 8, 'cat' => $category, 'offset' => 1, 'post__not_in' => $do_not_duplicate ) ); ?>

							<?php while ( have_posts() ) : the_post(); ?>
								<?php if (zm_get_option('list_date')) { ?><li class="list-date"><?php the_time('m/d') ?></li><?php } ?>
								<?php the_title( sprintf( '<li class="list-title"><a href="%s" rel="bookmark"><i class="be be-arrowright"></i>', esc_url( get_permalink() ) ), '</a></li>' ); ?>
							<?php endwhile; ?>
							<?php wp_reset_query(); ?>
						</ul>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="clear"></div>
		</div>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>