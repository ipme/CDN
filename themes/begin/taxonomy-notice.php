<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<style type="text/css">
#primary {
	width: 100%;
}
.bulletin {
	margin: 0;
	padding: 0;
	background: none;
	border: none;
	box-shadow: none;
	border-radius: none;
}

.container {
	width: 98%;
	margin: 0 auto;
}

#timeline {
	position: relative;
}

#timeline::before {
	content: '';
	position: absolute;
	top: 0;
	left: 18px;
	height: 100%;
	width: 4px;
	background: #fff;	
    box-shadow: 2px 1px 1px #ddd inset;
}

@media only screen and (min-width: 900px) {
	#timeline::before {
		left: 50%;
		margin-left: -2px;
	}
}
#timeline {
	margin: 15px 0;
}
.timeline-block {
	position: relative;
	margin: 10px 0;
}

.timeline-block:after {
	content: "";
	display: table;
	clear: both;
}

.timeline-block:first-child {
	margin-top: 0;
}

.timeline-block:last-child {
	margin-bottom: 0;
}

@media only screen and (min-width: 900px) {
	.timeline-block {
		margin: 30px 0;
	}

	.timeline-block:first-child {
		margin-top: 0;
	}

	.timeline-block:last-child {
		margin-bottom: 0;
	}
}

.timeline-img {
	position: absolute;
	top: 0;
	left: 0;
	width: 40px;
	height: 40px;
	border-radius: 50%;
	border: 3px solid #ddd;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
}

.timeline-img.cdb {
	border: 3px solid #46c0e6;
}

.timeline-img {
	font-size: 20px;
	font-size: 2rem;
	font-weight: bold;
	text-align: center;
	line-height: 50px;
}

@media only screen and (max-width: 900px) {
	.timeline-img {
		font-size: 14px;
		font-size: 1.4rem;
		font-weight: bold;
		text-align: center;
		line-height: 33px;
	}
}

.timeline-img img {
	display: block;
	width: 24px;
	height: 24px;
	position: relative;
	left: 50%;
	top: 50%;
	margin-left: -12px;
	margin-top: -12px;
}

.timeline-img.time-picture {
	background: #fff;
}

.timeline-img.movie {
	background: #c03b44;
}

.timeline-img.location {
	background: #f0ca45;
}

@media only screen and (min-width: 900px) {
	.timeline-img {
		width: 60px;
		height: 60px;
		left: 50%;
		margin-left: -30px;
		-webkit-transform: translateZ(0);
		-webkit-backface-visibility: hidden;
	}

	.cssanimations .timeline-img.is-hidden {
		visibility: hidden;
	}

	.cssanimations .timeline-img.bounce-in {
		visibility: visible;
		-webkit-animation: bounce-1 0.6s;
		-moz-animation: bounce-1 0.6s;
		animation: bounce-1 0.6s;
	}
}

.timeline-content {
	position: relative;
	margin-left: 60px;
	background: #fff;
	padding: 20px;
	border: 1px solid #ddd;
	border-radius: 2px;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
}

.timeline-content:after {
	content: "";
	display: table;
	clear: both;
}

.timeline-content h2 {
	font-size: 16px;
	font-size: 1.6rem;
}

.timeline-content p, .timeline-content .read-more, .timeline-content .date {
}

.timeline-content .read-more, .timeline-content .date {
	display: inline-block;
}

.timeline-content p {
	margin: 10px 0;
	line-height: 1.6;
}

.timeline-content .read-more {
	float: right;
	padding: 2px 10px;
	background: #acb7c0;
	color: #fff;
	border-radius: 2px;
}

.no-touch .timeline-content .read-more:hover {
	background-color: #595959;
}

a.read-more:hover {
	text-decoration: none;
	background-color: #424242;
}

.timeline-content .date {
	float: left;
	padding: 10px 0;
	opacity: .7;
}

.timeline-content::before {
	content: '';
	position: absolute;
	top: 16px;
	right: 100%;
	height: 0;
	width: 0;
	border: 7px solid transparent;
	border-right: 7px solid #fff;
}

@media only screen and (min-width: 768px) {
	.timeline-content h2 {
		font-size: 16px;
		font-size: 1.6rem;
	}

	.timeline-content p {
		font-size: 14px;
		font-size: 1.4rem;
	}

	.timeline-content .read-more, .timeline-content .date {
		font-size: 14px;
		font-size: 1.4rem;
	}
}

@media only screen and (min-width: 900px) {
	.timeline-content {
		margin-left: 0;
		padding: 1.6em;
		width: 45%;
	}

	.timeline-content::before {
		top: 24px;
		left: 100%;
		border-color: transparent;
		border-left-color: white;
	}

	.timeline-content .read-more {
		float: left;
	}

	.timeline-content .date {
		position: absolute;
		width: 100%;
		left: 122%;
		top: 6px;
		font-size: 16px;
		font-size: 1.6rem;
	}

	.timeline-block:nth-child(even) .timeline-content {
		float: right;
	}

	.timeline-block:nth-child(even) .timeline-content::before {
		top: 24px;
		left: auto;
		right: 100%;
		border-color: transparent;
		border-right-color: white;
	}

	.timeline-block:nth-child(even) .timeline-content .read-more {
		float: right;
	}

	.timeline-block:nth-child(even) .timeline-content .date {
		left: auto;
		right: 122%;
		text-align: right;
	}

	.cssanimations .timeline-content.is-hidden {
		visibility: hidden;
	}

	.cssanimations .timeline-content.bounce-in {
		visibility: visible;
		-webkit-animation: bounce-2 0.6s;
		-moz-animation: bounce-2 0.6s;
		animation: bounce-2 0.6s;
	}
}

@media only screen and (min-width: 900px) {
	.cssanimations .timeline-block:nth-child(even) .timeline-content.bounce-in {
		-webkit-animation: bounce-2-inverse 0.6s;
		-moz-animation: bounce-2-inverse 0.6s;
		animation: bounce-2-inverse 0.6s;
	}
}
</style>
<section id="timeline" class="container">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<div class="timeline-block">
		<div id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">

			<div class="timeline-img time-picture">
				<?php the_time( 'd' ) ?>
				<!-- <?php echo get_avatar( get_the_author_meta('email'), '64' ); ?> -->
			</div>

			<div class="timeline-content">
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<p>
					<?php if (has_excerpt()){ ?>
						<?php the_excerpt() ?>
					<?php } else { echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 60,"..."); } ?>
				</p>
				<a href="<?php the_permalink(); ?>" class="read-more" rel="bookmark">阅读全文</a>
				<span class="date"><?php the_time( 'Y年m月' ) ?></span>
			</div>

		</div><!-- #post -->
	</div>

	<?php endwhile;endif; ?>

	<?php begin_pagenav(); ?>

</section><!-- .content-area -->

<script type="text/javascript">
$("#timeline .timeline-img:even").addClass("cdb"); 
</script>

<?php get_footer(); ?>