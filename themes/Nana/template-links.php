<?php
/*
Template Name: 友情链接
*/
?>
<?php get_header();?>
<div id="content" class="site-content">	
<div class="clear"></div>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php while ( have_posts() ) : the_post(); ?>
			
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>	
		<div class="single_info">
					<span class="date"><?php the_time( 'Y-m-d H:i' ) ?></span>
					<span class="views"><?php if( function_exists( 'the_views' ) ) { the_views(); print '人阅读 '; } ?></span>			
					<span class="edit"><?php edit_post_link('编辑', '  ', '  '); ?></span>
				</div>			
	</header><!-- .entry-header -->

	<div class="entry-content">
					<div class="single-content">									
	<?php the_content(); ?>
	<article class="link-page">
<ul id="linkdh">
	<li class="linkcat">		
	<ul class="xoxo blogroll">
<?php wp_list_bookmarks('title_before=<h2 style="text-align: left;">&title_after=</h2>&before=<span class="cx7"><span class="link-all">&after=</span></span>&category_orderby=id'); ?>	
</ul>
	</li>
</ul>
</article>
			</div>
<div class="clear"></div>
<?php get_template_part( 'inc/social' ); ?>
				<div class="clear"></div>
	</div><!-- .entry-content -->

	</article><!-- #post -->	
<?php if (get_option('ygj_taobaoym') == '显示') { get_template_part('/inc/taobao'); } ?>	
		<?php if (get_option('ygj_g_full') == '显示') { get_template_part( 'inc/ad/ad_full' ); } ?>	
	<?php comments_template( '', true ); ?>			
			<?php endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<div class="clear"></div>
</div><!-- .site-content -->
<?php get_footer();?>