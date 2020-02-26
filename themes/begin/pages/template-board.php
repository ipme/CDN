<?php
/*
Template Name: 留言板
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<style type="text/css">
#primary {
	width: 100%;
}

.comment-reply-title span, .comments-title{
	display: none;
}

.comment-reply-title:after {
	content: '给我留言';
}

.barrager {
	position: fixed;
	top: 120px;
	left: 0;
	width: 100%;
	max-height: 500px;
	z-index: 99999;
}

.barrager div {
	position: absolute;
}

.barrager a {
	float: left;
	color: #fff;
	font-size: 15px;
	font-size: 1.5rem;
	line-height: 24px;
	width: 320px;
	white-space: nowrap;
	word-wrap: normal;
	text-overflow: ellipsis;
	overflow: hidden;
	padding: 5px;
	background: rgba(0, 0, 0, 0.5);
	border-radius: 50px;
}

.barrager a:hover {
	color: #ccc;
}

.barrager-avatar img {
	float: left;
	width: 24px;
	height: 24px;
	margin: 0 8px 0 0;
	border-radius: 25px;
}

#mask {
	position: fixed;
	left: 0;
	top: 0;
	background: #000;
	width: 100%;
	height: 100%;
	opacity: .60;
	z-index: 9999;
}

.barrager-close {
	float: right;
	color: #fff;
	line-height: 24px;
	cursor: pointer;
	margin: 30px 20px 0 0;
	padding: 5px 10px;
	border-radius: 2px;
	border: 1px solid #fff;
}
</style>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template/content', 'page' ); ?>
			<div id="barrager" class="barrager">
				<?php 
				$no_comments = false;
				$avatar_size = 64;
				$comments_query = new WP_Comment_Query();
				$comments = $comments_query->query( array_merge( array( 'number' => 15 , 'post_id' => 0 ) ) );
				if ( $comments ) : foreach ( $comments as $comment ) : ?>

				<div>
					<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="发表在：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
						<span class="barrager-avatar">
							<?php if (zm_get_option('cache_avatar')) { ?>
								<?php echo begin_avatar( $comment->comment_author_email, $avatar_size ); ?>
							<?php } else { ?>
								<?php echo get_avatar( $comment->comment_author_email, $avatar_size ); ?>
							<?php } ?>
						</span>
						<span class="comment_author"><?php echo get_comment_author( $comment->comment_ID ); ?></span>
						<?php echo convert_smilies($comment->comment_content); ?>
					</a>
				</div>

				<?php endforeach; else : ?>
					<div>暂无留言</div>
					<?php $no_comments = true;
				endif; ?>
				<div class="clear"></div>
			</div>
			<div id="mask"><div class="barrager-close" >关闭弹幕</div></div>
			<!-- <a href="#" rel="barrager" class="barrager-open" ><i class="be be-businesscard"></i>弹幕</a> -->
			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template( '', true ); ?>
			<?php endif; ?>

		<?php endwhile; ?>

		</main>
	</div>

<script type="text/javascript">
$(document).ready(function() {
 	// 弹窗

	$("#mask, .poster-close").click(function() {
		$("#mask, #barrager").fadeOut();
		return false
	})

 	// 滚动
	$(".barrager").barrager()
}); 

(function() {
	var Barrager = function(ele, options) {
		var defaults = {
			wrap: ele
		};
		this.settings = $.extend({},
		defaults, options || {});
		this._init();
		this.bindEven();
	};
	Barrager.prototype = {
		_init: function() {
			var item = $(this.settings.wrap).find("div");
			for (var i = 0; i < item.length; i++) {
				item.eq(i).css({
					top: this.getReandomTop() + "px",
					fontSize: this.getReandomSize() + "px"
				});
				item.eq(i).css({
					right: -item.eq(i).width()
				})
			}
			this.randomTime(0);
		},

		getReandomTop: function() {
			var top = (Math.random() * 450).toFixed(1);
			return top;
		},
		getReandomSize: function() {
			var size = (12 + Math.random() * 12);
			return size;
		},
		getReandomTime: function() {
			var time = Math.floor((12 + Math.random() * 12));
			return time * 2000; // 滚动速度
		},
		randomTime: function(n) {
			var obj = $(this.settings.wrap).find("div");
			var _this = this;
			var len = obj.length;
			if (n >= len) {
				n = 0;
			}
			setTimeout(function() {
				n++;
				_this.randomTime(n)
			},
			30);// 延迟
			var item = obj.eq(n),
			_w = item.outerWidth(!0);
			item.animate({
				left: -_w
			},
			_this.getReandomTime(), "linear",
			function() {
				item.css({
					right: -_w,
					left: ""
				});
				_this.randomTime(n)
			});
		},
	};
	$.fn.barrager = function(opt) {
		var bger = new Barrager(this, opt);
	}
})(jQuery);
</script>

<?php get_footer(); ?>