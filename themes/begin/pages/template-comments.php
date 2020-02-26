<?php
/*
* Template Name: 读者排行
* Description：留言者排行
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<?php get_header(); ?>
<style type="text/css">
#primary {
	width: 100%;
	overflow: hidden;
}

.top-comments {
	margin: 0 -6px;
}

.top-comments img {
	float: left;
	width: 100%;
	height: auto;
	max-width: 100%;
}

.top-author {
	background: #fff;
	margin: 5px;
	padding: 0 0 5px 0;
	overflow: hidden;
	border-radius: 5px;
	border: 1px solid #ddd;
}

.top-comment {
	color: #999;
	text-align: center;
}

.author-url {
	width: 100%;
	padding: 5px;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.comment-authors {
	padding: 0 10px;
}
</style>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="comment-authors">
				<?php the_content(); ?>
			</div>
		<?php endwhile; ?>
		<?php top_comment_authors(98); ?>
		<div class="clear"></div>
	</main>
</div>

<?php get_footer(); ?>