<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php else : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp post ms'); ?> data-wow-delay="0.3s">
<?php endif; ?>
	<?php if ( get_post_meta($post->ID, 'header_img', true) || get_post_meta($post->ID, 'header_bg', true) ) { ?>
	<?php } else { ?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
	<?php } ?>
	<div class="entry-content">
		<div class="single-content">
			<?php the_content(); ?>

			<?php if (zm_get_option('xzh_gz')) { ?>
				 <script>cambrian.render('tail')</script>
			<?php } ?>

			<?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) : ?><style>#primary {width: 100%;}#sidebar, .r-hide, .s-hide {display: none;}</style><?php endif; ?>

			<?php begin_link_pages(); ?>
		</div>
		<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
		<div class="clear"></div>
		<?php if ( get_post_meta($post->ID, 'no_abstract', true) ) : ?><style>.abstract {display: none;}</style><?php endif; ?>
	</div><!-- .entry-content -->

</article><!-- #page -->