<?php
/**
 * 子分类图片模板
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<style type="text/css">
#primary {
	width: 100%;
}

.child-box {
	margin: 0 -5px;
}

.child-cat {
	display: none;
}

.grid-cat-title {
	padding: 0 0 0 5px;
}

.child-img-4 {
	position: relative;
	float: left;
	padding: 5px;
	width: 25%;
}

.child-img {
	background: #fff;
	margin: 0 0 10px 0;
	border-radius: 2px;
	border: 1px solid #ddd;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
}

.child-img-4 img {
	float: left;
	width: auto;
	height: auto;
	max-width: 100%;
}

@media screen and (max-width:600px) {
	.child-box {
		margin: 0;
	}

	.child-img-4 {
		width: 50%;
	}
}
</style>
<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<article class="child-box">
			<?php
				global $cat;
				$cats = get_categories(array(
					'child_of' => $cat,
					'parent' => $cat,
					'hide_empty' => 0
				 ));
				foreach($cats as $the_cat){
					$posts = get_posts(array(
						'category' => $the_cat->cat_ID,
						'numberposts' => 8,
					));
					if(!empty($posts)){
						echo '<h3 class="grid-cat-title">
							<a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a>
						</h3>';
						foreach($posts as $post){
							echo '<div class="child-img-4"><div class="child-img sup ms">';
								if ( get_post_meta($post->ID, 'thumbnail', true) ) {
									$thumbnail  = get_post_meta(get_the_ID(),'thumbnail',true);
									$thumb_img =  '<img src="'.$thumbnail.'">';
								} else {
									$content = $post->post_content;
									preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
									$thumb_img = '<img src="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /><div class="clear"></div>';
								}
							echo '<figure class="picture-img"><a href="'.get_permalink($post->ID).'">'.$thumb_img.'</a></figure>';
							echo '<h2 class="grid-title"><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h2></div></div>';
						}
					}
				}
				echo '<div class="clear"></div></div>';
			?>
		</article>
	</main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_footer(); ?>