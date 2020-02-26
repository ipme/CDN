<?php get_header();?>
	<div id="content" class="site-content">
	<?php if (get_option('ygj_ddad') == '显示') { get_template_part('/inc/ad/ad_dhl');  } ?>
	<div id="gensui">
		<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if (get_option('ygj_hdpkg') == '显示') { get_template_part ('/inc/slider');} ?>
	<?php if (get_option('ygj_new_p') == '显示') { ?>
	
	 <div id="post_list_box" class="border_gray">
 <?php
	$scrollcount = get_option('ygj_new_post');
 ?>
<?php query_posts('&showposts='.$scrollcount.'&ignore_sticky_posts=1.&cat='.get_option('ygj_new_exclude')); while ( have_posts() ) : the_post();$do_not_duplicate[] = $post->ID; ?>

<article id="post-<?php the_ID(); ?>" class="archive-list">
		<figure class="thumbnail">		
			<?php ygj_thumbnail(270,180); ?>					
		</figure>
		<header class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h2>		
		</header><!-- .entry-header -->
		
		<div class="entry-content">
			
			<span class="entry-meta">
				<span class="post_cat">
				<?php 
					$category = get_the_category(); 
					if($category[0]){
					echo '<a href='.get_category_link($category[0]->term_id ).'>'.$category[0]->cat_name.'</a>';
					}
				?>
			</span>
				<span class="post_spliter">•</span>
				<span class="date" title="<?php the_time( 'Y/m/d H:i');?>"><?php
        echo timeago(get_gmt_from_date(get_the_time('Y-m-d G:i:s'))) ?></span>			
			</span>		
			
			<div class="archive-content">			 				
				<?php if (has_excerpt()){ echo wp_trim_words( get_the_excerpt(), 80, '...' );} elseif (post_password_required()){echo wp_trim_words( get_the_content(), 22, '...' ); }else { echo wp_trim_words( get_the_content(), 80, '...' );} ?>
			</div>
			<div class="archive-tag"><span class="views"><?php if( function_exists( 'the_views' ) ) { print '  阅读 '; the_views(); print ' 次  ';  } ;?></span><?php the_tags('', '', '');?></div>
			<div class="clear"></div>
		</div><!-- .entry-content -->
	</article><!-- #post -->

 	<!-- ad -->
	<?php if ($wp_query->current_post == 0) : ?>
	<?php if (get_option('ygj_adh') == '显示') { get_template_part('/inc/ad/ad_h'); } ?>
	<?php endif; ?>	
	<!-- end: ad -->
<?php endwhile; ?>
</div>
	<?php } ?>
	<div class="clear"></div>	
	<?php if (get_option('ygj_taobao') == '显示') { get_template_part('/inc/taobao'); } ?>
<?php if (get_option('ygj_syytsl') == '显示') { ?>
	<div class="line-big">
		<?php 
		$display_categories = explode(',', get_option('ygj_catldt') ); 
		foreach ($display_categories as $category) { 
			query_posts( array(
				'showposts' => 1,
				'cat' => $category,
				'ignore_sticky_posts'=> 1,
				'post__not_in' => $do_not_duplicate
				)
			);
		?>
		<div class="xl3 xm3">
			<div class="cat-box">
				<?php while (have_posts()) : the_post(); ?>
				<h3 class="cat-title"><span class="syfl"><?php single_cat_title(); ?></span><span class="catmore"><a href="<?php echo get_category_link($category);?>" title="更多<?php single_cat_title(); ?>文章">More></a></span></h3>
				<div class="clear"></div>
				<div class="cat-site">
					<div class="item"> 
			<?php ygj_thumbnail(550,265); ?>
<div style="z-index: 1;"> 			
<span class="txt"><?php the_title(); ?></span>
<span class="txt-bg"></span>
</div>
	</div>
					
			
					<?php endwhile; ?>
					<div class="clear"></div>
					<ul class="cat-list">
					<?php
					query_posts( array(
						'showposts' => get_option('ygj_cat_nddt'),
						'cat' => $category,
						'offset' => 1,
						'ignore_sticky_posts'=> 1,
						'post__not_in' => $do_not_duplicate
						)
		 			);
					?>
					<?php while (have_posts()) : the_post(); ?>
						<span class="list-date"><?php the_time('m/d') ?></span>					
						<li class="list-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo cut_str($post->post_title,50); ?></a></li>	
					<?php endwhile; ?>						
					</ul>

				</div>
			</div>
		</div>
		<?php } ?>
		<div class="clear"></div>	
	</div>
<?php } ?>
<?php if (get_option('ygj_adhx') == '显示') { get_template_part('/inc/ad/ad_hx'); } ?>
<?php if (get_option('ygj_sywtsl') == '显示') { ?>
<div class="line-one">
	<?php 
		$display_categoriesdl = explode(',', get_option('ygj_catld') ); 
		foreach ($display_categoriesdl as $categorydl) { ?>
		<?php
			query_posts( array(
				'showposts' => 1,
				'cat' => $categorydl,
				'ignore_sticky_posts'=> 1,
				'post__not_in' => $do_not_duplicate
				)
			);
		?>
		<?php while (have_posts()) : the_post(); ?>
		<div class="cat-box">
			<h3 class="cat-title"><span class="syfl"><?php single_cat_title(); ?></span><span class="catmore"><a href="<?php echo get_category_link($categorydl);?>" title="更多<?php single_cat_title(); ?>文章">More></a></span></h3>
			<div class="clear"></div>
			<div class="cat-site">
				<div class="cat-dt">
						<figure class="line-one-thumbnail">		
							<?php ygj_thumbnail(371,247); ?>				
						</figure>
						<header class="entry-header">
							<h2 class="entry-title"><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></h2>		
						</header><!-- .entry-header -->
						<div class="entry-content">	
							<div class="archive-content">			 				
								<?php if (has_excerpt()){ echo wp_trim_words( get_the_excerpt(), 80, '...' );} else { echo wp_trim_words( get_the_content(), 80, '...' ); } ?>
							</div>
							<div class="archive-tag">
								<span class="date"><?php  the_time( 'Y-m-d');?></span>
								<span class="views"><?php if( function_exists( 'the_views' ) ) { print '  阅读 '; the_views(); print ' 次  ';  } ;?></span><?php the_tags('', '', '');?></div>
							<div class="clear"></div>
						</div><!-- .entry-content -->
					</div>
				<?php endwhile; ?>
				<ul class="cat-one-list">
				<?php
					query_posts( array(
						'showposts' => get_option('ygj_cat_ndt'),
						'cat' => $categorydl,
						'offset' => 1,
						'ignore_sticky_posts'=> 1,
						'post__not_in' => $do_not_duplicate
						)
		 			);
				?>
				<?php while (have_posts()) : the_post(); ?>
				<div class="cat-lists">						
						<div class="item-st"> 
						<div class="thimg">
						<?php ygj_thumbnail(227,135); ?>
						</div>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>						
						<div class="pricebtn"><span class="archive-tag">
								<span class="date"><?php  the_time( 'Y-m-d');?></span>
								<span class="views"><?php if( function_exists( 'the_views' ) ) { print '  阅读 '; the_views(); print ' 次  ';  } ;?></span></span></div>
						</div>											
					</div>							
				<?php endwhile; ?>						
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>	
	<?php } ?>		
	</div>	
	<?php } ?>
		<div class="clear"></div>			
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div>
<div class="clear"></div>
	</div><!-- .site-content -->				
<?php get_footer();?>