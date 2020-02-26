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
							<span class="xiaoshi">
								<?php if(get_post_meta($post->ID, 'wzurl', true)){$wzurl = get_post_meta($post->ID, 'wzurl', true);}else{$wzurl=get_the_permalink();}?>
								<?php if(get_post_meta($post->ID, 'wzzz', true)){$wzzz = get_post_meta($post->ID, 'wzzz', true);}else{$wzzz=get_the_author();}?>
								<?php if (!get_option('ygj_post_wzlx') ) { ?>
									<span class="leixing">
										<?php if ( get_post_meta($post->ID, 'tgwz', true) ) { ?>
											<span class="tglx">投稿</span>
										<?php } elseif ( get_post_meta($post->ID, 'zzwz', true) ) { ?>
											<span class="zzlx">转载</span>
										<?php } else { ?>
											<span class="yclx">原创</span>
										<?php } ?>
									</span>
								<?php } ?>
								<?php if (!get_option('ygj_post_author') ) { ?>
									<a href="<?php echo $wzurl; ?>" rel="nofollow" target="_blank"><?php echo $wzzz; ?></a>&nbsp;
								<?php } ?>
							</span>
							<span class="date"><?php the_time( 'Y-m-d H:i' ) ?>&nbsp;</span>
							<span class="views"><?php if( function_exists( 'the_views' ) ) { print '  阅读 '; the_views(); print ' 次  ';  } ;?></span>
							
								
					
					<?php if (get_option('ygj_post_comment') ) { ?>
					<span class="comment"><?php comments_popup_link( ' 评论 0 条', ' 评论 1 条', ' 评论 % 条' ); ?></span>
					<?php } ?>
					
							<span class="edit"><?php edit_post_link('编辑', '  ', '  '); ?></span>
						</div>		
					</header><!-- .entry-header -->

					<?php if (get_option('ygj_g_single') == '显示') { get_template_part('/inc/ad/ad_single'); } ?>
					<?php if ( has_excerpt() ) { ?>
						<span class="abstract"><strong>摘要：</strong><?php the_excerpt() ?></span>
					<?php } ?>
					<div class="entry-content">
						<div class="single-content">			
							<?php the_content(); ?>
							<?php wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="fa fa-angle-left"></i></span>', 'nextpagelink' => "")); ?>
							<?php wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); ?>
							<?php wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => '<span><i class="fa fa-angle-right"></i></span>')); ?>			
						</div>
						<div class="clear"></div>
						<div class="xiaoshi">
							<div class="single_banquan">	
								<strong>本文地址：</strong><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"  target="_blank"><?php the_permalink() ?></a><br/>
								<strong>关注我们：</strong>请关注一下我们的微信公众号：<a class="iboke112" href="JavaScript:void(0)">扫描二维码<span><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/gongzhonghao.jpg" alt="<?php bloginfo('name'); ?>的公众号"></span></a>，公众号：HiiPhone<br/>
								<?php if ( get_post_meta($post->ID, 'tgwz', true) ) : ?>
									<strong>温馨提示：</strong>文章内容系作者个人观点，不代表<?php bloginfo('name'); ?>对观点赞同或支持。<br/>
									<strong>版权声明：</strong>本文为投稿文章，感谢&nbsp;<a href="<?php echo $wzurl; ?>" target="_blank" rel="nofollow"><?php echo $wzzz; ?></a>&nbsp;的投稿，版权归原作者所有，欢迎分享本文，转载请保留出处！
								<?php elseif ( get_post_meta($post->ID, 'zzwz', true) ) : ?>
									<strong>温馨提示：</strong>文章内容系作者个人观点，不代表<?php bloginfo('name'); ?>对观点赞同或支持。<br/>
									<strong>版权声明：</strong>本文为转载文章，来源于&nbsp;<a href="<?php echo $wzurl; ?>" target="_blank" rel="nofollow"><?php echo $wzzz; ?></a>&nbsp;，版权归原作者所有，欢迎分享本文，转载请保留出处！
								<?php else:  ?>
									<strong>版权声明：</strong>本文为原创文章，版权归&nbsp;<a href="<?php echo $wzurl; ?>" target="_blank"><?php echo $wzzz; ?></a>&nbsp;所有，欢迎分享本文，转载请保留出处！
								<?php endif; ?>
							</div>
						</div>
						<?php get_template_part( 'inc/social' ); ?>
						<?php get_template_part('inc/file'); ?>
						<div class="clear"></div>
						<?php get_template_part('inc/prenext_post');?>
					</div><!-- .entry-content -->
				</article><!-- #post -->
				<?php if (get_option('ygj_taobaowz') == '显示') { get_template_part('/inc/taobao'); } ?>				
				<?php if (get_option('ygj_adt') == '显示') { get_template_part('/inc/ad/ad_single_d'); } ?>
				<?php if (get_option('ygj_related_ys') == '列表式') {get_template_part('inc/realted_post');}else{get_template_part('inc/realted_grid');}?>
				<?php if (get_option('ygj_g_comment') == '显示') { get_template_part( 'inc/ad/ad_comment' ); } ?>
				<?php comments_template( '', true ); ?>			
			<?php wp_reset_query();endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<?php get_sidebar();?>
	<div class="clear"></div>
</div><!-- .site-content -->
<?php get_footer();?>