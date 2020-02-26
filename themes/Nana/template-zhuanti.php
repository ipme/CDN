<?php
/*
Template Name: 专题页面模板
*/
?>
<?php get_header();?>
<div id="content" class="site-content">	
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
			</div>
<div class="clear"></div>
<?php get_template_part( 'inc/social' ); ?>
	<div class="clear"></div>
	</div><!-- .entry-content -->
	</article><!-- #post -->																
			<?php endwhile; ?>
	<div class="clear"></div>	
<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">	
<div id="post_list_box" class="border_gray">
<?php 
	if(get_post_meta($post->ID, 'zttagid', true)){
	$zttagid = get_post_meta($post->ID, 'zttagid', true);
	$args=array('include' => $zttagid); 
	$tags = get_tags($args);
	foreach ($tags as $tag) { 
		$tagid = $tag->term_id; 						
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$args = array(
			'tag_id' => $tagid,
			'paged' => $paged
			);
		query_posts( $args );
?> 
		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="archive-list">
		<figure class="thumbnail">		
			<?php ygj_thumbnail(270,180);?>					
		</figure>
		<header class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>		
		</header><!-- .entry-header -->
		
		<div class="entry-content">
			
			<span class="entry-meta">
				<span class="post_cat">
				<?php the_category( ' | ' ) ?>
			</span>
				<span class="post_spliter">•</span>
				<span class="date" title="<?php the_time( 'Y/m/d H:i');?>"><?php
        echo timeago(get_gmt_from_date(get_the_time('Y-m-d G:i:s'))) ?></span>			
			</span>		
			
			<div class="archive-content">			 				
				<?php if (has_excerpt()){ echo wp_trim_words( get_the_excerpt(), 80, '...' );} else { echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 160,"..."); } ?>
			</div>
			<div class="archive-tag"><span class="views"><?php if( function_exists( 'the_views' ) ) { print '  阅读 '; the_views(); print ' 次  ';  } ;?></span><?php the_tags('', '', '');?></div>
			<div class="clear"></div>
		</div><!-- .entry-content -->
	</article><!-- #post -->

 	<!-- ad -->
	<?php if ($wp_query->current_post == 1) : ?>
	<?php if (get_option('ygj_adh') == '关闭') { ?>
	<?php { echo ''; } ?>
	<?php } else { include(TEMPLATEPATH . '/inc/ad/ad_h.php'); } ?>
	<?php endif; ?>	
	<?php if ($wp_query->current_post == 4) : ?>
	<?php if (get_option('ygj_adhx') == '关闭') { ?>
	<?php { echo ''; } ?>
	<?php } else { include(TEMPLATEPATH . '/inc/ad/ad_hx.php'); } ?>
	<?php endif; ?>	
	<!-- end: ad -->
<?php endwhile; ?>
		<?php endif; ?>	
	<?php }} ?>
</div>			
		</main><!-- .site-main -->		
		<?php pagenavi(); ?>
	</section><!-- .content-area -->
<div id="sidebar" class="widget-area">			
<?php dynamic_sidebar( 'sidebar-2' ); ?>
<?php dynamic_sidebar( 'sidebar-4' ); ?>	
</div>
</div>
<div class="clear"></div>
</div><!-- .site-content -->
<?php get_footer();?>