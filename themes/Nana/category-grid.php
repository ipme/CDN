<?php get_header();?>
	<div id="content" class="site-content">
		<div class="clear"></div>
		<div id="primarys" class="content-area">
		<main id="main" class="site-main" role="main">
		<div class="clear"></div>		
		<div class="cat-box">
			<div class="cat-site">	
				<ul class="cat-one-list">
		<?php
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$sticky = get_option( 'sticky_posts' );
		$args = array(
			'posts_per_page' => get_option('ygj_grid_num'),
			'cat' => $cat,
			'paged' => $paged
		);
		query_posts( $args );
 	?>
		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
		
				<div class="cat-lists">						
						<div class="item-st">						
						<div class="thimg">
						<span class="pic-num"><?php echo get_post_images_number();?>图</span>
							<?php ygj_thumbnail(280,210); ?>
						</div>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>						
						<div class="pricebtn">
								<span class="date"><?php  the_time( 'Y-m-d');?></span>
								<span class="views"><?php if( function_exists( 'the_views' ) ) { print '  阅读 '; the_views(); print ' 次  ';  } ;?></span></div>
						</div>							
					</div>							
				<?php endwhile; ?>	
<?php endif; ?>				
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>				
		</main><!-- .site-main -->
		<?php if (get_option('ygj_gdjz') !== '标准页码') {ygj_pagenav();}else{pagenavi();} ?>
	</div><!-- .content-area -->
<div class="clear"></div>
	</div><!-- .site-content -->	
</div>	
<?php get_footer();?>