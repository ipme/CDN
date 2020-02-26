<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<style type="text/css">
.page-template-template-cat-tag .page{	
    padding: 0 0 0 0 !important;
}
.single-content {
	float: left;
	width: 100%;
}
.tag-cat {
	float: left;
	width: 14.285714%;
	line-height: 240%;
	padding: 2px;
	transition-duration: .5s;
}
.tag-cat a {
	background: #fff;
	display: block;
	text-align: center;
	white-space: nowrap;
	word-wrap: normal;
	text-overflow: ellipsis;
	overflow: hidden;
    padding: 0 2px;
	border: 1px solid #ddd;
}
.tag-cat-page {
	margin: 0 -2px;
}
@media screen and (max-width: 1080px) {
	.tag-cat { 
		width: 20%;
	}
}
@media screen and (max-width: 800px) {
	.tag-cat { 
		width: 25%;
	}
}
@media screen and (max-width: 700px) {
	.tag-cat { 
		width: 33.3333333333%;
	}
}
@media screen and (max-width: 500px) {
	.tag-cat { 
		width: 50%;
	}
}
.tag-cat a {
    -webkit-transition: -webkit-transform 0.2s;
    transition: transform 0.2s;
}
.tag-cat a:hover {
    -webkit-transform: scale(0.9);
    transform: scale(0.9);
}
</style>

<main id="main" class="link-content" role="main">

<article class="tag-cat-page">
	<?php
	$args = array( 'categories' => '5,765');// 修改数字为分类ID
	$tags = get_category_tags($args);
	if(!empty($tags)) {
	  foreach ($tags as $tag) {
	    $content .= "<span class='tag-cat'><a href=\"".get_tag_link($tag->term_id)."\">".$tag->name."</a></span>";
	  }
	}
	echo $content;
	?>
	<div class="clear"></div>
</article>

</main>

<?php get_footer(); ?>