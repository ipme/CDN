<?php
// 评论回应邮件通知
function comment_mail_notify($comment_id) {
  $admin_email = get_bloginfo ('admin_email'); // $admin_email 可改指定的 e-mail.
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $to = $parent_id ? trim(get_comment($parent_id)->comment_author_email) : '';
  $spam_confirmed = $comment->comment_approved;
  if (($parent_id != '') && ($spam_confirmed != 'spam') && ($to != $admin_email)) {
    /* 上面的判断式,决定发出邮件的必要条件:
    ($parent_id != '') && ($spam_confirmed != 'spam'): 回复的, 而且不是 spam 才可发, 必需!!
    ($to != $admin_email) : 不发给 admin.
    ($comment_author_email == $admin_email) : 只有 admin 的回复才可发.
    可视个人需求修改以上条件.
    */
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); // e-mail 发出点, no-reply 可改为可用的 e-mail.
    $subject = '您在 [' . get_option("blogname") . '] 的留言有了新回复';
    $message = '<div style="background-color:#f8f8f8; border:1px solid #D2D1CB; color:#111; -moz-border-radius:8px; -webkit-border-radius:8px; -khtml-border-radius:8px; border-radius:8px; font-size:14px; width:702px; margin:0 auto; margin-top:10px;">
		<div style="background:#0e79cc; width:100%; height:60px; color:white; -moz-border-radius:6px 6px 0 0; -webkit-border-radius:6px 6px 0 0; -khtml-border-radius:6px 6px 0 0; border-radius:6px 6px 0 0; ">
			<span style="height:60px; line-height:60px; margin-left:30px; font-size:20px;"> 您在<a href="' . home_url() . '" style="text-decoration:none; color:#fff;font-weight:600;" target="_blank">' . get_option('blogname') . '</a>上的评论有新回复啦！</span>
		</div>
		<div style="width:90%; margin:0 auto">
			<br />
			<p style="font-weight:600;">' . trim(get_comment($parent_id)->comment_author) . '：</p>
			<p>您好，您曾在《' . get_the_title($comment->comment_post_ID) . '》发表了如下的评论:</p>
			<p style="background-color: #fff;border:1px solid #eaeaea;padding: 20px;margin: 15px 0; -moz-border-radius:6px 6px; -webkit-border-radius:6px 6px;; -khtml-border-radius:6px 6px; border-radius:6px 6px;">'. trim(get_comment($parent_id)->comment_content) . '</p>
			<p style="font-weight:600;">' . trim($comment->comment_author) . '：</p>
			<p>已给您做出如下的回复:</p>
			<p style="background-color: #fff;border:1px solid #eaeaea;padding: 20px;margin: 15px 0; -moz-border-radius:6px 6px; -webkit-border-radius:6px 6px;; -khtml-border-radius:6px 6px; border-radius:6px 6px;">'. trim($comment->comment_content) . '</p>
			<p>您可以点击<a href="' . htmlspecialchars(get_comment_link($parent_id, array('type' => 'comment'))) . '" style="color:#0e79cc" target="_blank">查看完整内容</a></p><br />
			<p style="font-weight:600;">温馨提示：</p>
			<p>1.本邮件由系统自动发出，请勿直接回复，谢谢!</p>
			<p>2.找博客，就上<a href="http://boke112.com" style="color:#0e79cc" target="_blank">boke112导航</a>，请认准网址：<a href="http://boke112.com" style="color:#0e79cc" target="_blank">http://boke112.com</a>!</p>
			<p>3.<a href="http://boke112.com" style="color:#0e79cc" target="_blank">boke112导航</a>宗旨是让广大博客主能够体验到博客互访的方便与快捷服务!</p><br />
		</div>
		<div style="background:#0e79cc; width:100%; height:30px; color:white; -moz-border-radius:0 0 6px 6px; -webkit-border-radius:0 0 6px 6px;; -khtml-border-radius:0 0 6px 6px; border-radius:0 0 6px 6px; ">
			<span style="height:30px; line-height:30px; margin-left:30px; font-size:14px;">以后有空请多来访，<a href="' . home_url() . '" style="text-decoration:none; color:#fff;font-weight:600;" target="_blank">' . get_option('blogname') . '</a>在此谢谢您的支持！</span>
		</div>
	</div>';
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
    //echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
  }
}
add_action('comment_post', 'comment_mail_notify');

// 自动勾选 
function add_checkbox() {
  echo '<span class="footer-tag"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" style="margin-left:0px;" /><label for="comment_mail_notify">有人回复时邮件通知我</label></span>';
}
add_action('comment_form', 'add_checkbox');
 ?>