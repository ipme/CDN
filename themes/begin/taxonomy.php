<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Cache-Control" content="no-transform" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<?php begin_title(); ?>
<link rel="shortcut icon" href="<?php echo zm_get_option('favicon'); ?>">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo zm_get_option('apple_icon'); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
<![endif]-->
<?php wp_head(); ?>
<?php echo zm_get_option('ad_t'); ?>
<?php echo zm_get_option('tongji_h'); ?>

</head>
<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php get_template_part( 'template/menu', 'index' ); ?>
	<?php if (zm_get_option('m_nav')) { ?>
		<?php if ( wp_is_mobile() ) { ?><?php get_template_part( 'inc/menu-m' ); ?><?php } ?>
	<?php } ?>
	<nav class="bread">
		<?php begin_breadcrumb(); ?>
		<?php type_breadcrumb(); ?>
		<?php begin_dw_breadcrumb(); ?>
	</nav>
	<?php get_template_part( 'template/header-slider' ); ?>
	<?php get_template_part('ad/ads', 'header'); ?>
	<?php if (zm_get_option('filters')) { ?>
	<div class="header-sub">
		<?php get_template_part( '/inc/filter-results' ); ?>
	</div>
	<?php } ?>
	<div id="content" class="site-content">
	<?php if (zm_get_option('filters_img')) { ?>
		<section id="picture" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php $posts = query_posts($query_string . '&orderby=date&showposts=16');?>
				<?php while ( have_posts() ) : the_post(); ?>
				<article class="picture wow fadeInUp" data-wow-delay="0.3s">
					<div class="picture-box ms">
						<figure class="picture-img">
							<?php if (zm_get_option('hide_box')) { ?>
								<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-box"></div></a>
								<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-excerpt"><?php if (has_excerpt('')){ echo wp_trim_words( get_the_excerpt(), 62, '...' ); } else { echo wp_trim_words( get_the_content(), 72, '...' ); } ?></div></a>
							<?php } ?>
							<?php zm_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<span class="grid-inf">
							<span class="g-cat"><i class="be be-folder"></i> <?php zm_category(); ?></span>
							<span class="grid-inf-l">
								<span class="date"><i class="be be-schedule"></i> <?php the_time( 'm/d' ); ?></span>
								<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
								<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
							</span>
			 			</span>
			 			<div class="clear"></div>
					</div>
				</article>
				<?php endwhile;?>
			</main><!-- .site-main -->
			<?php begin_pagenav(); ?>
		</section><!-- .content-area -->
	<?php }else { ?>
		<section id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template/content', get_post_format() ); ?>

					<?php get_template_part('ad/ads', 'archive'); ?>

				<?php endwhile; ?>

				<?php else : ?>
					<?php get_template_part( 'template/content', 'none' ); ?>

				<?php endif; ?>

			</main><!-- .site-main -->

			<div class="pagenav-clear"><?php begin_pagenav(); ?></div>

		</section><!-- .content-area -->
		<?php get_sidebar(); ?>
	<?php } ?>
<?php get_footer(); ?>