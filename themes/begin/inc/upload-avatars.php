<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
class Begin_Local_Avatars {
	private $user_id_being_edited, $avatar_upload_error, $remove_nonce, $avatar_ratings;
	public $options;
	public function __construct() {
		$this->options = (array) get_option( 'begin_local_avatars' );
		if ( empty( $this->options['only'] ) )
		if (zm_get_option('cache_avatar')) {
			add_filter( 'begin_avatar', array( $this, 'begin_avatar' ), 12, 5 );
		} else {
			add_filter( 'get_avatar', array( $this, 'get_avatar' ), 12, 5 );
		}

		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'show_user_profile', array( $this, 'edit_user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'edit_user_profile' ) );
		
		add_action( 'personal_options_update', array( $this, 'edit_user_profile_update' ) );
		add_action( 'edit_user_profile_update', array( $this, 'edit_user_profile_update' ) );
		add_action( 'admin_action_remove-begin-local-avatar', array( $this, 'action_remove_begin_local_avatar' ) );
		add_action( 'wp_ajax_assign_begin_local_avatar_media', array( $this, 'ajax_assign_begin_local_avatar_media' ) );
		add_action( 'wp_ajax_remove_begin_local_avatar', array( $this, 'action_remove_begin_local_avatar' ) );
		add_action( 'user_edit_form_tag', array( $this, 'user_edit_form_tag' ) );
		
		add_filter( 'avatar_defaults', array( $this, 'avatar_defaults' ) );
	}

	public function begin_avatar( $avatar = '', $id_or_email, $size = 96, $default = '', $alt = '' ) {
		if ( is_numeric( $id_or_email ) )
			$user_id = (int) $id_or_email;
		elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
			$user_id = $user->ID;
		elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
			$user_id = (int) $id_or_email->user_id;
		
		if ( empty( $user_id ) )
			return $avatar;

		$local_avatars = get_user_meta( $user_id, 'begin_local_avatar', true );
		if ( empty( $local_avatars['full'] ) )
			return $avatar;

		if ( ! empty( $local_avatars['media_id'] ) ) {
			if ( ! $avatar_full_path = get_attached_file( $local_avatars['media_id'] ) ) {
				if ( is_user_logged_in() )
					$this->avatar_delete( $user_id );
				return $avatar;
			}
		}
		$size = (int) $size;
		if ( empty( $alt ) )
			$alt = get_the_author_meta( 'display_name', $user_id );
		if ( ! array_key_exists( $size, $local_avatars ) ) {
			$local_avatars[$size] = $local_avatars['full'];
			if ( $allow_dynamic_resizing = apply_filters( 'begin_local_avatars_dynamic_resize', true ) ) :
				$upload_path = wp_upload_dir();
				if ( ! isset( $avatar_full_path ) )
					$avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
				$editor = wp_get_image_editor( $avatar_full_path );
				if ( ! is_wp_error( $editor ) ) {
					$resized = $editor->resize( $size, $size, true );
					if ( ! is_wp_error( $resized ) ) {
						$dest_file = $editor->generate_filename();
						$saved = $editor->save( $dest_file );
						if ( ! is_wp_error( $saved ) )
							$local_avatars[$size] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $dest_file );
					}
				}
				update_user_meta( $user_id, 'begin_local_avatar', $local_avatars );
			endif;
		}

		if ( 'http' != substr( $local_avatars[$size], 0, 4 ) )
			$local_avatars[$size] = home_url( $local_avatars[$size] );
		
		$author_class = is_author( $user_id ) ? ' current-author' : '' ;
		$avatar = "<img alt='" . esc_attr( $alt ) . "' src='" . esc_url( $local_avatars[$size] ) . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";
		
		return apply_filters( 'begin_local_avatar', $avatar );
	}



	public function get_avatar( $avatar = '', $id_or_email, $size = 96, $default = '', $alt = '' ) {
		if ( is_numeric( $id_or_email ) )
			$user_id = (int) $id_or_email;
		elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
			$user_id = $user->ID;
		elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
			$user_id = (int) $id_or_email->user_id;
		
		if ( empty( $user_id ) )
			return $avatar;

		$local_avatars = get_user_meta( $user_id, 'begin_local_avatar', true );
		if ( empty( $local_avatars['full'] ) )
			return $avatar;

		if ( ! empty( $local_avatars['media_id'] ) ) {
			if ( ! $avatar_full_path = get_attached_file( $local_avatars['media_id'] ) ) {
				if ( is_user_logged_in() )
					$this->avatar_delete( $user_id );
				return $avatar;
			}
		}
		$size = (int) $size;
		if ( empty( $alt ) )
			$alt = get_the_author_meta( 'display_name', $user_id );
		if ( ! array_key_exists( $size, $local_avatars ) ) {
			$local_avatars[$size] = $local_avatars['full'];
			if ( $allow_dynamic_resizing = apply_filters( 'begin_local_avatars_dynamic_resize', true ) ) :
				$upload_path = wp_upload_dir();
				if ( ! isset( $avatar_full_path ) )
					$avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
				$editor = wp_get_image_editor( $avatar_full_path );
				if ( ! is_wp_error( $editor ) ) {
					$resized = $editor->resize( $size, $size, true );
					if ( ! is_wp_error( $resized ) ) {
						$dest_file = $editor->generate_filename();
						$saved = $editor->save( $dest_file );
						if ( ! is_wp_error( $saved ) )
							$local_avatars[$size] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $dest_file );
					}
				}
				update_user_meta( $user_id, 'begin_local_avatar', $local_avatars );
			endif;
		}

		if ( 'http' != substr( $local_avatars[$size], 0, 4 ) )
			$local_avatars[$size] = home_url( $local_avatars[$size] );
		
		$author_class = is_author( $user_id ) ? ' current-author' : '' ;
		$avatar = "<img alt='" . esc_attr( $alt ) . "' src='" . esc_url( $local_avatars[$size] ) . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";
		
		return apply_filters( 'begin_local_avatar', $avatar );
	}

	public function admin_init() {
		if ( $old_ops = get_option( 'begin_local_avatars_caps' ) ) {
			if ( ! empty( $old_ops['begin_local_avatars_caps'] ) )
				update_option( 'begin_local_avatars', array( 'caps' => 1 ) );
			delete_option( 'begin_local_avatar_caps' );
		}
		
		register_setting( 'discussion', 'begin_local_avatars', array( $this, 'sanitize_options' ) );
		add_settings_field( 'begin-local-avatars-only', __('本地头像使用','begin'), array( $this, 'avatar_settings_field' ), 'discussion', 'avatars', array( 'key' => 'only', 'desc' => '仍然使用Gravatar头像' ) );
		add_settings_field( 'begin-local-avatars-caps', __('本地上传权限','begin'), array( $this, 'avatar_settings_field' ), 'discussion', 'avatars', array( 'key' => 'caps', 'desc' => '仅允许作者及及以上权限用户本地上传头像' ) );
	}

	public function admin_enqueue_scripts( $hook_suffix ) {
		if ( 'profile.php' != $hook_suffix && 'user-edit.php' != $hook_suffix )
			return;
		$user_id = ( 'profile.php' == $hook_suffix ) ? get_current_user_id() : (int) $_GET['user_id'];
		$this->remove_nonce = wp_create_nonce( 'remove_begin_local_avatar_nonce' );
		wp_localize_script( 'begin-local-avatars', 'i10n_SimpleLocalAvatars', array(
			'user_id'			=> $user_id,
			'insertMediaTitle'	=> __('选择头像','begin'),
			'insertIntoPost'	=> __('设置头像','begin'),
			'deleteNonce'		=> $this->remove_nonce,
			'mediaNonce'		=> wp_create_nonce( 'assign_begin_local_avatar_nonce' ),
		) );
	}

	public function sanitize_options( $input ) {
		$new_input['caps'] = empty( $input['caps'] ) ? 0 : 1;
		$new_input['only'] = empty( $input['only'] ) ? 0 : 1;
		return $new_input;
	}

	public function avatar_settings_field( $args ) {
		$args = wp_parse_args( $args, array(
			'key' 	=> '',
			'desc'	=> '',
		) );

		if ( empty( $this->options[$args['key']] ) )
			$this->options[$args['key']] = 0;
		
		echo '
			<label for="begin-local-avatars-' . $args['key'] . '">
				<input type="checkbox" name="begin_local_avatars[' . $args['key'] . ']" id="begin-local-avatars-' . $args['key'] . '" value="1" ' . checked( $this->options[$args['key']], 1, false ) . ' />
				' . __($args['desc'],'begin-local-avatars') . '
			</label>
		';
	}

	public function edit_user_profile( $profileuser ) {
	?>
	<h3><?php _e( '头像','begin-local-avatars' ); ?></h3>
	
	<table class="form-table">
		<tr>
			<th scope="row"><label for="begin-local-avatar"><?php _e('上传头像','begin'); ?></label></th>
			<td style="width: 50px;" id="begin-local-avatar-photo">
				<?php echo get_begin_local_avatar( $profileuser->ID ); ?>
			</td>
			<td>
			<?php
				if ( ! $upload_rights = current_user_can('upload_files') )
					$upload_rights = empty( $this->options['caps'] );
			
				if ( $upload_rights ) {
					do_action( 'begin_local_avatar_notices' ); 
					wp_nonce_field( 'begin_local_avatar_nonce', '_begin_local_avatar_nonce', false );
					$remove_url = add_query_arg(array(
						'action'	=> 'remove-begin-local-avatar',
						'user_id'	=> $profileuser->ID,
						'_wpnonce'	=> $this->remove_nonce,
					) );
			?>
					<p style="display: inline-block; width: 26em;">
						<span class="description"><?php _e( 'Choose an image from your computer:' ); ?></span><br />
						<input type="file" name="begin-local-avatar" id="begin-local-avatar" class="standard-text" />
						<span class="spinner" id="begin-local-avatar-spinner"></span>
					</p>
					<p>
						
						<a href="<?php echo $remove_url; ?>" class="button item-delete submitdelete deletion" id="begin-local-avatar-remove"<?php if ( empty( $profileuser->begin_local_avatar ) ) echo ' style="display:none;"'; ?>><?php _e('删除本地头像','begin-local-avatars'); ?></a>
					</p>
			<?php
				} else {
					if ( empty( $profileuser->begin_local_avatar ) )
						echo '<span class="description">' . __('没有设置本地头像。 请在Gravatar.com上设置你的头像。','begin') . '</span>';
					else 
						echo '<span class="description">' . __('您没有上传头像的权限，请与博客管理员联系。','begin') . '</span>';
				}
			?>
			</td>
		</tr>
	</table>
	<?php
	}

	public function user_edit_form_tag() {
		echo 'enctype="multipart/form-data"';
	}

	private function assign_new_user_avatar( $url_or_media_id, $user_id ) {
		$this->avatar_delete( $user_id );
		$meta_value = array();
		if ( is_int( $url_or_media_id ) ) {
			$meta_value['media_id'] = $url_or_media_id;
			$url_or_media_id = wp_get_attachment_url( $url_or_media_id );
		}
		$meta_value['full'] = $url_or_media_id;
		update_user_meta( $user_id, 'begin_local_avatar', $meta_value );
	}

	public function edit_user_profile_update( $user_id ) {
		if( empty( $_POST['_begin_local_avatar_nonce'] ) || ! wp_verify_nonce( $_POST['_begin_local_avatar_nonce'], 'begin_local_avatar_nonce' ) )
			return;
		if ( ! empty( $_FILES['begin-local-avatar']['name'] ) ) :
			if ( false !== strpos( $_FILES['begin-local-avatar']['name'], '.php' ) ) {
				$this->avatar_upload_error = __('出于安全原因，扩展名“.php”不能出现在您的文件名中。','begin');
				add_action( 'user_profile_update_errors', array( $this, 'user_profile_update_errors' ) );
				return;
			}
			if ( ! function_exists( 'wp_handle_upload' ) )
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			$this->user_id_being_edited = $user_id;
			$avatar = wp_handle_upload( $_FILES['begin-local-avatar'], array(
				'mimes' 					=> array(
					'jpg|jpeg|jpe'	=> 'image/jpeg',
					'gif'			=> 'image/gif',
					'png'			=> 'image/png',
				),
				'test_form'					=> false,
				'unique_filename_callback'	=> array( $this, 'unique_filename_callback' )
			) );
			if ( empty($avatar['file']) ) {
				switch ( $avatar['error'] ) {
					case 'File type does not meet security guidelines. Try another.' :
						$this->avatar_upload_error = __('请为头像上传有效的图片文件。','begin');
						break;
					default :
						$this->avatar_upload_error = '<strong>' . __('上传头像时出错：','begin') . '</strong> ' . esc_html( $avatar['error'] );
				}
				add_action( 'user_profile_update_errors', array( $this, 'user_profile_update_errors' ) );
				return;
			}
			$this->assign_new_user_avatar( $avatar['url'], $user_id );
		endif;

		if ( isset( $avatar['url'] ) || $avatar = get_user_meta( $user_id, 'begin_local_avatar', true ) ) {
			if ( empty( $_POST['begin_local_avatar_rating'] ) || ! array_key_exists( $_POST['begin_local_avatar_rating'], $this->avatar_ratings ) )
				$_POST['begin_local_avatar_rating'] = key( $this->avatar_ratings );
			update_user_meta( $user_id, 'begin_local_avatar_rating', $_POST['begin_local_avatar_rating'] );
		}
	}

	public function action_remove_begin_local_avatar() {
		if ( ! empty( $_GET['user_id'] ) &&  ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'remove_begin_local_avatar_nonce' ) ) {
			$user_id = (int) $_GET['user_id'];
			if ( ! current_user_can('edit_user', $user_id) )
				wp_die( __('您无权编辑此用户。') );
			$this->avatar_delete( $user_id );
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
				echo get_begin_local_avatar( $user_id );
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			die;
	}

	public function ajax_assign_begin_local_avatar_media() {
		if ( empty( $_POST['user_id'] ) || empty( $_POST['media_id'] ) || ! current_user_can( 'upload_files' ) || ! current_user_can( 'edit_user', $_POST['user_id'] ) || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'assign_begin_local_avatar_nonce' ) )
			die;
		$media_id = (int) $_POST['media_id'];
		$user_id = (int) $_POST['user_id'];
		if ( wp_attachment_is_image( $media_id ) )
			$this->assign_new_user_avatar( $media_id, $user_id );
		echo get_begin_local_avatar( $user_id );
		die;
	}

	public function avatar_defaults( $avatar_defaults ) {
		remove_action( 'get_avatar', array( $this, 'get_avatar' ) );
		return $avatar_defaults;
	}

	public function avatar_delete( $user_id ) {
		$old_avatars = (array) get_user_meta( $user_id, 'begin_local_avatar', true );
		if ( empty( $old_avatars ) )
			return;
		if ( array_key_exists( 'media_id', $old_avatars ) )
			unset( $old_avatars['media_id'], $old_avatars['full'] );
		if ( ! empty( $old_avatars ) ) {
			$upload_path = wp_upload_dir();
			foreach ($old_avatars as $old_avatar ) {
				$old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
				if ( file_exists( $old_avatar_path ) )
					unlink( $old_avatar_path );
			}
		}
		delete_user_meta( $user_id, 'begin_local_avatar' );
		delete_user_meta( $user_id, 'begin_local_avatar_rating' );
	}

	public function unique_filename_callback( $dir, $name, $ext ) {
		$user = get_user_by( 'id', (int) $this->user_id_being_edited ); 
		$name = $base_name = sanitize_file_name( $user->display_name . '_avatar_' . time() );
		$number = 1;
		while ( file_exists( $dir . "/$name$ext" ) ) {
			$name = $base_name . '_' . $number;
			$number++;
		}
		return $name . $ext;
	}

	public function user_profile_update_errors( WP_Error $errors ) {
		$errors->add( 'avatar_error', $this->avatar_upload_error );
	}
}

function get_begin_local_avatar( $id_or_email, $size = 96, $default = '', $alt = '' ) {
	global $begin_local_avatars;
	$avatar = $begin_local_avatars->get_avatar( '', $id_or_email, $size, $default, $alt );
	if ( empty ( $avatar ) ) {
		remove_action( 'get_avatar', array( $begin_local_avatars, 'get_avatar' ) );
		$avatar = get_avatar( $id_or_email, $size, $default, $alt );
		add_action( 'get_avatar', array( $begin_local_avatars, 'get_avatar' ) );
	}
	return $avatar;
}