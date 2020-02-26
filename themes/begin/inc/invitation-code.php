<?php
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );
if ( ! is_admin() ) {
	// 前端
	add_action( 'register_form', 'begin_register_form_add_field' );
	function begin_register_form_add_field() { 
		global $allowedposttags;
		$begin_fields = get_option( 'begin_fields' );
	?>
		<p>
			<label for="invitation_code"><?php _e( '邀请码', 'begin' ); ?> *<br />
			<input name="invitation_code" type="text" class="input" id="invitation_code" style="text-transform: uppercase" required="required" />
			</label>
			<?php if ( ! empty( $begin_fields['link'] ) && $begin_fields['link'] == 'on' ) { ?>
			<span class="to-code"><a href="<?php echo ! empty( $begin_fields['text_link'] ) ? wp_kses_post( $begin_fields['text_link'], $allowedposttags ) : ''; ?>" target="_blank"><?php _e( '获取邀请码', 'begin' ); ?></a></span>
			<?php } ?>
		</p>
	 <?php
	}

	add_filter( 'registration_errors', 'begin_registration_errors', 20, 3 ); 
	function begin_registration_errors( $errors, $sanitized_user_login, $user_email ) {
		if( count( $errors->errors ) ) {
			return $errors;
		}
		$begin_codes_options = get_option( 'begin_codes_options' );

		$invitation_code = isset( $_POST['invitation_code'] ) ? strtoupper( $_POST['invitation_code'] ) : '';
		if ( ! array_key_exists( $invitation_code, $begin_codes_options['codes'] ) ) {
			add_action( 'login_head', 'wp_shake_js', 12 );
			return new WP_Error( 'authentication_failed', __( '<strong>错误</strong>: 请输入正确的邀请码！', 'begin' ) );
		} elseif ( isset( $begin_codes_options['codes'][ $invitation_code ] ) && ! $begin_codes_options['codes'][ $invitation_code ]['leftcount'] ){
			add_action( 'login_head', 'wp_shake_js', 12 );
			return new WP_Error( 'authentication_failed', __( '<strong>错误</strong>: 此邀请码已被使用！', 'begin' ) );
		} else {
			$begin_codes_options['codes'][ $invitation_code ]['leftcount']--;
			// begin_codes_options['codes'][ $invitation_code ]['users'][] = $sanitized_user_login;
			update_option( 'begin_codes_options', $begin_codes_options );
		}
		return $errors;
	}

	add_action( 'login_footer', 'begin_login_footer' );
	function begin_login_footer() {
		$begin_codes_options = get_option( 'begin_codes_options' );

		$invitation_code = isset( $_POST['invitation_code'] ) ? strtoupper( $_POST['invitation_code'] ) : '';
		if ( $invitation_code && ! array_key_exists( $invitation_code, $begin_codes_options['codes'] ) ):
			?>
			<script type="text/javascript">
				try{document.getElementById('invitation_code').focus();}catch(e){}
			</script>
			<?php 
		endif;
	}
} else {
	define( 'begin__FILE__', __FILE__ );

	// 表单文字
	function begin_field_link() {
		$begin_fields = get_option( 'begin_fields' );
	?>
		<label><input type="checkbox" class="checkbox" name="begin_fields[link]" <?php checked( $begin_fields['link'], 'on' ); ?>/> 在注册页面表单添加获取邀请码链接。</label>
	<?php
	}

	function begin_field_text_link() {
		$begin_fields = get_option( 'begin_fields' );
	?>
		<label><input type="text" size="60" name="begin_fields[text_link]" value="<?php echo !empty( $begin_fields['text_link'] ) ? esc_attr( $begin_fields['text_link'] ) : ''; ?>"/></label>
	<?php
	}

	function begin_field_count() {
	?>
		<input type="number" size="3" min="1" name="begin_field_count" value="1" /> 同一邀请码可以几个用户使用？
	<?php
	}

	function begin_field_length() {
	?>
		<input type="number" size="10" min="4" max="16" name="begin_field_length" value="8" /> 验证长度（最短4位,最长16位）
	<?php
	}

	function begin_field_howmany() {
	?>
		<input type="number" size="3" min="1" max="10" name="begin_field_howmany" value="5" /> 你要生成几个邀请码？
	<?php
	}

	function begin_field_code() {
	?>
		<input type="text" name="begin_field_code" size="40" value="" style="text-transform: uppercase;" /> 请用字母或者数字,禁止用其他字符
	<?php
	}

	function begin_field_prefix() {
	?>
		<input type="text" name="begin_field_prefix" size="10" value="" style="text-transform: uppercase;" /> 添加生成验证码的前缀
	<?php
	}

	// 设置
	add_filter( 'plugin_action_links_' . plugin_basename( begin__FILE__ ), 'begin_codes_settings_action_links', 10, 2 );
	function begin_codes_settings_action_links( $links, $file ) {
		array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=begin_rand_code' ) . '">添加邀请码</a>' );
		return $links;
	}

	add_action( 'admin_menu', 'begin_admin_menu' );
	function begin_admin_menu() {
		// All my pages
		add_menu_page( '邀请码', '邀请码', 'manage_options', 'begin_list_codes', 'begin_list_codes', 'dashicons-id', 24 );
		add_submenu_page( 'begin_list_codes', '生成邀请码', '生成邀请码', 'manage_options', 'begin_rand_code', 'begin_rand_code' );
		add_submenu_page( 'begin_list_codes', '全部邀请码', '全部邀请码', 'manage_options', 'begin_raw_codes', 'begin_raw_codes' );
		add_submenu_page( 'begin_list_codes', '邀请码选项', '邀请码选项', 'manage_options', 'begin_codes_settings', 'begin_codes_settings_page' );
		// and registered settings
		register_setting( 'begin_rand_code', 'begin_field_prefix', 'begin_fields_cb2' );
		register_setting( 'begin_codes_settings', 'begin_fields' );
	}

	function begin_raw_codes() {
		$begin_codes_options = get_option( 'begin_codes_options' );
	?>
		<div class="wrap">
			<h1><?php _e( '全部邀请码', 'begin' ); ?>
				<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=begin_list_codes' ); ?>">全部邀请码</a>
				<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=begin_rand_code' ); ?>">生成邀请码</a>
			</h1>
			<h3><?php _e( '全部邀请码', 'begin' ); ?></h3>
			<?php
			// foreach ( $begin_codes_options['codes'] as $code => $val ) {
				//if ( ! $val['leftcount'] ) {
					//unset( $begin_codes_options['codes'][ $code ] );
				//}
			//}
			$codes = ! empty( $begin_codes_options['codes'] ) ? implode( "\n", array_keys( $begin_codes_options['codes'] ) ) : __( '-- 没有邀请码! --', 'begin' );
			?>
			<textarea cols="40" rows="10"><?php echo esc_textarea( $codes ); ?></textarea>
			<p class="description"><?php _e ( '提示：将邀请码发送给朋友，邀请他们在你的博客注册。', 'begin' ); ?></p>
		</div>
	<?php
	}

	function begin_codes_settings_page() {
		settings_errors();
		add_settings_section( 'begin_codes_settings', __( '选项', 'begin' ), '__return_false', 'begin_codes_settings' );
		add_settings_field( 'begin_field_code', __( '添加链接', 'begin' ), 'begin_field_link', 'begin_codes_settings', 'begin_codes_settings' );
		add_settings_field( 'begin_field_count', __( '链接地址', 'begin' ), 'begin_field_text_link', 'begin_codes_settings', 'begin_codes_settings' );
	?>
		<div class="wrap">
			<h1><?php _e( '邀请码设置', 'begin' ); ?>
				<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=begin_list_codes' ); ?>">全部邀请码</a>
				<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=begin_rand_code' ); ?>">生成邀请码</a>
			</h1>

			<form action="options.php" method="post">
				<?php settings_fields( 'begin_codes_settings' ); ?>
				<?php do_settings_sections( 'begin_codes_settings' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
	}

	function begin_rand_code() {
		settings_errors();
		add_settings_section( 'begin_rand_code', __( '生成邀请码', 'begin' ), '__return_false', 'begin_rand_code' );
		add_settings_field( 'begin_field_prefix', __( '邀请码前缀', 'begin' ), 'begin_field_prefix', 'begin_rand_code', 'begin_rand_code' );
		add_settings_field( 'begin_field_length', __( '长度', 'begin' ), 'begin_field_length', 'begin_rand_code', 'begin_rand_code' );
		add_settings_field( 'begin_field_howmany', __( '邀请码数量', 'begin' ), 'begin_field_howmany', 'begin_rand_code', 'begin_rand_code' );
		add_settings_field( 'begin_field_count', __( '最大数', 'begin' ), 'begin_field_count', 'begin_rand_code', 'begin_rand_code' );
	?>
		<div class="wrap">
			<h1><?php _e( '生成邀请码', 'begin' ); ?>
				<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=begin_list_codes' ); ?>">全部邀请码</a>
			</h1>

			<form action="options.php" method="post">
				<?php settings_fields( 'begin_rand_code' ); ?>
				<?php do_settings_sections( 'begin_rand_code' ); ?>
				<?php submit_button( __( '生成邀请码', 'begin' ) ); ?>
			</form>
		</div>
	<?php
	}

	function begin_fields_cb2( $val ) {
		$begin_codes_options = get_option( 'begin_codes_options' );

		$prefix = trim( $val );
		$count = isset( $_POST['begin_field_count'] ) ? (int) $_POST['begin_field_count'] : 1;
		$length = isset( $_POST['begin_field_length'] ) ? (int) $_POST['begin_field_length'] : 8;
		$howmany = isset( $_POST['begin_field_howmany'] ) ? (int) $_POST['begin_field_howmany'] : 5;
		if ( $count < 1 ) {
			add_settings_error( 'begin', '', __( 'How many time this code can be used?', 'begin' ) . sprintf( __( ' (Minimum %d)', 'begin' ), 1 ), 'error' );
		} elseif( $length<4 || $length>16 ) {
			add_settings_error( 'begin', '', __( 'Incorrect length.', 'begin' ) . sprintf( __( ' (Minimum %d)', 'begin' ), 4 ) . sprintf( __( ' (Maximum %d)', 'begin' ), 16 ), 'error' );
		} elseif( $howmany<1 ) {
			add_settings_error( 'begin', '', __( 'How many codes do you need?', 'begin' ) . sprintf( __( ' (Minimum %d)', 'begin' ), 1 ), 'error' );
		} else {
			$temp = array();
			$i = 1;
			while ( $i <= $howmany ) {
				$temp = strtoupper( $prefix . wp_generate_password( $length, false ) );
				if ( ! in_array( $temp, $begin_codes_options['codes'] ) ) {
					++$i;
					$begin_codes_options['codes'][ $temp ] = array( 'maxcount' => $count, 'leftcount' => $count, 'users' => '' );
				}
			}
			add_settings_error( 'begin', '', sprintf( __( '已添加 %d 个邀请码，<a href="%s">查看全部邀请码 &raquo;</a>', 'begin' ), $howmany, admin_url( 'admin.php?page=begin_list_codes' ) ), 'updated' );
			update_option( 'begin_codes_options', $begin_codes_options );
		}
		return false;
	}

	function begin_fields_cb( $val ) {
		$begin_codes_options = get_option( 'begin_codes_options' );

		$code = trim( strtoupper( sanitize_key( $val ) ) );
		$count = isset( $_POST['begin_field_count'] ) ? (int)$_POST['begin_field_count'] : 1;
		if( isset( $begin_codes_options['codes'][$code] ) ):
			add_settings_error( 'begin', '', sprintf( __( '已添加邀请码：<i>%s</i> 可以再添加一个.', 'begin' ), esc_html( $code ) ), 'error' );
		elseif( $count<1 ):
			add_settings_error( 'begin', '', __( 'How many time this code can be used?', 'begin' ) . sprintf( __( ' (Minimum %d)', 'begin' ), 1 ), 'error' );
		elseif( $code=='' ):
			add_settings_error( 'begin', '', __( '您还没有输入邀请码 ...', 'begin' ), 'error' );
		else:
			add_settings_error( 'begin', '', sprintf( __( '已添加邀请码：<i>%s</i><a href="%s"> 查看全部邀请码 &raquo;</a>', 'begin' ), esc_html( $code ), admin_url( 'admin.php?page=begin_list_codes' ) ), 'updated' );
			create_invitation_code( $code, $count );
		endif;
		return false;
	}

	function create_invitation_code( $code, $count = 1 ) {
		$begin_codes_options = get_option( 'begin_codes_options' );

		$count = (int) $count>0 ? $count : 1;
		$code = sanitize_key( $code );
		if ( isset( $begin_codes_options['codes'][ $code ] ) || ! trim( $code ) ) {
			return false;
		} else {
			$begin_codes_options['codes'][ $code ] = array( 'maxcount' => $count, 'leftcount' => $count, 'users' => '' );
			update_option( 'begin_codes_options', $begin_codes_options );
			return true;
		}
	}

	register_activation_hook( begin__FILE__, 'begin_activation' );
	function begin_activation() {
		add_option( 'begin_codes_options', array( 'codes' => array( 'INVITATION' => array( 'maxcount' => 999999, 'leftcount' => 999999, 'users' => '' ) ) ) );
		add_option( 'begin_fields', array( 'link' => 'on', 'text_link'=> sprintf( __( '需要邀请码 ? <a href="mailto:%s">联系我们!</a>', 'begin' ), get_option( 'admin_email' ) ) ) );
	}

	register_uninstall_hook( begin__FILE__, 'begin_uninstaller' );
	function begin_uninstaller() {
		delete_option( 'begin_codes_options' );
		delete_option( 'begin_fields' );
	}

	function begin_list_codes() { 
		$begin_codes_options = get_option( 'begin_codes_options' );

		$admin_notices = array( 'updated' => array(), 'error' => array() );
		if ( isset( $_GET['action'], $_GET['_wpnonce'] ) ) { // do this in admin-post next time
			switch ( $_GET['action'] ) {
				case 'delete':
					$code = isset( $_GET['code'] ) ? sanitize_key( $_GET['code'] ) : false;
					if ( $code && isset( $begin_codes_options['codes'][ $code ] ) && wp_verify_nonce( $_GET['_wpnonce'], 'begin-' . $_GET['action'] . '-' . $code ) ) {
						unset( $begin_codes_options['codes'][ $code ] );
						update_option( 'begin_codes_options', $begin_codes_options );
						$admin_notices['updated'][] = sprintf( __( '邀请码 <b>%s</b> 成功删除。', 'begin' ), esc_html( $code ) );
					} else {
						$admin_notices['error'][] = sprintf( __( '邀请码 <b>%s</b>并没有被删除。', 'begin' ), esc_html( $code ) );
					}
					break;
				case 'reset': 
					if ( wp_verify_nonce( $_GET['_wpnonce'], 'begin-' . $_GET['action'] ) ) {
						$begin_codes_options['codes'] = array();
						update_option( 'begin_codes_options', $begin_codes_options );
						$admin_notices['updated'][] = __( '所有的邀请码都删除了。', 'begin' );
					}
					break;
			}
		}
		// actions
		// $counts['all'] = count( $begin_codes_options['codes'] );
		$counts['used'] = 0;
		$counts['not_used'] = 0;
		if( $counts['all'] > 0 )
			foreach ( $begin_codes_options['codes'] as $c ) {
				if ( ! $c['users'] ) {
					++$counts['not_used'];
				} else {
					++$counts['used'];
				}
			}
	?>
		<div class="wrap">
			<h1><?php _e( '全部邀请码', 'begin' ); ?>
				<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=begin_rand_code' ); ?>"><?php _e( '生成邀请码', 'begin' ) ;?></a>
			<?php
			if ( ! empty( $_GET['s'] ) ) {
				printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html( $_GET['s'] ) );
			}
			?>
			</h1>
			<p>
			<?php
			foreach ( $admin_notices['updated'] as $an ) {
				echo '<div class="updated"><p>' . $an . '</p></div>';
			}
			foreach ( $admin_notices['error'] as $an ) {
				echo '<div class="error"><p>' . $an . '</p></div>';
			}
			unset( $an );
			?>
			</p>

			<form action="<?php echo admin_url( 'admin.php' ); ?>">
			<p class="search-box">

				<input type="hidden" id="page" name="page" value="begin_list_codes" />
			</p>
			</form>
			<table id="codes_table" class="widefat plugins datatables">
				<thead>
					<tr>
						<th scope="col" width="350"><?php _e( '邀请码', 'begin' ); ?></th>
						<th scope="col" width="350"><?php _e( '计数', 'begin' ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col"><?php _e( '邀请码', 'begin' ); ?></th>
						<th scope="col"><?php _e( '计数', 'begin' ); ?></th>
					</tr>
				</tfoot>
				<tbody class="codes_table">
				<?php
				$empty = true;
				if ( isset( $begin_codes_options['codes'] ) && count( $begin_codes_options['codes'] ) > 0 ) {
					foreach ( $begin_codes_options['codes'] as $code=>$infos ) {
						if ( ! empty( $_GET['status'] ) && ( ( 'used' == $_GET['status'] && ! $infos['users'] ) || ( 'not_used' == $_GET['status'] && $infos['users'] ) ) ) {
							continue;
						}
						if ( ! empty( $_GET['s'] ) && strstr( $code, strtoupper( $_GET['s'] ) ) === false ) {
							continue;
						}
						$empty = false;
					?>
						<tr class="token">
							<td>
								<div class="activation">
									<pre><b><?php echo esc_html( $code ); ?></b></pre>
								</div>
							</td>
							<td>
								<div class="activation">
									<?php echo '<b>' . $infos['leftcount'] . '</b> / ' . (int) $infos['maxcount']; ?>
								</div>
							</td>

						</tr>
					<?php 
					}
				} else {
					echo '<tr><td colspan="4">' . sprintf( __( '无邀请码, <a href="%s">添加一个</a>', 'begin' ), admin_url( 'admin.php?page=begin_rand_code' ) ) . '</td></tr>';
					$empty = false;
				}
				if ( $empty ) {
					echo '<tr><td colspan="4">' . sprintf( __( '无邀请码, <a href="%s">添加一个</a>', 'begin' ), admin_url( 'admin.php?page=begin_rand_code' ) ) . '</td></tr>';
				}
				?>
				</tbody>
			</table>
			<p><a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=begin_list_codes&action=reset' ), 'begin-reset' ); ?>" class="button-secondary"><?php _e( '删除所有邀请码', 'begin' ); ?></a></p>
		</div>
	<?php }

	add_action( 'admin_notices', 'begin_admin_notice_noone' );
	function begin_admin_notice_noone() {
		$begin_codes_options = get_option( 'begin_codes_options' );

		$codes = $begin_codes_options['codes'];
		// foreach ( $codes as $code => $val )
			if ( ! $val['leftcount'] ) {
				unset( $codes[$code] );
			}
	}
}