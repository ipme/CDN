<?php
class Linksclick {
	// Constructor
	function __construct() {
		//register_activation_hook( __FILE__, 'flush_rewrite_rules' );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'columns_data' ) );
		add_filter( 'manage_edit-surl_columns', array( $this, 'columns_filter' ) );
		add_filter( 'post_updated_messages', array( $this, 'updated_message' ) );
		add_action( 'admin_menu', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'meta_box_save' ), 1, 2 );
		add_action( 'template_redirect', array( $this, 'count_and_redirect' ) );
	}

	function register_post_type() {
		$slug = 'surl';
		$labels = array(
			'name'               => __( '短链接', 'begin' ),
			'singular_name'      => __( '链接', 'begin' ),
			'add_new'            => __( '添加链接', 'begin' ),
			'add_new_item'       => __( '添加新链接', 'begin' ),
			'edit'               => __( '编辑', 'begin' ),
			'edit_item'          => __( '编辑链接', 'begin' ),
			'new_item'           => __( '新的链接', 'begin' ),
			'view'               => __( '查看链接', 'begin' ),
			'view_item'          => __( '查看链接', 'begin' ),
			'search_items'       => __( '搜索链接', 'begin' ),
			'not_found'          => __( '你还没有发表链接', 'begin' ),
			'not_found_in_trash' => __( '回收站中没有链接', 'begin' ),
			'messages'           => array(
				 0 => '', // Unused. Messages start at index 1.
				 1 => __( '链接更新。<a href="%s">查看链接</a>', 'begin' ),
				 2 => __( '自定义字段已更新。', 'begin' ),
				 3 => __( '自定义字段已删除。', 'begin' ),
				 4 => __( '链接已更新。', 'begin' ),
				/* translators: %s: date and time of the revision */
				 5 => isset( $_GET['revision'] ) ? sprintf( __( '文章已从 %s 恢复到修订版本', 'begin' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				 6 => __( '链接已更新。 <a href="%s">查看链接</a>', 'begin' ),
				 7 => __( '链接已保存', 'begin' ),
				 8 => __( '链接已提交。', 'begin' ),
				 9 => __( '链接定时', 'begin' ),
				10 => __( '链接草稿已更新。', 'begin' ),
			),
		);

		$labels = apply_filters( 'simple_urls_cpt_labels', $labels );
		$rewrite_slug = apply_filters( 'simple_urls_slug', 'go' );
		register_post_type( $slug,
			array(
				'labels'        => $labels,
				'public'        => true,
				'query_var'     => true,
				'menu_position' => 20,
				'menu_icon'          => 'dashicons-chart-bar',
				'supports'      => array( 'title' ),
				'rewrite'       => array( 'slug' => $rewrite_slug, 'with_front' => false ),
			)
		);
	}

	function columns_filter( $columns ) {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( '标题', 'begin' ),
			'url'       => __( '重定向到', 'begin' ),
			'permalink' => __( '永久链接', 'begin' ),
			'clicks'    => __( '点击', 'begin' ),
			'id'    => __( 'ID', 'begin' ),
		);
		return $columns;
	}

	function columns_data( $column ) {
		global $post;
		$url   = get_post_meta( $post->ID, '_surl_redirect', true );
		$count = get_post_meta( $post->ID, '_surl_count', true );
		if ( 'url' == $column ) {
			echo make_clickable( esc_url( $url ? $url : '' ) );
		}
		elseif ( 'permalink' == $column ) {
			echo make_clickable( get_permalink() );
		}
		elseif ( 'clicks' == $column ) {
			echo esc_html( $count ? $count : 0 );
		}
		if ( 'id' == $column ) {
			echo url_to_postid(get_permalink() );
		}
	}

	function updated_message( $messages ) {
		$surl_object = get_post_type_object( 'surl' );
		$messages['surl'] = $surl_object->labels->messages;
		if ( $permalink = get_permalink() ) {
			foreach ( $messages['surl'] as $id => $message ) {
				$messages['surl'][ $id ] = sprintf( $message, $permalink );
			}
		}
		return $messages;
	}

	function add_meta_box() {
		add_meta_box( 'surl', __( '链接信息', 'begin' ), array( $this, 'meta_box' ), 'surl', 'normal', 'high' );
	}

	function meta_box() {
		global $post;
		printf( '<input type="hidden" name="_surl_nonce" value="%s" />', wp_create_nonce( plugin_basename(__FILE__) ) );
		printf( '<p><label for="%s">%s</label></p>', '_surl_redirect', __( '重定向后真正的访问地址', 'begin' ) );
		printf( '<p><input style="%s" type="text" name="%s" id="%s" value="%s" /></p>', 'width: 99%;', '_surl_redirect', '_surl_redirect', esc_attr( get_post_meta( $post->ID, '_surl_redirect', true ) ) );
		$count = isset( $post->ID ) ? get_post_meta($post->ID, '_surl_count', true) : 0;
		echo '<p>' . sprintf( __( '该链接已被点击 %d 次', 'begin' ), esc_attr( $count ) ) . '</p>';
	}

	function meta_box_save( $post_id, $post ) {
		$key = '_surl_redirect';
		if ( ! isset( $_POST['_surl_nonce'] ) || ! wp_verify_nonce( $_POST['_surl_nonce'], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		// don't try to save the data under autosave, ajax, or future post.
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
		if ( defined('DOING_AJAX') && DOING_AJAX ) return;
		if ( defined('DOING_CRON') && DOING_CRON ) return;
		// is the user allowed to edit the URL?
		if ( ! current_user_can( 'edit_posts' ) || 'surl' != $post->post_type )
			return;
		$value = isset( $_POST[ $key ] ) ? $_POST[ $key ] : '';
		if ( $value ) {
			// save/update
			update_post_meta( $post->ID, $key, $value );
		} else {
			// delete if blank
			delete_post_meta( $post->ID, $key );
		}
	}

	function count_and_redirect() {
		if ( ! is_singular( 'surl' ) ) {
			return;
		}
		global $wp_query;
		// Update the count
		$count = isset( $wp_query->post->ID ) ? get_post_meta( $wp_query->post->ID, '_surl_count', true ) : 0;
		update_post_meta( $wp_query->post->ID, '_surl_count', $count + 1 );
		// Handle the redirect
		$redirect = isset( $wp_query->post->ID ) ? get_post_meta( $wp_query->post->ID, '_surl_redirect', true ) : '';

		// Filter the redirect URL.
		$redirect = apply_filters( 'simple_urls_redirect_url', $redirect, $count );
		 // Action hook that fires before the redirect.
		do_action( 'simple_urls_redirect', $redirect, $count );
		if ( ! empty( $redirect ) ) {
			wp_redirect( esc_url_raw( $redirect ), 301);
			exit;
		}
		else {
			wp_redirect( home_url(), 302 );
			exit;
		}
	}
}

new Linksclick;