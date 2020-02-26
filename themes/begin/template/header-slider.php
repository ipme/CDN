<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( get_post_meta($post->ID, 'header_img', true) ) : ?>
<?php if (is_single() || is_page() ) : ?>
<div class="header-sub">
	<div id="slideshow">
		<div id="slider-title" class="slider-title">
			<?php
			$image = get_post_meta($post->ID, 'header_img', true);
			$image=explode("\n",$image);
			foreach($image as $key => $header_img) { ?>
			<div class="slider-title-img"><img src="<?php echo $header_img;?>"/></div>
			<?php }?>
		</div>
		<div class="header-title-main">
			<?php while (have_posts()) : the_post(); ?>
				<?php the_title( '<h1 class="header-title wow fadeInUp" data-wow-delay="0.3s">', '</h1>' ); ?>
				<?php if (is_single()) : ?><?php begin_single_meta(); ?><?php endif; ?>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ( get_post_meta($post->ID, 'header_bg', true) ) { ?>
<?php if (is_single() || is_page() ) : ?>
<div class="header-sub">
	<div class="cat-des wow fadeInUp" data-wow-delay="0.3s">
		<?php
		$image = get_post_meta($post->ID, 'header_bg', true);
		$image=explode("\n",$image);
		foreach($image as $key => $header_bg) { ?>
			<div class="cat-des-img"><img src="<?php echo $header_bg;?>"/></div>
		<?php }?>

		<div class="header-title-main">
			<?php while (have_posts()) : the_post(); ?>
				<?php the_title( '<h1 class="header-title wow fadeInUp" data-wow-delay="0.3s">', '</h1>' ); ?>
				<?php if (is_single()) : ?><?php begin_single_meta(); ?><?php endif; ?>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</div>
</div>
<?php endif; ?>
<?php }?>