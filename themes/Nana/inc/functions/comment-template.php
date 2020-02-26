<?php
function mytheme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li class="nana"';
		$add_below = 'div-comment';
	}
// 楼层	
	$comorder =  get_option('comment_order');
	if($comorder == 'asc'){
		//在页面顶部显示 旧的 评论
		global $commentcount;
		if(!$commentcount) {
			if ( get_query_var('cpage') > 0 )
				$page = get_query_var('cpage')-1;
			else $page = get_query_var('cpage');
				$cpp=get_option('comments_per_page');
				$commentcount = $cpp * $page;
		}
	}else{
		//在页面顶部显示 新的 评论
		global $commentcount,$wpdb, $post;
		if(!$commentcount) { //初始化楼层计数器
			$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = '' AND comment_approved = '1' AND !comment_parent");
			$cnt = count($comments);//获取主评论总数量
			$page = get_query_var('cpage');//获取当前评论列表页码
			$cpp=get_option('comments_per_page');//获取每页评论显示数量
			if (ceil($cnt/$cpp) == 1 || ($page>1 && $page == ceil($cnt/$cpp))) {
				$commentcount = $cnt + 1;//如果评论只有1页或者是最后一页，初始值为主评论总数
			} else {
				$commentcount = $cpp * $page + 1;
			}
		}
	}
?>
	<div id="anchor"><div id="comment-<?php comment_ID() ?>"></div></div>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<?php echo my_avatar( $comment->comment_author_email,50,$default='',$comment->comment_author); ?>
	<div class="comment-author">
	<?php if( $comment->comment_parent > 0) { ?>
	<strong><span class="duzhe"><?php commentauthor(); ?></span></strong><span class="reply_tz"><?php if (!get_option('ygj_pldj') ) { ?><?php get_author_class($comment->comment_author_email,$comment->user_id); ?><?php if(user_can($comment->user_id, 'administrator')){echo '<span class="dengji">【';echo stripslashes(get_option('ygj_adminch')); echo '】</span>';}?><?php } ?><?php printf( __('%1$s at %2$s', 'Nana'), get_comment_date( 'Y-m-d' ),  get_comment_time('H:i') ); ?><?php comment_reply_link(array_merge( $args, array('reply_text' => '&nbsp;<i class="fa fa-reply"></i>&nbsp;回复', 'add_below' =>$add_below, 'depth' => $depth, 'max_depth' => 10000))); ?></span><br/>
	<?php } else { ?>
		<strong><span class="duzhe"><?php commentauthor(); ?></span><?php if (!get_option('ygj_pldj') ) { ?><?php get_author_class($comment->comment_author_email,$comment->user_id); ?><?php if(user_can($comment->user_id, 'administrator')){echo '<span class="dengji">【';echo stripslashes(get_option('ygj_adminch')); echo '】</span>';}?><?php } ?></strong><span class="reply_t"><?php comment_reply_link(array_merge( $args, array('reply_text' => '&nbsp;@回复', 'add_below' =>$add_below, 'depth' => $depth, 'max_depth' => 10000))); ?></span><br/>
		<span class="comment-meta commentmetadata">
			<span class="comment-aux">
				<?php echo '<span class="xiaoshi">发表于</span>';printf( __('%1$s at %2$s', 'Nana'), get_comment_date( 'Y-m-d' ),  get_comment_time('H:i') ); ?>
				<?php
					if ( is_user_logged_in() ) {
						$url = home_url() ;
						echo '<a id="delete-'. $comment->comment_ID .'" href="' . wp_nonce_url("$url/wp-admin/comment.php?action=deletecomment&amp;p=" . $comment->comment_post_ID . '&amp;c=' . $comment->comment_ID, 'delete-comment_' . $comment->comment_ID) . '" >&nbsp;删除</a>';
					}
				?>
				<?php edit_comment_link( '编辑' , '&nbsp;', '' ); ?>
				<?php
                    if($comorder == 'asc'){
						//在页面顶部显示 旧的 评论
		                if(!$parent_id = $comment->comment_parent){
							 switch ($commentcount){
								case 0 :echo "&nbsp;<span class='pinglunqs plshafa'>沙发</span>";++$commentcount;break;
								case 1 :echo "&nbsp;<span class='pinglunqs plbandeng'>板凳</span>";++$commentcount;break;
								case 2 :echo "&nbsp;<span class='pinglunqs pldiban'>地板</span>";++$commentcount;break;
								default:printf('<span class="floor">&nbsp;%1$s楼</span>', ++$commentcount);
							}
							
						}
					}else{
						//在页面顶部显示 新的 评论
						if(!$parent_id = $comment->comment_parent){
							 switch ($commentcount){
							    case 2 :echo "&nbsp;<span class='pinglunqs plshafa'>沙发</span>";--$commentcount;break;
								case 3 :echo "&nbsp;<span class='pinglunqs plbandeng'>板凳</span>";--$commentcount;break;
								case 4 :echo "&nbsp;<span class='pinglunqs pldiban'>地板</span>";--$commentcount;break;
								default:printf('<span class="floor">&nbsp;%1$s楼</span>', --$commentcount);
							}
							
						}
					}
					?>
			</span>
		</span>
	<?php } ?>
	</div>
		<?php comment_text(); ?>

	<?php if ( $comment->comment_approved == '0' ) : ?>
		<div class="comment-awaiting-moderation">您的评论正在等待审核！</div>
	<?php endif; ?>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}