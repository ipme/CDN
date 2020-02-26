<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function submit_form(){
if( isset($_POST['tougao_form']) && $_POST['tougao_form'] == 'send'){
	if( isset($_COOKIE["tougao"]) && ( time() - $_COOKIE["tougao"] ) < 0 ){
		wp_die('您投稿也太勤快了吧，先歇会儿！ <a href="javascript:void(0);" onclick="history.back();">点此返回</a>');
	}

	//表单变量初始化
	$name = isset( $_POST['tougao_authorname'] ) ? $_POST['tougao_authorname'] : '';
	$email = isset( $_POST['tougao_authoremail'] ) ? $_POST['tougao_authoremail'] : '';
	$blog = isset( $_POST['tougao_authorblog'] ) ? $_POST['tougao_authorblog'] : '';
	$title = isset( $_POST['tougao_title'] ) ? $_POST['tougao_title'] : '';
	$tags = isset( $_POST['tougao_tags'] ) ? $_POST['tougao_tags'] : '';
	$category = isset( $_POST['cat'] ) ? (int)$_POST['cat'] : 0;
	$content = isset( $_POST['tou-content'] ) ? $_POST['tou-content'] : '';
	$phone = isset( $_POST['tougao_authorphone'] ) ? $_POST['tougao_authorphone'] : '';
	$authorqq = isset( $_POST['tougao_authorqq'] ) ? $_POST['tougao_authorqq'] : '';
	$remarks = isset( $_POST['tougao_authorremarks'] ) ? $_POST['tougao_authorremarks'] : '';

	//表单项数据验证
	if ( empty($name) || strlen($name) > 20 ){
		echo '<div class="post tou-err-main"><p class="tou-err tou-err-w">提示：昵称必须填写，且不得超过20个字符！</p><p class="tou-err down"><a href="javascript:void(0);" onclick="history.back();">返回修改</a></p></div>';
		get_footer();die();
	}
	if ( empty($email) || strlen($email) > 60 || !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)){
		echo '<div class="post tou-err-main"><p class="tou-err tou-err-w">提示：邮箱必须填写，且不得超过60个字符，必须符合 Email 格式！</p><p class="tou-err down"><a href="javascript:void(0);" onclick="history.back();">返回修改</a></p></div>';
		get_footer();die();
	}
	if ( empty($title) || strlen($title) > 100 ){
		echo '<div class="post tou-err-main"><p class="tou-err tou-err-w">提示：文章标题必须填写，且不得超过100个字符！</p><p class="tou-err down"><a href="javascript:void(0);" onclick="history.back();">返回修改</a></p></div>';
		get_footer();die();
	}
	// 正文验证可以删除
	if ( empty($content) || strlen($content) < 10){
		echo '<div class="post tou-err-main"><p class="tou-err tou-err-w">提示：内容必须填写，且不得少于10个字符！</p><p class="tou-err down"><a href="javascript:void(0);" onclick="history.back();">返回修改</a></p></div>';
		get_footer();die();
	}

	if (zm_get_option('publish_form')) {
		$tougao = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_status' => 'publish',
			'tags_input' => $tags,
			'post_category' => array($category)
		);

		// 将文章插入数据库
		$status = wp_insert_post( $tougao );
		if ($status != 0){
			global $wpdb;
			$myposts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC");
			add_post_meta($myposts[0]->ID, 'postauthor', $name);
			// 添加字段
			if( !empty($blog))
				add_post_meta($myposts[0]->ID, 'authorurl', $blog);
			if( !empty($email))
				add_post_meta($myposts[0]->ID, 'authoremail', $email);
			if( !empty($phone))
				add_post_meta($myposts[0]->ID, 'phone', $phone);
			if( !empty($authorqq))
				add_post_meta($myposts[0]->ID, 'authorqq', $authorqq);
			if( !empty($remarks))
				add_post_meta($myposts[0]->ID, 'remarks', $remarks);

			setcookie("tougao", time(), time()+180);
			echo '<div class="post tou-err-main"><p class="tou-err tou-err-c">提交成功！</p><p class="tou-err down"><a href="javascript:void(0);" onclick="history.back();">再次提交</a></p></div>';
			get_footer();die();
		} else {
			echo '<div class="post tou-err-main"><p class="tou-err tou-err-w">提示：提交失败！</p><p class="tou-err down"><a href="javascript:void(0);" onclick="history.back();">返 回</a></p></div>';
			get_footer();die();
		}

	} else {
		$tougao = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_status' => 'pending',
			'tags_input' => $tags,
			'post_category' => array($category)
		);

		$status = wp_insert_post( $tougao );
		if ($status != 0){
			global $wpdb;
			$myposts = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_status = 'pending' AND post_type = 'post' ORDER BY post_date DESC");
			add_post_meta($myposts[0]->ID, 'postauthor', $name);
			// 添加字段
			if( !empty($blog))
				add_post_meta($myposts[0]->ID, 'authorurl', $blog);
			if( !empty($email))
				add_post_meta($myposts[0]->ID, 'authoremail', $email);
			if( !empty($phone))
				add_post_meta($myposts[0]->ID, 'phone', $phone);
			if( !empty($authorqq))
				add_post_meta($myposts[0]->ID, 'authorqq', $authorqq);
			if( !empty($remarks))
				add_post_meta($myposts[0]->ID, 'remarks', $remarks);

			setcookie("tougao", time(), time()+180);
			echo '<div class="post tou-err-main"><p class="tou-err tou-err-c">提交成功！</p><p class="tou-err down"><a href="javascript:void(0);" onclick="history.back();">再次提交</a></p></div>';
			get_footer();die();
		} else {
			echo '<div class="post tou-err-main"><p class="tou-err tou-err-w">提示：提交失败！</p><p class="tou-err down"><a href="javascript:void(0);" onclick="history.back();">返 回</a></p></div>';
			get_footer();die();
		}
	}
}
}