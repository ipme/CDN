<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<div class="bbp-crumb"><?php bbp_breadcrumb(); ?></div>

<div id="primary" class="content-area bbp-primary">
	<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="bbp-entry-header">
					<?php if ( bbp_is_forum_archive()) { ?>
						<div class="bbp-image">
							<img src="http://wx3.sinaimg.cn/large/0066LGKLly1fgcbh8r7rcj31hc0dwtr7.jpg" alt="论坛">
							<div class="clear"></div>
						</div>
						<h1 class="forums-entry-title"><i class="be be-peopleoutline"></i>论坛</h1>
					<?php } ?>
					<?php if ( bbp_is_single_forum()) { ?>
						<div class="bbp-image">
							<img src="http://wx1.sinaimg.cn/large/0066LGKLly1fhdkqvy1wuj31hc0dwt9s.jpg" alt="版块">
							<div class="clear"></div>
						</div>
						<?php the_title( '<h1 class="forum-entry-title"><i class="be be-editor"></i>版块：', '</h1>' ); ?></h1>
					<?php } ?>
					<?php if ( bbp_is_single_topic()) { ?>
						<div class="bbp-image">
							<img src="http://wx3.sinaimg.cn/large/0066LGKLly1fhdkstmp1pj31hc0dwdqk.jpg" alt="话题">
							<div class="clear"></div>
						</div>
						<?php the_title( '<h1 class="topic-entry-title">', '</h1>' ); ?>
					<?php } ?>
				</header>

				<div class="bbp-content">
					<?php the_content(); ?>
				</div>

			</article>
		<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>