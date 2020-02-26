<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

function begin_get_current_page_url(){
	$ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true:false;
	$sp = strtolower($_SERVER['SERVER_PROTOCOL']);
	$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
	$port  = $_SERVER['SERVER_PORT'];
	$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
	$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
	return $protocol . '://' . $host . $port . $_SERVER['REQUEST_URI'];
}

function ajax_login_object(){
	$object = array();
	$object['redirecturl'] = begin_get_current_page_url();
	$object['ajaxurl'] = admin_url( '/admin-ajax.php' );
	$object['loadingmessage'] = sprintf(__( '请稍等', 'begin' ));
	$object_json = json_encode($object);
	return $object_json;
}

function begin_ajax_login(){
	$result = array();
	if(isset($_POST['security']) && wp_verify_nonce( $_POST['security'], 'security_nonce' ) ) {
		$creds = array();
		$creds['user_login'] = $_POST['username'];
		$creds['user_password'] = $_POST['password'];
		$creds['remember'] = ( isset( $_POST['remember'] ) ) ? $_POST['remember'] : false;
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			$login = wp_signon($creds, true);
		}else{
			$login = wp_signon($creds, false);
		}
		if ( ! is_wp_error( $login ) ) {
			$result['loggedin'] = 1;
		}else{
			$result['message'] = ( $login->errors ) ? strip_tags( $login->get_error_message() ) : '<strong>ERROR</strong>: ' . esc_html__( '请输入正确用户名和密码以登录', 'begin' );
		}
	}else{
		$result['message'] = __('失败，请重试！','begin');
	}
	header( 'content-type: application/json; charset=utf-8' );
	echo json_encode( $result );
	exit;
}
add_action( 'wp_ajax_ajaxlogin', 'begin_ajax_login' );
add_action( 'wp_ajax_nopriv_ajaxlogin', 'begin_ajax_login' );

function begin_ajax_register() {
	$result = array();
	if(isset($_POST['security']) && wp_verify_nonce( $_POST['security'], 'user_security_nonce' ) ) {
		$user_login = sanitize_user($_POST['username']);
		$user_pass = $_POST['password'];
		$user_email = apply_filters( 'user_registration_email', $_POST['email'] );
		$errors = new WP_Error();
		if( ! validate_username( $user_login ) ){
			$errors->add( 'invalid_username', __( '请输入一个有效用户名','begin' ) );
		}elseif(username_exists( $user_login )){
			$errors->add( 'username_exists', __( '此用户名已被注册','begin' ) );
		}elseif(email_exists( $user_email )){
			$errors->add( 'email_exists', __( '此邮箱已被注册','begin' ) );
		}
		do_action( 'register_post', $user_login, $user_email, $errors );
		$errors = apply_filters( 'registration_errors', $errors, $user_login, $user_email );
		if ( $errors->get_error_code() ) {
			$result['success'] = 0;
			$result['message'] = $errors->get_error_message();

		} else {
			$user_id = wp_create_user( $user_login, $user_pass, $user_email );
			if ( ! $user_id ) {
				$errors->add( 'registerfail', sprintf( __( '无法注册，请联系管理员','begin' ), get_option( 'admin_email' ) ) );
				$result['success'] = 0;
				$result['message'] = $errors->get_error_message();
			} else{
				update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
				wp_new_user_notification( $user_id, $user_pass );
				$result['success'] = 1;
				$result['message'] = esc_html__( '注册成功','begin' );
				wp_set_current_user($user_id);
				wp_set_auth_cookie($user_id);
				$result['loggedin'] = 1;
			}
		}
	} else {
		$result['message'] = __('失败，请重试！','begin');
	}
	header( 'content-type: application/json; charset=utf-8' );
	echo json_encode( $result );
	exit;
}
add_action( 'wp_ajax_ajaxregister', 'begin_ajax_register' );
add_action( 'wp_ajax_nopriv_ajaxregister', 'begin_ajax_register' );

function add_login_footer() {
	$ajax = ajax_login_object();
	print  <<<END
	<script type="text/javascript">
		/* <![CDATA[ */
		var ajax_login_object = $ajax;
		/* ]]> */
	</script>
END;
}
add_action('wp_footer','add_login_footer');