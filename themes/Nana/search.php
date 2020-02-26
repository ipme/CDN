<?php get_header();?>		
<div id="content" class="site-content">	
<div class="clear"></div>
		<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">	
				<ul class="search-page">
				<?php $posts = query_posts($query_string . '&posts_per_page=30');?>
				<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
					<li class="search-inf"><?php  the_time( 'Y年m月d日');?></li>
					<li class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"  se_prerender_url="complete"><?php the_title(); ?></a>
					</li>
		<?php endwhile; ?>
		<?php else : ?>
		<li class="search-inf"><?php  echo date( 'Y年m月d日');?></li>
			<li class="entry-title">
					亲！没有您要找的，请<a href="<?php echo esc_url( home_url() ); ?>" rel="bookmark" title="亲！没有您要找的"  se_prerender_url="complete">返回首页！</a></li>
			</ul>
		<?php endif; ?>			
		</main><!-- .site-main -->
		<?php pagenavi(); ?>
	</section><!-- .content-area -->
	<?php get_sidebar();?>
	<div class="clear"></div>
</div><!-- .site-content -->
<?php get_footer();?>