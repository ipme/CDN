<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found page">
				<header class="entry-header">
					<h1 class="page-title"><?php echo stripslashes( zm_get_option('404_t') ); ?></h1>
				</header><!-- .page-header -->

				<div class="single-content">
					<p style="text-align: center;"><?php echo stripslashes( zm_get_option('404_c') ); ?></p>

					<?php get_search_form(); ?>

					<br /><br /><br /><br /><br />
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>