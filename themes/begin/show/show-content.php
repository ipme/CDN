<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="g-row">
	<div class="g-col">
		<div class="section-box">
			<div class="group-title wow fadeInUp" data-wow-delay="0.3s">
				<?php $s_c_t = get_post_meta($post->ID, 's_c_t', true); ?>
				<h3><?php echo $s_c_t; ?><?php edit_post_link('<i class="be be-editor"></i>', '', '' ); ?></h3>
				<div class="clear"></div>
			</div>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
					<div class="entry-content">
						<div class="single-content">
							<?php the_content(); ?>
						</div>
						<?php begin_link_pages(); ?>
						<?php if ( get_post_meta($post->ID, 'down_link_much', true) ) : ?><style>.down-link {float: left;}</style><?php endif; ?>
						<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<div class="clear"></div>
	</div>
</div>