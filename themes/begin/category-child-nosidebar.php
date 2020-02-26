<?php
/**
 * 全部子分类(无侧边栏)
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

.ch3 {
	padding: 0 5px;
	float: left;
	width: 50%;
	transition-duration: .5s;
}

.child-post {
	position: relative;
	background: #fff;
	margin: 0 0 10px 0;
	border-radius: 2px;
	border: 1px solid #ddd;
}

.child-inf {
	float: right;
	color: #999;
}

.ch3 .cat-title {
	float: left;
	background: #f8f8f8;
	width: 100%;
	border-bottom: 1px solid #ddd;
}

.ch3 .cat-title a {
	float: left;
	width: 100%;
	height: 38px;
	line-height: 38px;
}

.child-list {
	padding: 50px 15px 15px 15px;
}

.child-list li {
	line-height: 230%;
	width: 84%;
	white-space: nowrap;
	word-wrap: normal;
	text-overflow: ellipsis;
	overflow: hidden;
}

@media screen and (max-width:900px) {
	.child-box {
		margin-top: 5px;
	}
	.child-inf {
		display: none;
	}
	.child-inf {
		display: none;
	}
	.child-list li {
		width: 99%;
	}
}

@media screen and (max-width:700px) {
	.ch3 {
		width: 100%;
	}
}
</style>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="child-box">
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
						echo '<div class="ch3"><div class="child-post wow fadeInUp ms" data-wow-delay="0.3s">';
						echo '<h3 class="child-title cat-title"><a href="'.get_category_link($the_cat).'">';
						echo title_i();
						echo $the_cat->name;
						echo more_i();
						echo '</a></h3>';
						echo '
							<ul class="child-list">';
								foreach($posts as $post){
									echo '<i class="child-inf">'.mysql2date('d/m', $post->post_date).'</i>
										<li><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></li>';
								}
							echo '</ul>';
						echo '</div></div>';
					}
				}
			?>
		</div>
	</main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_footer(); ?>