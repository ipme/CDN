<?php
/*
Template Name: 随机文章
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>

<style type="text/css">
.page-template-template-random #primary {
	width: 100%;
}
#main {
	background: #fff;
	margin: 0 0 10px 0;
	padding: 20px;
	border: 1px solid #ddd;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    border-radius: 2px;
}
.post {
	margin: 0;
	padding: 0;
	border: none;
	box-shadow: none;
    border-radius: 0;
}
.archive-list {
	margin: 0 -20px;
	padding: 0 20px;
	border-bottom: 1px solid #dadada;
}
.list-title {
	width: 80%;
	font-weight: normal;
	line-height: 280%;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.archive-list-inf {
	float: right;
	color: #999;
	line-height: 280%;
}
.ias-trigger-next {
	margin: 35px 0 0 -20px;
}
.ias-spinner {
	margin: 30px 0 0 -20px;
}
@media screen and (max-width: 420px) {
	.list-title {
		width: 99%;
	}
	.archive-list-inf {
		display: none;
	}
}
</style>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php query_posts( array ( 'orderby' => 'rand', 'showposts' => 60, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="archive-list">
				<span class="archive-list-inf"><?php the_time( 'm/d' ) ?></span>
				<?php the_title( sprintf( '<h2 class="list-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</div>
		</article>
		<?php endwhile; ?>

	</main><!-- .site-main -->

</section><!-- .content-area -->

<?php get_footer(); ?>