<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php else : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp post ms'); ?> data-wow-delay="0.3s">
<?php endif; ?>

	<header class="entry-header">
		<?php if ( is_single() ) : ?>
			<?php if ( get_post_meta($post->ID, 'header_img', true) ) { ?>
				<div class="entry-title-clear"></div>
			<?php } else { ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php } ?>
		<?php else : ?>
			<span class="format-img-cat"><?php zm_category(); ?></span>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( ! is_single() ) : ?>
			<figure class="content-image">
				<a href="<?php the_permalink() ?>" rel="bookmark"><?php format_image_thumbnail(); ?></a>
			</figure>
			<span class="title-l"></span>
			<span class="post-format"><i class="be be-picture"></i></span>
			<?php the_title( sprintf( '<h2 class="post-format-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?><span class="img-number"><?php _e( '共', 'begin' ); ?> <?php echo get_post_images_number().' ' ?> <?php _e( '张图片', 'begin' ); ?></span>
			<div class="clear"></div>

		<?php else : ?>

			<?php if ( ! get_post_meta($post->ID, 'header_img', true) ) : ?>
			<?php if (zm_get_option('meta_b')) {
				begin_single_meta();
			} else {
				begin_entry_meta();
			} ?>
			<?php endif; ?>

			<?php if (zm_get_option('all_more') && !get_post_meta($post->ID, 'not_more', true)) { ?>
				<div class="single-content<?php if (word_num() > 800) { ?> more-content<?php } ?>">
			<?php } else { ?>
				<div class="single-content">
			<?php } ?>
				<?php if ( has_excerpt() ) { ?><span class="abstract"><fieldset><legend><?php _e( '摘要', 'begin' ); ?></legend><?php the_excerpt() ?><div class="clear"></div></fieldset></span><?php }?>
				<?php get_template_part('ad/ads', 'single'); ?>

				<?php the_content(); ?>

				<?php if (zm_get_option('xzh_gz')) { ?>
					 <script>cambrian.render('tail')</script>
				<?php } ?>
			</div>

			<?php if (zm_get_option('all_more') && !get_post_meta($post->ID, 'not_more', true)) { ?><?php all_content(); ?><?php } ?>
			<?php if (zm_get_option('tts_play') && zm_get_option('meta_b') && !get_post_meta($post->ID, 'not_tts', true)) { ?><?php require get_template_directory() . '/inc/tts.php'; ?><?php } ?>

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
				<?php if (zm_get_option('meta_b')) {
					begin_single_cat();
				} ?>
			</footer><!-- .entry-footer -->

		<?php endif; ?>
	</div><!-- .entry-content -->

</article><!-- #post -->

<?php if ( is_single() ) : ?><div class="single-tag"><?php the_tags( '<ul class="wow fadeInUp" data-wow-delay="0.3s"><li>', '</li><li>', '</li></ul>' ); ?></div><?php endif; ?>