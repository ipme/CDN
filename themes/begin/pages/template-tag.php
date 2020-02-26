<?php
/*
Template Name: 热门标签
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>
<style type="text/css">
#primary {
	width: 100%;
}
#tag_letter {
	margin-left: 13px;
}
#tag_letter li {
	float: left;
	background: #0088cc;
	width: 31px;
	height: 31px;
	line-height: 31px; 
	color: #a5a5a5;
	text-align: center;
	margin: 4px;
	border-radius: 2px;
}
#tag_letter li:hover {
	background: #c40000;
}
#tag_letter li a {
	color: #fff;
	display: block;
}
#all_tags {
	margin: 30px 6px;
	clear: both;
}
#all_tags h4 {
	margin: -5px 0 0 5px;
	padding: 5px 0 5px 0;
	height: 40px;
}
#all_tags {
	border: 1px solid #fff;
}
#all_tags li {
	padding: 5px 0;
	border-bottom: 1px dashed #dadada;
}
#all_tags li:last-child {
	border:none;
}
#all_tags li a {
	margin: 5px;
	word-wrap: break-word;
	word-break: normal;
}
</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php specs_show_tags(); ?>
				<div class="clear"></div>
			</article><!-- #page -->
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>