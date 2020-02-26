<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<style type="text/css">
.tao-goods {
	border: 1px solid #fff;
}
.tao-img {
	float: left;
	max-width: 261px;
	height: auto;
	margin: 0 30px 30px 0;
    overflow: hidden;
	transition-duration: .3s;
}
.tao-img a img {
	width: auto;
	height: auto;
	max-width: 100%;
	-webkit-transition: -webkit-transform .3s linear;
	-moz-transition: -moz-transform .3s linear;
	-o-transition: -o-transform .3s linear;
	transition: transform .3s linear
}
.tao-img:hover a img {
	transition: All 0.7s ease;
	-webkit-transform: scale(1.1);
	-moz-transform: scale(1.1);
	-ms-transform: scale(1.1);
	-o-transform: scale(1.1);
}
.brief {
	float: left;
	width: 50%;
	margin: 0;
	padding: 0 10px 10px 10px;
}
.product-m {
	font-size: 15px;
	display: block;
	margin: 0 0 15px 0;
}
.pricex {
	font-size: 16px;
	color: #ff4400;
	display: block;
}

.tao-goods ul li {
 	font-size: 14px;
	font-weight: normal;
	list-style:none;
	border: none;
    line-height: 180%;
    margin: 0;
	box-shadow: none;
}
.taourl a {
	float: left;
	background: #ff4400;
	color: #fff !important;
	line-height: 35px;
	margin: 40px 20px 0 0;
	padding: 0 15px;
	border: 1px solid #ff4400;
	border-radius: 2px;
}
.taourl a:hover {
	background: #7ab951;
	border: 1px solid #7ab951;
}
.discount a {
	float: left;
	background: #fff;
	color: #444 !important;
	line-height: 35px;
	margin: 40px 20px 0 0;
	padding: 0 15px;
	border: 1px solid #ddd;
	border-radius: 2px;
}
.discount a:hover {
	color: #fff !important;
	background: #7ab951;
	border: 1px solid #7ab951;
}

@media screen and (max-width: 640px) {
	.brief {
		width: 100%;
	}
	.tao-img {
		float: inherit;
		margin: 0 auto 0;
	}
}
</style>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp post ms'); ?> data-wow-delay="0.3s">

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<div class="single-content">
						<div class="tao-goods">
							<figure class="tao-img">
								<?php tao_thumbnail(); ?>
							</figure>

							<div class="brief">
								<span class="product-m"><?php $price = get_post_meta($post->ID, 'product', true);{ echo $price; }?></span>
								<span class="pricex"><strong>￥<?php $price = get_post_meta($post->ID, 'pricex', true);{ echo $price; }?>元</strong></span>
								<?php if ( get_post_meta($post->ID, 'pricey', true) ) : ?>
									<span class="pricey"><del>市场价:<?php $price = get_post_meta($post->ID, 'pricey', true);{ echo $price; }?>元</del></span>
								<?php endif; ?>

								<?php if ( get_post_meta($post->ID, 'discount', true) ) : ?>
									<?php
										$discount = get_post_meta($post->ID, 'discount', true);
										$url = get_post_meta($post->ID, 'discounturl', true);
										echo '<span class="discount"><a href='.$url.' rel="external nofollow" target="_blank" class="url">'.$discount.'</a></span>';
									 ?>
								<?php endif; ?>

								<?php if ( get_post_meta($post->ID, 'taourl', true) ) : ?>
									<?php
										$url = get_post_meta($post->ID, 'taourl', true);
										echo '<span class="taourl"><a href='.$url.' rel="external nofollow" target="_blank" class="url">直接购买</a></span>';
									 ?>
								<?php endif; ?>

								<!-- 
								<?php if ( get_post_meta($post->ID, 'discount', true) ) : ?>
									<?php
										$discount = get_post_meta($post->ID, 'discount', true);
										$url = get_post_meta($post->ID, 'discounturl', true);
										echo "<div class='discount'><a href='$url' rel='external nofollow' target='_blank' class='url'>$discount</a></div>";
									 ?>
								<?php endif; ?>

								<?php
									$url = get_post_meta($post->ID, 'taourl', true);
									echo "<div class='taourl'><a href='$url' rel='external nofollow' target='_blank' class='url'>直接购买</a></div>";
								 ?>
					 			-->

							</div>
							<div class="clear"></div>
						</div>

						<div class="clear"></div>

						<?php the_content(); ?>
						<?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) : ?><style>	#primary {width: 100%;}#sidebar,.r-hide {display: none;}</style><?php endif; ?>
						<div class="clear"></div>
						<?php begin_link_pages(); ?>
					</div>

						<?php if (zm_get_option('zm_like')) { ?>
							<?php get_template_part( 'template/social' ); ?>
						<?php } else { ?>
							<div id="social"></div>
						<?php } ?>

						<footer class="single-footer">
							<ul class="single-meta">
								<?php edit_post_link('<i class="be be-editor"></i>', '<li class="edit-link">', '</li>' ); ?>
								<?php if ( post_password_required() ) { ?>
									<li class="comment"><a href="#comments">密码保护</a></li>
								<?php } else { ?>
									<li class="comment"><?php comments_popup_link( '<i class="be be-speechbubble"></i> 0', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' ); ?></li>
								<?php } ?>
								<?php if( function_exists( 'the_views' ) ) { the_views(true, '<li class="views"><i class="be be-eye"></i> ','</li>');  } ?>
							</ul>

							<div class="single-cat-tag">
								<div class="single-cat">分类：<?php echo get_the_term_list( $post->ID,  'taobao', '' ); ?></div>
							</div>
						</footer><!-- .entry-footer -->

					<div class="clear"></div>
				</div><!-- .entry-content -->

			</article><!-- #post -->
			<div class="single-tag"><?php echo get_the_term_list($post->ID,  'taotag', '<ul class="wow fadeInUp" data-wow-delay="0.3s"><li>', '</li><li>', '</li></ul>' ); ?></div>

			<?php if (zm_get_option('copyright')) { ?>
				<?php get_template_part( 'template/copyright' ); ?>
			<?php } ?>

			<?php if (zm_get_option('related_img')) { ?>
				<?php get_template_part( 'template/single-tao' ); ?>
			<?php } ?>

				<?php get_template_part('ad/ads', 'comments'); ?>

				<nav class="nav-single wow fadeInUp" data-wow-delay="0.3s">
					<?php
						if (get_previous_post()) { previous_post_link( '%link','<span class="meta-nav ms"><span class="post-nav"><i class="be be-arrowleft"></i> ' . sprintf(__( '上一篇', 'begin' )) . '</span><br/>%title</span>' ); } else { echo "<span class='meta-nav ms'><span class='post-nav'>" . sprintf(__( '没有了', 'begin' )) . "<br/></span>" . sprintf(__( '已是最后文章', 'begin' )) . "</span>"; }
						if (get_next_post()) { next_post_link( '%link', '<span class="meta-nav ms"><span class="post-nav">' . sprintf(__( '下一篇', 'begin' )) . ' <i class="be be-arrowright"></i></span><br/>%title</span>' ); } else { echo "<span class='meta-nav ms'><span class='post-nav'>" . sprintf(__( '没有了', 'begin' )) . "<br/></span>" . sprintf(__( '已是最新文章', 'begin' )) . "</span>"; }
					?>
					<div class="clear"></div>
				</nav>
				<?php if (zm_get_option('meta_nav_lr')) : ?>
				<?php
					the_post_navigation( array(
						'next_text' => '<span class="meta-nav-l" aria-hidden="true"><i class="be be-arrowright"></i></span>',
						'prev_text' => '<span class="meta-nav-r" aria-hidden="true"><i class="be be-arrowleft"></i></span>',
					) );
				?>
				<?php endif; ?>
			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template( '', true ); ?>
			<?php endif; ?>

		<?php endwhile; ?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>