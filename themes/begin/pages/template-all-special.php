<?php
/*
Template Name: 所有专题
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>

<section id="primary-cover" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="cat-cover-box">
			<?php $posts = get_posts( array( 'post_type' => 'any', 'meta_key' => 'special', 'ignore_sticky_posts' => 1, 'showposts' => 1000 ) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
			<div class="cover4x">
				<div class="cat-cover-main wow fadeInUp ms" data-wow-delay="0.3s">
					<div class="cat-cover-img">
						<a href="<?php echo get_permalink(); ?>" rel="bookmark">
							<div class="special-mark">专题</div>
							<figure class="cover-img">
								<?php 
									$image = get_post_meta($post->ID, 'thumbnail', true);
									echo '<img src=';
									if (zm_get_option('special_thumbnail')) {
										echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_sp_w').'&h='.zm_get_option('img_sp_h').'&a='.zm_get_option('crop_top').'&zc=1';
									} else {
										echo $image;
									}
									echo ' alt="'.$post->post_title .'" />'; 
								?>
							</figure>
							<div class="cover-des-box"><div class="cover-des"><?php $description = get_post_meta($post->ID, 'description', true);{echo $description;} ?></div></div>
						</a>
						<div class="clear"></div>
					</div>
					<a href="<?php echo get_permalink(); ?>" rel="bookmark"><h4 class="cat-cover-title"><?php the_title(); ?></h4></a>
				</div>
			</div>
			<?php endforeach; endif; ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>