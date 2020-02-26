<?php
/*
Template Name: 用户注册
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<?php
	if( !empty($_POST['user_reg']) ) {
		$error = '';
		$sanitized_user_login = sanitize_user( $_POST['user_login'] );
		$user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );

  // 检查名称
	if ( $sanitized_user_login == '' ) {
		$error .= '<i class="be be-info"></i>' . (__( '请输入用户名！', 'begin' )) . '<br />';
	} elseif ( ! validate_username( $sanitized_user_login ) ) {
		$error .= '<i class="be be-info"></i>' . (__( '此用户名包含无效字符，请输入有效的用户名！', 'begin' )) . '<br />';
		$sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
		$error .= '<i class="be be-info"></i>' . (__( '该用户名已被注册，请再选择一个！', 'begin' )) . '<br />';
	}

  // 检查邮件
	if ( $user_email == '' ) {
		$error .= '<i class="be be-info"></i>' . (__( '请填写电子邮件地址！', 'begin' )) . '<br />';
	} elseif ( ! is_email( $user_email ) ) {
		$error .= '<i class="be be-info"></i>' . (__( '电子邮件地址不正确！', 'begin' )) . '<br />';
		$user_email = '';
	} elseif ( email_exists( $user_email ) ) {
		$error .= '<i class="be be-info"></i>' . (__( '该电子邮件地址已经被注册，请换一个！', 'begin' )) . '<br />';
	}
	if (zm_get_option('invitation_code')) {
		//检测邀请码
		$begin_codes_options = get_option( 'begin_codes_options' );
		$invitation_code = isset( $_POST['invitation_code'] ) ? strtoupper( $_POST['invitation_code'] ) : '';
		if ( ! array_key_exists( $invitation_code, $begin_codes_options['codes'] ) ) {
			add_action( 'wp_head', 'wp_shake_js', 12 );
			$error .= '<i class="be be-info"></i>' . (__( '邀请码错误！', 'begin' )) . '<br />';
		} elseif ( isset( $begin_codes_options['codes'][ $invitation_code ] ) && ! $begin_codes_options['codes'][ $invitation_code ]['leftcount'] ){
			add_action( 'wp_head', 'wp_shake_js', 12 );
			$error .='<i class="be be-info"></i>' . (__( '此邀请码已被使用！', 'begin' )) . '<br />';
		} else {
			$begin_codes_options['codes'][ $invitation_code ]['leftcount']--;
			// $begin_codes_options['codes'][ $invitation_code ]['users'][] = $sanitized_user_login;
			update_option( 'begin_codes_options', $begin_codes_options );
		}
	}

	// 检查密码
	if(strlen($_POST['user_pass']) < 6)
		$error .= '<i class="be be-info"></i>' . (__( '密码长度至少6位!', 'begin' )) . '<br />';
		elseif($_POST['user_pass'] != $_POST['user_pass2'])
		$error .= '<i class="be be-info"></i>' . (__( '密码不一致!', 'begin' )) . '<br />';

	if($error == '') {
			$user_id = wp_create_user( $sanitized_user_login, $_POST['user_pass'], $user_email );
		if ( ! $user_id ) {
			$error .= sprintf( '<i class="be be-info"></i>无法完成您的注册请求... 请联系<a href=\"mailto:%s\">管理员</a>！<br />', get_option( 'admin_email' ) );
		}
		else if (!is_user_logged_in()) {
			$user = get_userdatabylogin($sanitized_user_login);
			$user_id = $user->ID;

	      // 自动登录
			wp_set_current_user( $user_id, $user->user_login );
			wp_set_auth_cookie( $user_id );
			do_action( 'wp_login', $user->user_login );
		}
	}
}
?>
<?php get_header(); ?>
<?php $imgurl=zm_get_option('reg_img');echo'<style type="text/css">body.custom-background{background: url('.$imgurl.') no-repeat;background-position: center center;background-size: cover;width: 100%;background-attachment: fixed;}body{background: url('.$imgurl.') no-repeat;background-position: center center;background-size: cover;width: 100%;background-attachment: fixed;}</style>'?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/register.css" />

<div id="primary" class="content-reg">
	<main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( !get_option('users_can_register') )  { ?>
				<p class="reg-error"><i class="be be-info"></i> 提示：进入后台→设置→常规→常规选项页面，勾选“任何人都可以注册”！</p>
			<?php } else { ?>
			<div class="reg-main">
				<div class="reg-page">
					<div class="reg-content">
						<?php if(!empty($error)) {
							echo '<p class="user_error">'.$error.'</p>';
							}
						if (!is_user_logged_in()) { ?>
							<form name="registerform" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="user_reg">
								<p>
									<label for="user_login"><?php _e( '用户名', 'begin' ); ?> *<br />
										<input type="text" name="user_login" id="user_login" class="input" value="<?php if(!empty($sanitized_user_login)) echo $sanitized_user_login; ?>" size="30" required="required" />
							      </label>
								</p>

								<p>
									<label for="user_email"><?php _e( '电子邮件地址', 'begin' ); ?> *<br />
										<input type="text" name="user_email" id="user_email" class="input" value="<?php if(!empty($user_email)) echo $user_email; ?>" size="30" required="required" />
									</label>
							    </p>

								<?php if ( zm_get_option('go_reg') == '' ) { ?>
									<p>
										<label for="user_pwd1"><?php _e( '密码(至少6位)', 'begin' ); ?> *<br />
											<input id="user_pwd1" class="input" type="password" size="30" value="" name="user_pass" required="required" />
										</label>
									</p>

									<p>
										<label for="user_pwd2"><?php _e( '重复密码', 'begin' ); ?> *<br />
											<input id="user_pwd2" class="input" type="password" size="30" value="" name="user_pass2" required="required" />
										</label>
									</p>
								<?php } ?>

								<?php do_action('register_form'); ?>

								<p class="submit">
									<input type="hidden" name="user_reg" value="ok" />
									<input id="submit" name="submit" type="submit" value="<?php _e( '提交注册', 'begin' ); ?>"/>
								</p>
							</form>

						<?php } else { ?>
							<p class="user_is">
								<?php wp_redirect( home_url() );?>
							</p>
						<?php } ?>
						<div class="clear"></div>
					</div>
				</div>

				<div class="entry-content">
					<div class="single-content">
						<?php the_content(); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</article>
		<?php } ?>
	<?php endwhile; ?>
	</main>
</div>

<?php get_footer(); ?>