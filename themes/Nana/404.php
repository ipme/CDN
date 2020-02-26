<?php get_header();?>
<div id="content" class="site-content">	
<div class="clear"></div>
<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<section class="error-404 not-found page">
				<header class="entry-header">
					<h1 class="page-title">亲，你迷路了！</h1>
				</header><!-- .page-header -->
<?php if (get_option('ygj_g_single') == '关闭') { ?>
	<?php { echo ''; } ?>
	<?php } 
	else { get_template_part('/inc/ad/ad_single'); } ?>
				<div class="single-content">
					<p style="text-align: center;">亲，该网页可能搬家了！</p>
					<?php get_search_form(); ?>
					<br /><br /><br /><br /><br />
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_sidebar();?>
<div class="clear"></div>
</div><!-- .site-content -->
<?php get_footer();?>