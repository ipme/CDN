<?php
// jQuery-Ajax-Comments v1.3 by Willin Kan.

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

// Sets up the WordPress Environment
require( dirname(__FILE__) . '/../../../../wp-load.php' ); // 此 comments-ajax.php 位于主题数据夹,所以位置已不同

nocache_headers();

$comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;

$post = get_post($comment_post_ID);

if ( empty($post->comment_status) ) {
	do_action('comment_id_not_found', $comment_post_ID);
	err('Invalid comment status.'); // 将 exit 改为错误提示
}

// get_post_status() will get the parent status for attachments.
$status = get_post_status($post);

$status_obj = get_post_status_object($status);

if ( !comments_open($comment_post_ID) ) {
	do_action('comment_closed', $comment_post_ID);
	err('Sorry, comments are closed for this item.'); // 将 wp_die 改为错误提示
} elseif ( 'trash' == $status ) {
	do_action('comment_on_trash', $comment_post_ID);
	err('Invalid comment status.'); // 将 exit 改为错误提示
} elseif ( !$status_obj->public && !$status_obj->private ) {
	do_action('comment_on_draft', $comment_post_ID);
	err('Invalid comment status.'); // 将 exit 改为错误提示
} elseif ( post_password_required($comment_post_ID) ) {
	do_action('comment_on_password_protected', $comment_post_ID);
	err('Password Protected'); // 将 exit 改为错误提示
} else {
	do_action('pre_comment_on_post', $comment_post_ID);
}

$comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
$comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
$comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
$edit_id              = ( isset($_POST['edit_id']) ) ? $_POST['edit_id'] : null; // 提取 edit_id

// If the user is logged in
$user = wp_get_current_user();
if ( $user->ID ) {
	if ( empty( $user->display_name ) )
		$user->display_name=$user->user_login;
	$comment_author       = $wpdb->escape($user->display_name);
	$comment_author_email = $wpdb->escape($user->user_email);
	$comment_author_url   = $wpdb->escape($user->user_url);
	if ( current_user_can('unfiltered_html') ) {
		if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
			kses_remove_filters(); // start with a clean slate
			kses_init_filters(); // set up the filters
		}
	}
} else {
	if ( get_option('comment_registration') || 'private' == $status )
		err('<i class="be be-info"></i>对不起，您必须登录后才能发表评论。');
}

$comment_type = '';
if ( zm_get_option('no_email') ) {
	if ( '' == $comment_author )
		err('<i class="be be-info"></i>' . sprintf(__( '提示：必须填写昵称。', 'begin' )) . '');
} else {
	if ( get_option('require_name_email') && !$user->ID ) {
		if ( 6 > strlen($comment_author_email) || '' == $comment_author )
			err('<i class="be be-info"></i>' . sprintf(__( '提示：必须填写昵称及邮件。', 'begin' )) . ''); 
		elseif ( !is_email($comment_author_email))
			err('<i class="be be-info"></i>' . sprintf(__( '提示：请输入一个有效的电子邮件地址。', 'begin' )) . '');
	}
}

if ( '' == $comment_content )
	err('<i class="be be-info"></i>' . sprintf(__( '提示：请输入评论内容。', 'begin' )) . '');

// 增加: 错误提示功能
function err($ErrMsg) {
	header('HTTP/1.1 405 Method Not Allowed');
	echo $ErrMsg;
	exit;
}

// 检查重复评论功能
$dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
$dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
if ( $wpdb->get_var($dupe) ) {
	err('<i class="be be-info"></i>' . sprintf(__( '貌似您已发表过重复的评论!', 'begin' )) . '');
}

// 检查评论太快功能
if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) { 
	$time_lastcomment = mysql2date('U', $lasttime, false);
	$time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
	$flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
	if ( $flood_die ) {
		err('<i class="be be-info"></i>' . sprintf(__( '您发表评论也太快了!', 'begin' )) . '');
	}
}

$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;

$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');


// 检查评论是否正被编辑, 更新或新建评论
function ihacklog_user_can_edit_comment($new_cmt_data,$comment_ID = 0) {
	if(current_user_can('edit_comment', $comment_ID)) {
		return true;
	}
	$comment = get_comment( $comment_ID );
	$old_timestamp = strtotime( $comment->comment_date);
	$new_timestamp = current_time('timestamp');
	// 不用get_comment_author_email($comment_ID) , get_comment_author_IP($comment_ID)
	$rs = $comment->comment_author_email === $new_cmt_data['comment_author_email']
		&& $comment->comment_author_IP === $_SERVER['REMOTE_ADDR']
		&& $new_timestamp - $old_timestamp < 3600;
	return $rs;
}

if ( $edit_id ) {
	$comment_id = $commentdata['comment_ID'] = $edit_id;
	if( ihacklog_user_can_edit_comment($commentdata,$comment_id) ){
		wp_update_comment( $commentdata );
	} else {
		err( '<i class="be be-info"></i>您没有更改此评论的权限!' );
	}
}else{
	$comment_id = wp_new_comment( $commentdata );
}

$comment = get_comment($comment_id);
if ( !$user->ID ) {
	$comment_cookie_lifetime = apply_filters('comment_cookie_lifetime', 30000000);
	setcookie('comment_author_' . COOKIEHASH, $comment->comment_author, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_url_' . COOKIEHASH, esc_url($comment->comment_author_url), time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
}

//$location = empty($_POST['redirect_to']) ? get_comment_link($comment_id) : $_POST['redirect_to'] . '#comment-' . $comment_id; //取消原有的刷新重定向
//$location = apply_filters('comment_post_redirect', $location, $comment);

//wp_redirect($location);

$comment_depth = 1;   //为评论的 class 属性准备的
$tmp_c = $comment;
while($tmp_c->comment_parent != 0){
$comment_depth++;
$tmp_c = get_comment($tmp_c->comment_parent);
}

//以下是评论式样, 不含 "回复". 
?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<div class="comment-author vcard">
		<?php if (zm_get_option('cache_avatar')) { ?>
			<?php echo begin_avatar( $comment->comment_author_email, 64 ); ?>
		<?php } else { ?>
			<?php echo get_avatar( $comment, 64 ); ?>
		<?php } ?>
		<?php printf('<cite class="fn">%s</cite> <span class="says"></span>', get_comment_author_link() ); ?>
		
		<span class="comment-meta commentmetadata">
			<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><br />
				<?php printf('%1$s %2$s', get_comment_date(),  get_comment_time() ); ?>
			</a>
		</span>
	</div>
	<?php comment_text(); ?>
	<?php if ( $comment->comment_approved == '0' ) : ?>
		<div class="comment-awaiting-moderation"><i class="be be-info"></i><?php _e( '您的评论正在等待审核！', 'begin' ); ?></div>
	<?php endif; ?>