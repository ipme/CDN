<div class="m-personal">
	<div class="personal-bg">
		<img src="<?php echo zm_get_option('personal_img'); ?>">
	</div>

	<p><?php _e( '今天是：', 'begin' ); ?>
	<script type="text/javascript">
	var d, s = "";
	var x = new Array("星期日", "星期一", "星期二","星期三","星期四", "星期五","星期六");
	d = new Date();
	s+=d.getFullYear()+"年"+(d.getMonth() + 1)+"月"+d.getDate()+"日 ";
	s+=x[d.getDay()];
	document.writeln(s);
	</script>
	</p>
	<div class="m-logout"><a href="<?php echo wp_logout_url('index.php'); ?>"><?php _e( '登出', 'begin' ); ?></a></div>
	<div class="my-avatar">
		<?php global $current_user; wp_get_current_user();
			echo '<div class="m-img">';
			if (zm_get_option('cache_avatar')):
				echo begin_avatar( $current_user->user_email, 64); 
			else :
				echo get_avatar( $current_user->user_email, 64); 
			endif;

			echo '</div>';
			echo '<div class="m-name"><span>' . __('欢迎回来！', 'begin' ) . '</strong>' . '</span><br />';
			echo '' . $current_user->display_name . "\n";
			echo '</div>';
		?>
	</div>
	<div class="clear"></div>
</div>


<div class="my-info">
	<?php global $current_user;
	wp_get_current_user();
	echo '<ul><li>';
	echo '<strong>' . __('用户名', 'begin' ) . '</strong>' . $current_user->user_login . "\n";
	echo '</li><li>';;
	echo '<strong>' . __('呢称', 'begin' ) . '</strong>' . $current_user->nickname . "\n";
	echo '</li><li>';
	echo '<strong>' . __('角色', 'begin' ) . '</strong>' . get_user_role() . "\n";
	echo '</li><li>';
	echo '<strong>' . __('邮箱', 'begin' ) . '</strong>' . $current_user->user_email . "\n";
	echo '</li></ul>';
	?>
	<?php
		global $wpdb;
		$author_id = $current_user->ID;
		$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
	?>
	<ul>
		<li><strong><?php _e( '我的评论', 'begin' ); ?></strong><?php echo $comment_count; ?></li>
		<li><strong><?php _e( '我的文章', 'begin' ); ?></strong><?php $userinfo=get_userdata(get_current_user_id()); $authorID= $userinfo->ID; echo num_of_author_posts($authorID); ?></li>
		<li><strong><?php _e( '注册时间', 'begin' ); ?></strong><?php user_registered(); ?></li>
		<li><strong><?php _e( '最后登录', 'begin' ); ?></strong><?php global $userdata; wp_get_current_user(); get_last_login($userdata->ID); ?></li>
		<li><strong><?php _e( '站点', 'begin' ); ?></strong><?php global $userdata; wp_get_current_user();echo esc_attr( $userdata->user_url ); ?></li>
	</ul>
</div>