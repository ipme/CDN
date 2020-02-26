<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( post_password_required() ) {
	return;
}
?>

<?php
	$numPingBacks = 0;
	$numComments  = 0;
	foreach ($comments as $comment)
	if (get_comment_type() != "comment") $numPingBacks++; else $numComments++;
?><!-- 引用 -->


<div class="scroll-comments"></div>

<div id="comments" class="comments-area">
	<?php if ( comments_open() ) : ?>
		<div id="respond" class="comment-respond wow fadeInUp ms" data-wow-delay="0.3s">
			<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
				<p class="comment-nologin">
					<?php if (zm_get_option('user_l')) { ?>
						<?php print '' . sprintf(__( '您必须', 'begin' )) . ''; ?><a class="login-respond" href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e( '登录', 'begin' ); ?></a><?php _e( '才能发表评论！', 'begin' ); ?>
					<?php } else { ?>
						<?php print '' . sprintf(__( '您必须', 'begin' )) . ''; ?><span class="login-respond show-layer" data-show-layer="login-layer" role="button"><?php _e( '登录', 'begin' ); ?></span><?php _e( '才能发表评论！', 'begin' ); ?>
					<?php } ?>
				</p>
			<?php else : ?>
				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
					<?php if ( $user_ID  || ( '' != $comment_author )) { ?>
					<?php } else { ?>
						<div class="comment-user-inf">
							<div class="user-avatar">
								<img alt="匿名" src="<?php echo zm_get_option('logo_small_b'); ?>">
							</div>
							<div class="comment-user-inc">
								<h3 id="reply-title" class="comment-reply-title">
									<span><?php _e( '发表评论', 'begin' ); ?></span>
									<small><?php cancel_comment_reply_link( '' . sprintf(__( '取消回复', 'begin' )) . '' ); ?></small>
								</h3>
								<span class="comment-user-name"><?php _e( '匿名网友', 'begin' ); ?></span>
								<span class="comment-user-alter"><?php if (zm_get_option('not_comment_form')) { ?><span><?php _e( '填写信息', 'begin' ); ?></span><?php } ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if ( $user_ID ) : ?>

						<div class="comment-user-inf">
							<div class="user-avatar">
								<?php global $current_user;wp_get_current_user();
									if (zm_get_option('cache_avatar')) {
										echo begin_avatar( $current_user->user_email, 64);
									} else {
										echo get_avatar( $current_user->user_email, 64);
									}
								?>
							</div>
							<div class="comment-user-inc">
								<h3 id="reply-title" class="comment-reply-title">
									<span><?php _e( '发表评论', 'begin' ); ?></span>
									<small><?php cancel_comment_reply_link( '' . sprintf(__( '取消回复', 'begin' )) . '' ); ?></small>
								</h3>
								<span class="comment-user-name"><a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php" title="<?php _e( '修改资料', 'begin' ); ?>" target="_blank"><?php echo $user_identity; ?></a></span>
								<span class="comment-user-alter"><a href="<?php echo wp_logout_url(get_permalink()); ?>"><?php _e( '退出登录', 'begin' ); ?></a></span>
							</div>
						</div>

					<?php elseif ( '' != $comment_author ): ?>

						<div class="comment-user-inf">
							<div class="user-avatar">
								<?php if (zm_get_option('cache_avatar')) { ?>
									<?php echo begin_avatar($comment_author_email, $size = '64');  ?>
								<?php } else { ?>
									<?php echo get_avatar($comment_author_email, $size = '64');  ?>
								<?php } ?>
							</div>
							<div class="comment-user-inc">
								<h3 id="reply-title" class="comment-reply-title">
									<span><?php _e( '发表评论', 'begin' ); ?></span>
									<small><?php cancel_comment_reply_link( '' . sprintf(__( '取消回复', 'begin' )) . '' ); ?></small>
								</h3>
								<span class="comment-user-name"><?php printf ('%s', $comment_author); ?></span>
								<span class="comment-user-alter"><a href="javascript:toggleCommentAuthorInfo();" id="toggle-comment-author-info"><?php _e( '修改信息', 'begin' ); ?></a></span>
							</div>
							<script>var changeMsg="修改信息";var closeMsg="修改完成";function toggleCommentAuthorInfo(){jQuery("#comment-author-info").slideToggle("slow",function(){if(jQuery("#comment-author-info").css("display")=="none"){jQuery("#toggle-comment-author-info").text(changeMsg)}else{jQuery("#toggle-comment-author-info").text(closeMsg)}})}jQuery(document).ready(function(){jQuery("#comment-author-info").hide()});</script>
						</div>

					<?php endif; ?>

					<p class="emoji-box"><?php get_template_part( 'inc/smiley' ); ?></p>
					<p class="comment-form-comment"><textarea id="comment" name="comment" rows="4" tabindex="1" placeholder="<?php echo stripslashes( zm_get_option('comment_hint') ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo stripslashes( zm_get_option('comment_hint') ); ?>'"></textarea></p>

					<p class="comment-tool">
					<?php if (zm_get_option('embed_img')) { ?><a class="tool-img" href='javascript:embedImage();' title="<?php _e( '插入图片', 'begin' ); ?>"><i class="icon-img"></i><i class="be be-picture"></i></a><?php } ?>
					<?php if (zm_get_option('emoji_show')) { ?><a class="emoji" href="" title="<?php _e( '插入表情', 'begin' ); ?>"><i class="be be-insertemoticon"></i></a><?php } ?>
					</p>

					<?php if ( ! $user_ID ): ?>
					<?php if (zm_get_option('not_comment_form')) { ?>
						<div id="comment-author-info" class="author-form">
					<?php } else { ?>
						<div id="comment-author-info">
					<?php } ?>
						<p class="comment-form-author">
							<label for="author"><?php _e( '昵称', 'begin' ); ?><span class="required"><?php if ($req) echo "*"; ?></span></label>
							<input type="text" name="author" id="author" class="commenttext" value="<?php echo $comment_author; ?>" tabindex="2" />
						</p>
						<?php if ( zm_get_option('no_email') == '' ) { ?>
							<p class="comment-form-email">
								<label for="email"><?php _e( '邮箱', 'begin' ); ?><span class="required"><?php if ($req) echo "*"; ?></span></label>
								<input type="text" name="email" id="email" class="commenttext" value="<?php echo $comment_author_email; ?>" tabindex="3" />
							</p>
							<?php if (zm_get_option('no_comment_url') == '' ) { ?>
								<p class="comment-form-url">
									<label for="url"><?php _e( '网址', 'begin' ); ?></label>
									<input type="text" name="url" id="url" class="commenttext" value="<?php echo $comment_author_url; ?>" tabindex="4" />
								</p>
							<?php } ?>
						<?php } ?>
						<?php if (zm_get_option('qq_info')) { ?>
							<p class="comment-form-qq">
								<label for="qq"><?php _e( 'QQ', 'begin' ); ?></label>
								<input id="qq" name="qq" type="text" value="" size="30" placeholder="输入QQ号码可以快速填写" />
								<span id="loging"></span>
							</p>
						<?php } ?>
					</div>
					<?php endif; ?>

					<div class="qaptcha"></div>

					<div class="clear"></div>
					<p class="form-submit">
						<input id="submit" name="submit" type="submit" tabindex="5" value="<?php _e( '提交', 'begin' ); ?>"/>
						<?php comment_id_fields(); do_action('comment_form', $post->ID); ?>
					</p>
				</form>

	 		<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! comments_open() ) : ?>
		<p class="no-comments"><?php _e( '评论已关闭！', 'begin' ); ?></p>
	<?php endif; ?>

	<?php if ( have_comments() ) : ?>

		<div class="comments-title wow fadeInUp ms" data-wow-delay="0.3s">
			<?php
				$my_email = get_bloginfo ( 'admin_email' );
				$str = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = $post->ID 
				AND comment_approved = '1' AND comment_type = '' AND comment_author_email";
				$count_t = $post->comment_count;
				$count_v = $wpdb->get_var("$str != '$my_email'");
				$count_h = $wpdb->get_var("$str = '$my_email'");
				echo "" . sprintf(__( '评论：', 'begin' )) . "",$count_t, " &nbsp;&nbsp;" . sprintf(__( '其中：访客', 'begin' )) . "&nbsp;&nbsp;", $count_v, " &nbsp;&nbsp;" . sprintf(__( '博主', 'begin' )) . "&nbsp;&nbsp;", $count_h, "  ";
			?>
			<?php if($numPingBacks>0) { ?>&nbsp;&nbsp;<?php _e( '引用', 'begin' ); ?>&nbsp;&nbsp;<?php echo ' '.$numPingBacks.' ';?><?php } ?>
		</div>

		<ol class="comment-list">
			<?php wp_list_comments( 'type=comment&callback=mytheme_comment' ); ?>
			<?php if($numPingBacks>0) { ?>
				<div id="trackbacks" class="ms">
					<h2 class="backs"><?php _e( '来自外部的引用：', 'begin' ); ?><?php echo ' '.$numPingBacks.'';?></h2>
					<ul class="track">
						<?php foreach ($comments as $comment) : ?>
						<?php $comment_type = get_comment_type(); ?>
						<?php if($comment_type != 'comment') { ?>
							<li><?php comment_author_link() ?></li>
						<?php } ?>
						<?php endforeach; ?>
			 		</ul>
				</div>
			<?php } ?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<?php if (zm_get_option('infinite_comment')) { ?><div class="scroll-links"><?php the_comments_navigation(); ?></div><?php } ?>
			<nav class="comment-navigation">
				<div class="pagination">
					<?php 
						if (wp_is_mobile()) {
							the_comments_pagination( array(
								'mid_size'  => 0,
								'prev_text' => '<i class="be be-arrowleft"></i>',
								'next_text' => '<i class="be be-arrowright"></i>',
								'before_page_number' => '<span class="screen-reader-text">'.sprintf(__( '第', 'begin' )).' </span>',
								'after_page_number'  => '<span class="screen-reader-text"> '.sprintf(__( '页', 'begin' )).'</span>',
							) ); 
						} else {
							the_comments_pagination( array(
								'mid_size'  => 1,
								'prev_text' => '<i class="be be-arrowleft"></i>',
								'next_text' => '<i class="be be-arrowright"></i>',
								'before_page_number' => '<span class="screen-reader-text">'.sprintf(__( '第', 'begin' )).' </span>',
								'after_page_number'  => '<span class="screen-reader-text"> '.sprintf(__( '页', 'begin' )).'</span>',
							) ); 
						}
					?>
				</div>
			</nav>
			<div class="clear"></div>
		<?php endif; // Check for comment navigation. ?>

	<?php endif; // have_comments() ?>
</div>
<!-- #comments -->