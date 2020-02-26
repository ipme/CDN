<?php
/*
Template Name: 文章更新
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>

<style type="text/css">
.archives-header {
	position: relative;
	margin: 0 0 10px 0;
	padding: 0;
	border: 1px solid #ddd;
	border-radius: 2px;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
}

.archives-title {
	position: absolute;
	top: 50%;
	left: 30px;
	right: 30px;
	font-size: 26px;
	font-size: 2.6rem;
	color: #fff;
	transform: translateY(-50%);
	-webkit-transform: translateY(-50%);
	text-shadow: 5px 2px 5px #000;
}

.archives-meta {
	position: absolute;
	bottom: 10px;
	right: 10px;
}

.archives-meta li {
	float: left;
	font-size: 16px;
	color: #fff;
	margin: 0 5px 0 0;
	padding: 5px 20px;
	text-shadow: 5px 2px 5px #000;
}

.year {
	display: none;
}

.year-m {
	float: right;
	color: #999;
	margin: 0 5px 0 0;
}

.mon {
	float: left;
	font-size: 14px;
	color: #666;
	font-weight: bold;
	display: block;
	margin: 0 0 8px 5px;
}

.mon-list {
	position: relative;
	background: #fff;
	margin: 0 0 10px 0;
	padding: 0;
	border-radius: 2px;
	border: 1px solid #ddd;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
}

.day-box {
	margin: 0 0 -1px 0;
	padding: 20px 0 5px 0;
	border-bottom: 1px solid #ddd;
}

.day-w {
	float: left;
	width: 100px;
}

.day-list {
	margin: -5px 0 15px 115px;
}

.day-list li {
	line-height: 240%;
	padding: 0 15px;
	width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.day-list li .be {
	color: #999;
	margin: 0 10px 0 0;
}

.days {
	float: left;
	font-size: 30px;
	color: #009ae5;
	font-weight: bold;
	margin: 0 0 0 20px;
	padding: 2px 5px 0 0;
}

.future-t {
	float: left;
	font-size: 15px;
	color: #009ae5;
	font-weight: bold;
	margin: 0 0 0 20px;
	padding: 2px 5px 0 0;
}

.future-t i {
	font-size: 20px;
	font-weight: normal;
	margin: 0 10px 0 0;
}

.future-post {
	padding: 20px 20px 5px 0;
}

.week-d {
	float: left;
	font-size: 12px;
	color: #999;
	font-weight: bold;
	line-height: 15px;
	margin: 0 0 10px 0;
}

.single-content {
	margin-top: 0;
}

.aligncenter {
	margin: 0 auto;
}

.single-content p {
	margin: 0;
}

@media screen and (max-width:480px) {
	.archives-title {
		font-size: 15px;
		padding: 5px;
	}

	.archives-meta li {
		font-size: 14px;
		margin: 0 5px 0 0;
		padding: 0 5px;
	}

	.day-w {
		width: 100%;
	}
	.day-list {
		margin: 0;
	}
}
</style>
<?php
function up_archives_list() {
	if( !$output = get_option('up_archives_list') ){
		$output = '<div id="archives-box">';
		$the_query = new WP_Query( 'posts_per_page=-1&cat='.zm_get_option('cat_up_n').'&year='.zm_get_option('year_n').'&monthnum='.zm_get_option('mon_n').'&ignore_sticky_posts=1' );
		// 如果想使用多个模板，分别在&cat=&year=&monthnum=这句的“=”后面依次添加文章分类ID,发表年份，发表月份，可以调用指定文章，留空则调用全部文章
		// 例如：$the_query = new WP_Query( 'posts_per_page=-1&cat=10,9&year=2017&monthnum=5&ignore_sticky_posts=1' );
		$year=0; $mon=0;$day=0; $i=0; $j=0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$year_tmp = get_the_time('Y');
			$mon_tmp = get_the_time('m');
			$day_tmp = get_the_time('d');
			$y=$year; $m=$mon;
			if ($day != $day_tmp && $day > 0) $output .= '</ul></li>';
			if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
			if ($year != $year_tmp && $year > 0) $output .= '</ul>';
			if ($year != $year_tmp) {
				$year = $year_tmp;
				$output .= '<h3 class="year"><i class="be be-calendar2"></i> '. $year .' 年</h3>';
				$output .= '<ul class="post-list">';
				$output .= '<div class="year-box">';
			}
			if ($mon != $mon_tmp) {
				$mon = $mon_tmp;
				$output .= '<li><span class="mon">'. $mon .'月</span><span class="year-m">'. $year .'年</span><div class="clear"></div>';
				$output .= '<ul class="mon-list">';
			}

			if ($day != $day_tmp) {
				$day = $day_tmp;
				$output .= '<li class="day-box"><span class="day-w"><span class="days">'. get_the_time('d') .'</span><span class="week-d">日<br />'. get_the_time('l') .'</span><span class="clear"></span></span>';
				$output .= '<ul class="day-list">';
			}
			$output .= '<li><a href="'. get_permalink() .'">'. get_the_title() .'</a>';
		endwhile;
		wp_reset_postdata();
		$output .= '</ul>';
		$output .= '</li>';
		$output .= '</div>';
		$output .= '</ul>';
		$output .= '</div>';
		update_option('up_archives_list', $output);
	}
	echo $output;
}
?>
<div id="archives-up" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>">
				<header class="archives-header">
					<h1 class="archives-title"><?php the_title(); ?></h1>
		
						<div class="single-content">
							<?php the_content(); ?>
						</div>
	
					<ul class="archives-meta">
						<li>今日更新：
							<?php
								$today = getdate();
								$query = new WP_Query( 'year=' . $today["year"] . '&monthnum=' . $today["mon"] . '&cat='.zm_get_option('cat_up_n').'&day=' . $today["mday"]);
								$postsNumber = $query->found_posts;
								echo $postsNumber;
							?>
						</li>
						<li>本周更新：
							<?php
								$week = date( 'W' );
								$year = date( 'Y' );
								$query = new WP_Query( 'year=' . $year . '&cat=&w=' . $week );
								$postsNumber = $query->found_posts;
								echo $postsNumber;
							?>
						</li>
						<div class="clear"></div>
					</ul>
				</header>
			</article>
		<?php endwhile;?>
		<div class="up-archives">
			<ul class="mon-list future-post">
				<span class="future-t"><i class="be be-file"></i>即将发表</span>
				<ul class="day-list">
					<?php
					$my_query = new WP_Query( array ( 'post_status' => 'future','cat' => '','order' => 'ASC','showposts' => 5,'ignore_sticky_posts' => '1'));
					if ($my_query->have_posts()) {
						while ($my_query->have_posts()) : $my_query->the_post();
							$do_not_duplicate = $post->ID; ?>
							<li><?php the_title(); ?></li>
						<?php endwhile;
					} else {
						echo '<li>暂无，敬请期待！</li>';
					}
					?>
				</ul>
			</ul>

			<?php up_archives_list(); ?>
		</div>
	</main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>