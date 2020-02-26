<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('grid_cat_new')) { ?>
<div class="grid-cat-box wow fadeInUp" data-wow-delay="0.3s">
<?php $recent = new WP_Query( array( 'posts_per_page' => zm_get_option('grid_cat_news_n'), 'category__not_in' => explode(',',zm_get_option('not_news_n')), 'post__not_in' => $do_not_top) ); ?>
	<div class="grid-cat-new-box">
		<div class="grid-cat-title-box">
			<h3 class="grid-cat-title wow fadeInUp" data-wow-delay="0.3s"><?php _e( '最近更新', 'begin' ); ?></h3>
			<?php if (zm_get_option('cat_all')) { ?>
				<div id="all-cat-grid" class="all-cat-grid grid-all-cat">
					<ul class="grid-cat-all">
						<?php
							$args=array(
								'exclude' => explode(',',zm_get_option('grid_cat_all_e')),
								'hide_empty' => 0
							);
							$cats = get_categories($args);
							foreach ( $cats as $cat ) {
							query_posts( 'cat=' . $cat->cat_ID );
						?>
						<li class="list-cat"><a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><?php single_cat_title(); ?></a></li>
						<?php } ?>
						<?php wp_reset_query(); ?>
					</ul>
				</div>
			<?php } ?>
		</div>
		<div class="clear"></div>
		<div class="grid-cat-site grid-cat-4">
			<?php while($recent->have_posts()) : $recent->the_post(); $count++; $do_not_duplicate[] = $post->ID; ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
					<div class="grid-cat-bx4 sup ms">
						<figure class="picture-img">
							<?php zm_thumbnail(); ?>
							<?php if ( has_post_format('video') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a></div><?php } ?>
							<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
							<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
						</figure>

						<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

						<span class="grid-inf">
							<?php if ( get_post_meta($post->ID, 'link_inf', true) ) { ?>
								<span class="link-inf"><?php $link_inf = get_post_meta($post->ID, 'link_inf', true);{ echo $link_inf;}?></span>
								<span class="grid-inf-l">
								<span class="g-cat"><?php zm_category(); ?></span>
								<?php if ( get_post_meta($post->ID, 'mark', true) ) { ?><span class="t-mark grid-inf-mark"><?php $mark = get_post_meta($post->ID, 'mark', true);{ echo $mark;}; ?></span><?php } ?>
								</span>
							<?php } else { ?>
								<span class="g-cat"><?php zm_category(); ?></span>
								<span class="grid-inf-l">
									<span class="date"><i class="be be-schedule"></i> <?php the_time( 'm/d' ); ?></span>
									<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
									<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
									<?php if ( get_post_meta($post->ID, 'mark', true) ) { ?><span class="t-mark grid-inf-mark"><?php $mark = get_post_meta($post->ID, 'mark', true);{ echo $mark;}; ?></span><?php } ?>
								</span>
							<?php } ?>
			 			</span>
			 			<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php if (zm_get_option('cat_all')) { ?>
<script type="text/javascript">
$(document).ready(function(){
function topmenu() {
	var totalWidth = $(".grid-cat-all").width();
	var topmenuR = $(".all-cat-grid").offset().left + totalWidth;
	var $list = $(".list-cat");
	var drowMenu = '';
	var more = "";
	var listD = '';
	for (var i = 0; i < $list.length; i++) {
		var liWidth = $($list[i]).width();
		var liR = $($list[i]).offset().left + liWidth;
		if (liR > topmenuR) {
			drowMenu += '<ul id="more-list" class="more-list">'
			for (var j = i; j < $list.length; j++) {
				listD += '<li class="list-cat">' + $($list[j]).html() + '</li>';
				$($list[j]).remove();
			}
			drowMenu += listD + '</ul>'
			more = '<li><a id="more-cat" class="more-cat"><p class="all-cat-ico"></p></a></li>';
			$(".grid-cat-all").append(more);
			$("#more-cat").append(drowMenu);
			break;
		}
	}
}
topmenu();
window.onresize = function() {
	var appendL = $("#more-list").html();
	$("#more-cat").remove();
	$(".grid-cat-all").append(appendL);
	topmenu();
}
});
</script>
<?php } ?>
<?php } ?>