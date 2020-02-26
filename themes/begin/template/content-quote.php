<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php else : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp post ms'); ?> data-wow-delay="0.3s">
<?php endif; ?>
	<?php if ( ! is_single() ) : ?>
		<figure class="thumbnail">
			<?php zm_thumbnail(); ?>
			<?php if (zm_get_option('no_thumbnail_cat')) { ?><span class="cat cat-roll"><?php } else { ?><span class="cat"><?php } ?><?php zm_category(); ?></span>
		</figure>
	<?php endif; ?>
	<header class="entry-header">
		<?php if ( is_single() ) : ?>
			<?php if ( get_post_meta($post->ID, 'header_img', true) ) { ?>
				<div class="entry-title-clear"></div>
			<?php } else { ?>
				<?php if ( get_post_meta($post->ID, 'mark', true) ) { ?>
					<?php the_title( sprintf( '<h1 class="entry-title">', esc_url( get_permalink() ) ), '</a><span class="t-mark">' . $mark = get_post_meta($post->ID, 'mark', true) . '</span></h1>' ); ?>
				<?php } else { ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php } ?>
			<?php } ?>
		<?php else : ?>
			<?php if ( get_post_meta($post->ID, 'mark', true) ) { ?>
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a><span class="t-mark">' . $mark = get_post_meta($post->ID, 'mark', true) . '</span></h2>' ); ?>
			<?php } else { ?>
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<?php } ?>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( ! is_single() ) : ?>
			<div class="archive-content">
				<?php if (has_excerpt('')){
						echo wp_trim_words( get_the_excerpt(), zm_get_option('word_n'), '...' );
					} else {
						$content = get_the_content();
						$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
						echo wp_trim_words( $content, zm_get_option('words_n'), '...' );
			        }
				?>
			</div>
			<div class="clear"></div>
			<span class="title-l"></span>
			<?php get_template_part( 'template/new' ); ?>
			<span class="post-format"><i class="be be-display" aria-hidden="true"></i></span>
			<span class="entry-meta">
				<?php begin_entry_meta(); ?>
			</span>
		<?php else : ?>
			<?php if (zm_get_option('all_more') && !get_post_meta($post->ID, 'not_more', true)) { ?>
				<div class="single-content<?php if (word_num() > 800) { ?> more-content<?php } ?>">
			<?php } else { ?>
				<div class="single-content">
			<?php } ?>
				<?php if ( has_excerpt() ) { ?><span class="abstract"><fieldset><legend><?php _e( '摘要', 'begin' ); ?></legend><?php the_excerpt() ?><div class="clear"></div></fieldset></span><?php }?>

				<?php get_template_part('ad/ads', 'single'); ?>
				<div class="videos-content">
					<div class="video-img">
						<?php zm_thumbnail(); ?>
					</div>

					<div class="video-inf">
						<span class="category"><strong><?php _e( '所属分类', 'begin' ); ?>：</strong><?php the_category( '&nbsp;&nbsp;' ); ?></span>
						<span><?php $file_os = get_post_meta($post->ID, 'file_os', true); ?><?php echo $file_os; ?></span>
						<span><?php $file_inf = get_post_meta($post->ID, 'file_inf', true); ?><?php echo $file_inf; ?></span>
						<span class="date"><strong><?php _e( '最后更新', 'begin' ); ?>：</strong><?php time_ago( $time_type ='posts' ); ?></ul>
					</div>
					<div class="clear"></div>
				</div>

				<?php the_content(); ?>

				<?php if (zm_get_option('xzh_gz')) { ?>
					 <script>cambrian.render('tail')</script>
				<?php } ?>
			</div>

			<?php if (zm_get_option('all_more') && !get_post_meta($post->ID, 'not_more', true)) { ?><?php all_content(); ?><?php } ?>
			<?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) : ?><style>#primary {width: 100%;}#sidebar, .r-hide, .s-hide {display: none;}</style><?php endif; ?>
			<?php if ( zm_get_option('begin_today') && !get_post_meta($post->ID, 'no_today', true) ) { ?><?php echo begin_today(); ?><?php } ?>
			<?php begin_link_pages(); ?>

				<?php if (zm_get_option('single_weixin')) { ?>
					<?php get_template_part( 'template/weixin' ); ?>
				<?php } ?>

				<?php if (zm_get_option('zm_like')) { ?>
					<?php get_template_part( 'template/social' ); ?>
				<?php } else { ?>
					<div id="social"></div>
				<?php } ?>

				<?php get_template_part('ad/ads', 'single-b'); ?>
				<?php if ( get_post_meta($post->ID, 'no_abstract', true) ) : ?><style>.abstract {display: none;}</style><?php endif; ?>

			<footer class="single-footer">
				<?php begin_entry_meta(); ?>
			</footer><!-- .entry-footer -->

		<?php endif; ?>
		<div class="clear"></div>
	</div><!-- .entry-content -->

	<?php if ( ! is_single() ) : ?>
		<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
		<?php $direct = get_post_meta($post->ID, 'direct', true); ?>
		<?php if (zm_get_option('more_hide')) { ?><span class="entry-more more-roll"><?php } else { ?><span class="entry-more"><?php } ?><a href="<?php echo $direct ?>" target="_blank" rel="nofollow"><?php echo zm_get_option('direct_w'); ?></a></span>
		<?php } else { ?>
		<?php if (zm_get_option('more_hide')) { ?><span class="entry-more more-roll"><?php } else { ?><span class="entry-more"><?php } ?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo zm_get_option('more_w'); ?></a></span>
		<?php } ?>
	<?php endif; ?>
</article><!-- #post -->

<?php if ( is_single() ) : ?><div class="single-tag"><?php the_tags( '<ul class="wow fadeInUp" data-wow-delay="0.3s"><li>', '</li><li>', '</li></ul>' ); ?></div><?php endif; ?>