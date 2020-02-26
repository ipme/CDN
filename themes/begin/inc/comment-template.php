<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function mytheme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
	if (zm_get_option('comment_floor')) { 
		$comorder = get_option('comment_order');
		if($comorder == 'asc'){
			global $commentcount;
			if(!$commentcount) {
				if ( get_query_var('cpage') > 0 )
				$page = get_query_var('cpage')-1;
				else $page = get_query_var('cpage');
				$cpp = get_option('comments_per_page');
				$commentcount = $cpp * intval($page);
			}
		} else {
			global $commentcount,$wpdb, $post;
			if(!$commentcount) {
				$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = '' AND comment_approved = '1' AND !comment_parent");
				$cnt = count($comments);
				$page = get_query_var('cpage');
				$cpp = get_option('comments_per_page');
				if (ceil($cnt/$cpp) == 1 || ($page> 1 && $page == ceil($cnt/$cpp))) {
					$commentcount = $cnt + 1;
				} else {
					$commentcount = intval($cpp) * intval($page) + 1;
				}
			}
		}
	}
?>

	<li class="comments-anchor"><ul id="anchor-comment-<?php comment_ID() ?>"></ul></li>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? 'wow fadeInUp ms' : 'parent ms' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
		<?php if (zm_get_option('cache_avatar')) { ?>
			<?php if (zm_get_option('lazy_c')) { ?>
				<?php echo '<img class="avatar" src="' . get_template_directory_uri() . '/img/load-avatar.gif" alt="avatar" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), begin_avatar( $comment->comment_author_email, '64','', get_comment_author())) . '" />'; ?>
			<?php } else { ?>
				<?php echo begin_avatar( $comment->comment_author_email, 64, '', get_comment_author() ); ?>
			<?php } ?>
		<?php } else { ?>
			<?php if (zm_get_option('lazy_c')) { ?>
				<?php echo '<img class="avatar" src="' . get_template_directory_uri() . '/img/load-avatar.gif" alt="avatar" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $comment, '64','', get_comment_author())) . '" />'; ?>
			<?php } else { ?>
				<?php echo get_avatar( $comment, 64, '', get_comment_author() ); ?>
			<?php } ?>
		<?php } ?>

		<strong><?php comment_author_link(); ?></strong>
		<?php get_author_admin($comment->comment_author_email, $comment->user_id); ?>
		<span class="reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => '@' . sprintf(__( '回复', 'begin' )) . '', 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
		<?php if (zm_get_option('vip')) { ?><?php get_author_class($comment->comment_author_email, $comment->user_id); ?><?php if(user_can($comment->user_id, 1)); ?><?php } ?>
		<span class="comment-meta commentmetadata">
			<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"></a><br />
			<span class="comment-aux">
				<?php printf('%1$s %2$s', get_comment_date(),  get_comment_time() ); ?>
				<?php if (zm_get_option('del_comment')) { ?>
				<?php
					if ( current_user_can('level_10') ) {
						$url = home_url();
						echo '<a id="delete-'. $comment->comment_ID .'" href="' . wp_nonce_url("$url/wp-admin/comment.php?action=deletecomment&p=" . $comment->comment_post_ID . '&c=' . $comment->comment_ID, 'delete-comment_' . $comment->comment_ID) . '" class="comment-delete">' . sprintf(__( '删除', 'begin' )) . '</a>';
					}
				?>
				<?php } ?>
				<?php edit_comment_link( '<i class="be be-editor"></i>' , '&nbsp;', '' ); ?>
				<?php if (zm_get_option('comment_floor')) { ?>
					<span class="floor">
						<?php
							if($comorder == 'asc'){
								if(!$parent_id = $comment->comment_parent){
									switch ($commentcount){
										case 0 :echo "&nbsp;<span class='floor-c floor-s'>" . sprintf(__( '沙发', 'begin' )) . "</span>";++$commentcount;break;
										case 1 :echo "&nbsp;<span class='floor-c floor-b'>" . sprintf(__( '板凳', 'begin' )) . "</span>";++$commentcount;break;
										case 2 :echo "&nbsp;<span class='floor-c floor-d'>" . sprintf(__( '地板', 'begin' )) . "</span>";++$commentcount;break;
										default :printf('&nbsp;<span class="floor-c floor-l">%1$s' . sprintf(__( '楼', 'begin' )) . '</span>', ++$commentcount);
									}
								}
							} else {
								if(!$parent_id = $comment->comment_parent){
									switch ($commentcount){
										case 2 :echo "&nbsp;<span class='floor-c floor-s'>" . sprintf(__( '沙发', 'begin' )) . "</span>";--$commentcount;break;
										case 3 :echo "&nbsp;<span class='floor-c floor-b'>" . sprintf(__( '板凳', 'begin' )) . "</span>";--$commentcount;break;
										case 4 :echo "&nbsp;<span class='floor-c floor-d'>" . sprintf(__( '地板', 'begin' )) . "</span>";--$commentcount;break;
										default :printf('&nbsp;<span class="floor-c floor-l">%1$s' . sprintf(__( '楼', 'begin' )) . '</span>', --$commentcount);
									}
								}
							}
						?>
						<?php if( $depth > 1){printf('&nbsp;<span class="floor-c floor-l">%1$s' . sprintf(__( '层', 'begin' )) . '</span>', $depth-1);} ?>
					</span>
				<?php } ?>
			</span>
		</span>
	</div>
	<?php comment_text(); ?>
	<?php if ( $comment->comment_approved == '0' ) : ?>
		<div class="comment-awaiting-moderation"><?php _e( '您的评论正在等待审核！', 'begin' ); ?></div>
	<?php endif; ?>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}