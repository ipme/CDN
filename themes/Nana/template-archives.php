<?php
/*
Template Name: 文章归档
*/
?>
<?php get_header(); ?>
<style type="text/css">
.year {
    font-size: 18px;
    font-size: 1.8rem;
    line-height: 150%;
    margin: 10px 0px;
    padding: 0 0 0 5px;
    border-left: 5px solid #C01E22;
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
</style>
<?php get_template_part( 'inc/functions/archives' ); ?>
	<div id="content" class="site-content shadow">	
<div class="clear"></div>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php while ( have_posts() ) : the_post(); ?>
					<header class="entry-header">
						<h1 class="single-title">文章归档</h1>
						<div class="single_info">
				<?php echo $count_categories = wp_count_terms('category'); ?>个分类&nbsp;&nbsp;
				<?php echo $count_tags = wp_count_terms('post_tag'); ?>个标签&nbsp;&nbsp;
				<?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?> 篇文章&nbsp;&nbsp;<?php echo all_view(); ?>人阅读&nbsp;&nbsp;
				<?php echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments");?>条留言&nbsp;&nbsp;
				更新时间：<?php $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");$last = date('Y年m月d日', strtotime($last[0]->MAX_m));echo $last; ?>					
						</div>
					</header>

					<div class="archives"><?php cx_archives_list(); ?></div>
				<?php endwhile; ?>
				</article><!-- #page -->

			
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<script type="text/javascript">
$(document).ready(function(){
    (function(){
		$('#all_archives span.mon').each(function(){
			var num=$(this).next().children('li').size();
			var text=$(this).text();
			$(this).html(text+' ( '+num+' 篇文章 )');
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
<?php get_sidebar();?>
<div class="clear"></div>
</div><!-- .site-content -->			
<?php get_footer();?>