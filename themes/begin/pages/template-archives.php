<?php
/*
Template Name: 文章归档
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>

<style type="text/css">
#primary {
	width: 100%;
}
.year {
	font-size: 16px;
	margin: 10px -21px 10px -21px;
	padding: 0 20px;
	border-bottom: 1px solid #ebebeb;
	border-left: 5px solid #0088cc;
}
.mon {
	color: #000;
	line-height: 30px; 
	margin: 5px 0 5px 5px;
	cursor: pointer;
}
.post_list li {
	line-height: 30px;
	text-indent: 2em;
}
.post_list {
	color: #999;
	margin: 0 0 10px 0;
}
.mon-num {
	color: #999;
	margin: 0 0 0 10px;
}
</style>
<?php require get_template_directory() . '/inc/archives.php'; ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<h1 class="single-title"><?php the_title(); ?></h1>
						<div class="archives-meta">
							站点统计：<?php echo $count_categories = wp_count_terms('category'); ?>个分类&nbsp;&nbsp;
							<?php echo $count_tags = wp_count_terms('post_tag'); ?>个标签&nbsp;&nbsp;
							<?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?> 篇文章&nbsp;&nbsp;
							<?php $my_email = get_bloginfo ( 'admin_email' ); echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments where comment_author_email!='$my_email'");?>条留言&nbsp;&nbsp;
							浏览量：<?php echo all_view(); ?>&nbsp;&nbsp;
							最后更新：<?php $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");$last = date('Y年n月j日', strtotime($last[0]->MAX_m));echo $last; ?>
						</div>
					</header>

					<div class="archives"><?php cx_archives_list(); ?></div>

				</article><!-- #page -->

			<?php endwhile;?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<script type="text/javascript">
$(document).ready(function(){
    (function(){
		$('#all_archives span.mon').each(function(){
			var num=$(this).next().children('li').size();
			var text=$(this).text();
			$(this).html(text+' <span class="mon-num">'+num+' 篇</span>');
		});
		var $al_post_list=$('#all_archives ul.post_list'),
			$al_post_list_f=$('#all_archives ul.post_list:first');
		$al_post_list.hide(1,function(){
			$al_post_list_f.show();
		});
		$('#all_archives span.mon').click(function(){
			$(this).next().slideToggle(400);
			return false;
		});
    })();
 });
</script>
<?php get_footer(); ?>