<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cms_top')) { ?>
	<?php $recent = new WP_Query( array( 'posts_per_page' => zm_get_option('news_n'), 'category__not_in' => explode(',', zm_get_option('not_news_n')), 'post__not_in' => $do_show, 'meta_query' => array( array( 'key' => 'cms_top', 'compare' => 'NOT EXISTS'))));?>
<?php } else { ?>
	<?php $recent = new WP_Query( array( 'posts_per_page' => zm_get_option('news_n'), 'post__not_in' => $do_show, 'category__not_in' => explode(',',zm_get_option('not_news_n'))) ); ?>
<?php } ?>
<?php while($recent->have_posts()) : $recent->the_post(); $count++; $do_not_duplicate[] = $post->ID; ?>
	<?php get_template_part( 'template/content', get_post_format() ); ?>
	<?php if ($count == 1) : ?>
		<!-- 图文日志 -->
		<?php if (zm_get_option('post_img')) { ?>
			<div class="line-four">
				<?php require get_template_directory() . '/cms/cms-post-img.php'; ?>
			</div>
		<?php } ?>
	<?php endif; ?>
	<?php if ($count == 2) : ?>
		<!-- 广告 -->
		<?php get_template_part('ad/ads', 'cms'); ?>
	<?php endif; ?>
<?php endwhile; ?>
<div class="clear"></div>