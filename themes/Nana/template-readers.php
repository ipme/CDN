<?php
/*
Template Name: 读者百强榜
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
<?php

function readers_wall($limit = "100")
{
	global $wpdb;
	$counts = $wpdb->get_results("SELECT count(comment_author) AS cnt, comment_author, comment_author_url, comment_author_email FROM $wpdb->comments WHERE user_id!='1' AND comment_author!='1' AND comment_approved='1' AND comment_type='' GROUP BY comment_author ORDER BY cnt DESC LIMIT $limit");
	$i = 0;
    $type='';
	foreach ($counts as $count ) {
		$i++;
		$c_url = $count->comment_author_url;

		if (!$c_url) {
			$c_url = "http://boke123.net";
		}

		$tt = $i;

		if ($i == 1) {
			$tt = "读者之青龙";
		}
		else if ($i == 2) {
			$tt = "读者之白虎";
		}
		else if ($i == 3) {
			$tt = "读者之朱雀";
		}
		else if ($i == 4) {
			$tt = "读者之玄武";
		}
		else {
			$tt = "第" . $i . "名";
		}
		$avatar = my_avatar( $count->comment_author_email,36,$default='',$count->comment_author);
		if ($i < 5) {
			$type .= "<a class=\"item-top item-" . $i . "\" target=\"_blank\" href=\"" . $c_url . "\" title=\"【" . $tt . "】评论：" . $count->cnt . "\"><h4>【" . $tt . "】</h4>".$avatar."<strong>" . $count->comment_author . "</strong>" . $c_url . "</a>";
		}
		else {
			$type .= "<a target=\"_blank\" href=\"" . $c_url . "\" title=\"【" . $tt . "】评论：" . $count->cnt . "\">" . $avatar . $count->comment_author . "</a>";
		}
	}

	echo $type;
}
?>
<div class="readers">
<?php readers_wall(100);?>
</div>
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