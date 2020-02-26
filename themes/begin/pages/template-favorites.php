<?php
/*
Template Name: 网址收藏
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/sites.css" />

<style type="text/css">
#primary {
 	float: right;
}
#sidebar {
	float: left;
}
</style>

<div class="favorites-top">
	<div class="top-date rili">
		<?php the_title( '<h1 class="favorites-title">', '</h1>' ); ?>
		<span id=localtime></span>
		<script type="text/javascript">
		function showLocale(objD){
			var str,colorhead,colorfoot;
			var yy = objD.getYear();
			if(yy<1900) yy = yy+1900;
			var MM = objD.getMonth()+1;
			if(MM<10) MM = '0' + MM;
			var dd = objD.getDate();
			if(dd<10) dd = '0' + dd;
			var hh = objD.getHours();
			if(hh<10) hh = '0' + hh;
			var mm = objD.getMinutes();
			if(mm<10) mm = '0' + mm;
			var ss = objD.getSeconds();
			if(ss<10) ss = '0' + ss;
			var ww = objD.getDay();
			if  ( ww==0 )  colorhead="<font color=\"#FF0000\">";
			if  ( ww > 0 && ww < 6 )  colorhead="<font color=\"#373737\">";
			if  ( ww==6 )  colorhead="<font color=\"#008000\">";
			if  (ww==0)  ww="星期日";
			if  (ww==1)  ww="星期一";
			if  (ww==2)  ww="星期二";
			if  (ww==3)  ww="星期三";
			if  (ww==4)  ww="星期四";
			if  (ww==5)  ww="星期五";
			if  (ww==6)  ww="星期六";
			colorfoot="</font>"
			str = colorhead + yy + "年" + MM + "月" + dd + "日 " + hh + ":" + mm + ":" + ss + "  " + ww + colorfoot;
			return(str);
		}
		function tick()
		{
			var today;
			today = new Date();
			document.getElementById("localtime").innerHTML = showLocale(today);
			window.setTimeout("tick()", 1000);
		}
		tick();
		</script>
	</div>

	<div class="tianqi rili">
		<iframe allowtransparency="true" frameborder="0" width="385" height="75" scrolling="no" src="//tianqi.2345.com/plugin/widget/index.htm?s=1&z=1&t=0&v=0&d=3&bd=0&k=&f=&q=1&e=1&a=1&c=54511&w=385&h=96&align=left"></iframe>
	</div>
	<div class="clear"></div>
</div>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<!-- 正文 -->
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<div class="single-content">
						<?php the_content(); ?>
						<?php if (zm_get_option('xzh_gz')) { ?>
							 <script>cambrian.render('tail')</script>
						<?php } ?>
						<?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) : ?><style>#primary {width: 100%;}#sidebar, .r-hide, .s-hide {display: none;}</style><?php endif; ?>
						<?php if ( get_post_meta($post->ID, 'down_link_much', true) ) : ?><style>.down-link {float: left;}</style><?php endif; ?>

						<?php wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span>上一页</span>', 'nextpagelink' => "")); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); ?>
						<?php wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => "<span>下一页</span>")); ?>
					</div>
					<div class="clear"></div>
					<?php if ( get_post_meta($post->ID, 'no_abstract', true) ) : ?><style>.abstract {display: none;}</style><?php endif; ?>
				</div><!-- .entry-content -->

			</article><!-- #page -->
		<?php endwhile; ?>

		<!-- 网址小工具 -->
		<div id="sites-widget-one" class="sites-widget">
			<?php dynamic_sidebar( 'favorites-one' ); ?>
			<div class="clear"></div>
		</div>

		<!-- 全部网址分类 -->
		<?php
		$taxonomy = 'favorites';
		$terms = get_terms($taxonomy); foreach ($terms as $cat) {
		$catid = $cat->term_id;
		$args = array(
			'showposts' => 100,
			'meta_key' => 'sites_order',
			'orderby' => 'meta_value',
			'order' => 'id',
			'tax_query' => array( array( 'taxonomy' => $taxonomy, 'terms' => $catid, 'include_children' => false ) )
		);
		$query = new WP_Query($args);
		if( $query->have_posts() ) { ?>
		<article class="sites sites-all wow fadeInUp" data-wow-delay="0.3s">
			<div class="sites-cats">
				<h3 class="sites-cat"><?php echo $cat->name; ?></h3>
				<span class="sites-more"><a href="<?php echo get_term_link( $cat ); ?>" ><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a></span>
			</div>
			<div class="clear"></div>
			<div class="sites-link">
				<div class="sites-5">
					<?php while ($query->have_posts()) : $query->the_post();?>
						<?php if ( get_post_meta($post->ID, 'sites_img_link', true) ) { ?>
							<?php $sites_img_link = get_post_meta($post->ID, 'sites_img_link', true); ?>
							<span class="sites-title sites-title-img wow fadeInUp" data-wow-delay="0s">
								<figure class="picture-img sites-img">
									<?php if ( get_post_meta($post->ID, 'shot_img', true) ) { ?>
										<?php zm_sites_shot_img(); ?>
									<?php } else { ?>
										<?php zm_sites_thumbnail(); ?>
									<?php } ?>
								</figure>
								<a href="<?php echo $sites_img_link; ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a>
							</span>
						<?php } else { ?>
							<?php $sites_link = get_post_meta($post->ID, 'sites_link', true); ?>
							<?php if ( get_post_meta($post->ID, 'shot_img', true) ) { ?>
							<span class="sites-title sites-title-img wow fadeInUp" data-wow-delay="0s">
								<figure class="picture-img sites-img">
									<?php zm_sites_shot(); ?>
								</figure>
								<a href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a>
							</span>
							<?php } else { ?>
								<span class="sites-title wow fadeInUp" data-wow-delay="0.3s"><a class="sites-title-t" href="<?php echo $sites_link; ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></span>
							<?php } ?>
						<?php } ?>
					<?php endwhile; ?>
					<div class="clear"></div>
				</div>
			</div>
		</article>
		<?php } wp_reset_query(); ?>
		<?php } ?>
	</main>
</div>

<?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) { ?>
<?php } else { ?>
<div id="sidebar" class="widget-area">
	<div class="wow fadeInUp" data-wow-delay="0.5s">
		<?php if ( ! dynamic_sidebar( 'favorites' ) ) : ?>
			<aside id="add-widgets" class="widget widget_text">
				<h3 class="widget-title"><i class="be be-warning"></i>添加小工具</h3>
				<div class="textwidget">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“网址侧边栏”添加小工具</a>
				</div>
				<div class="clear"></div>
			</aside>
		<?php endif; ?>
	</div>
</div>
<?php } ?>
<?php get_footer(); ?>