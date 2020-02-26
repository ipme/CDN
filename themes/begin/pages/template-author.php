<?php
/*
Template Name: 作者墙
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<style type="text/css">
.author-header h1 {
	font-size: 18px;
	font-size: 1.8rem;
	line-height: 30px;
	text-align: center;
	margin: 0 0 15px 0;
}

.author-page {
	margin: 0 -10px;
	overflow: hidden;
}

.author-name a {
	padding: 10px 0;
	display: block;
	white-space: nowrap;
	word-wrap: normal;
	text-overflow: ellipsis;
	overflow: hidden;
}

.author-all {
	background: #fff;
	text-align: center;
	display: block;
	border: 1px solid #ddd;
	border-radius: 2px;
	transition-duration: .5s;
}

.author-all a img {
	max-width: 100%;
	width: auto;
	height: auto;
}

.cx6 {
	float: left;
	min-height: 1px;
	width: 16.6666666666666%;
	padding: 10px;
	transition-duration: .5s;
}

@media screen and (max-width:900px) {
	.cx6 {
		width: 25%;
	}

	.author-page {
		margin: 0 -4px;
	}
}

@media screen and (max-width:480px) {
	.cx6 {
		width: 33.333333333333%;
	}
}

@media screen and (max-width:440px) {
	.cx6 {
		width: 50%;
	}
}
</style>
<main id="main" class="author-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="author-header">
				<div class="archive-l"></div>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->
			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
					<?php edit_post_link('编辑', '<span class="edit-link">', '</span>' ); ?>
				</div> <!-- .single-content -->
			</div><!-- .entry-content -->
		</article><!-- #page -->
	<?php endwhile; ?>

	<article class="author-page">
		<?php allauthor();?>
		<div class="clear"></div>
	</article>
</main>

<?php get_footer(); ?>