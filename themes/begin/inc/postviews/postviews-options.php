<?php
### 变量
$id = (isset($_GET['id'] ) ? intval($_GET['id'] ) : 0);
$mode = (isset($_GET['mode'] ) ? trim($_GET['mode'] ) : '' );
$text = '';

### 表单处理
if(!empty($_POST['Submit'] )) {
	check_admin_referer( 'wp-postviews_options' );
	$views_options = array(
		'count'                   => intval( begin_views_options_parse('views_count') ), 
		'exclude_bots'            => intval( begin_views_options_parse('views_exclude_bots') ), 
		'use_ajax'                => intval( begin_views_options_parse('views_use_ajax') ), 
		'template'                => trim( begin_views_options_parse('views_template_template') ), 
		'most_viewed_template'    => trim( begin_views_options_parse('views_template_most_viewed') )
	);
	$update_views_queries = array();
	$update_views_text = array();
	$update_views_queries[] = update_option( 'views_options', $views_options );
	$update_views_text[] = __( '设置', 'begin' );
	$i = 0;

	foreach( $update_views_queries as $update_views_query ) {
		if( $update_views_query ) {
			$text .= '<p style="color: green;">' . $update_views_text[$i] . ' ' . __( '已更新', 'begin' ) . '</p>';
		}
		$i++;
	}
	if( empty( $text ) ) {
		$text = '<p style="color: red;">' . __( '无选项更新', 'begin' ) . '</p>';
	}
}

$views_options = get_option( 'views_options' );

// 默认
if( !isset ( $views_options['use_ajax'] ) ) {
	$views_options['use_ajax'] = 1;
}
?>
<script type="text/javascript">
	/* <![CDATA[*/
	function views_default_templates(template) {
		var default_template;
		switch(template) {
			case 'template':
				default_template = "<?php _e( '%VIEW_COUNT%', 'begin' ); ?>";
				break;
			case 'most_viewed':
				default_template = "<li><a href=\"%POST_URL%\"  title=\"%POST_TITLE%\">%POST_TITLE%</a> - %VIEW_COUNT% <?php _e( '浏览', 'begin' ); ?></li>";
				break;
		}
		jQuery("#views_template_" + template).val(default_template);
	}
	/* ]]> */
</script>
<?php if( !empty( $text ) ) { echo '<div id="message" class="updated fade"><p>' . $text . '</p></div>'; } ?>
<form method="post" action="options-general.php?page=views_options">
<?php wp_nonce_field( 'wp-postviews_options' ); ?>
<div class="wrap">
	<h2><?php _e( '浏览计数设置', 'begin' ); ?></h2>
	<table class="form-table">
		 <tr>
			<td valign="top" width="30%"><strong><?php _e( '计数来源：', 'begin' ); ?></strong></td>
			<td valign="top">
				<select name="views_count" size="1">
					<option value="0"<?php selected( '0', $views_options['count'] ); ?>><?php _e( '所有人', 'begin' ); ?></option>
					<option value="1"<?php selected( '1', $views_options['count'] ); ?>><?php _e( '只有访客', 'begin' ); ?></option>
					<option value="2"<?php selected( '2', $views_options['count'] ); ?>><?php _e( '只有注册用户', 'begin' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top" width="30%"><strong><?php _e( '排除机器人：', 'begin' ); ?></strong></td>
			<td valign="top">
				<select name="views_exclude_bots" size="1">
					<option value="0"<?php selected( '0', $views_options['exclude_bots'] ); ?>><?php _e( '否', 'begin' ); ?></option>
					<option value="1"<?php selected( '1', $views_options['exclude_bots'] ); ?>><?php _e( '是', 'begin' ); ?></option>
				</select>
			</td>
		</tr>
		<?php if( defined( 'WP_CACHE' ) && WP_CACHE ): ?>
			<tr>
				<td valign="top" width="30%"><strong><?php _e( '使用Ajax更新浏览次数：', 'begin' ); ?></strong></td>
				<td valign="top">
					<select name="views_use_ajax" size="1">
						<option value="0"<?php selected( '0', $views_options['use_ajax'] ); ?>><?php _e( '否', 'begin' ); ?></option>
						<option value="1"<?php selected( '1', $views_options['use_ajax'] ); ?>><?php _e( '是', 'begin' ); ?></option>
					</select>
					<p>
						<?php _e( '如果启用了静态缓存，将使用AJAX更新浏览计数。', 'begin' ); ?>
					</p>
				</td>
			</tr>
		<?php else: ?>
			<input type="hidden" name="views_use_ajax" value="0" />
		<?php endif; ?>
		<tr>
			<td valign="top">
				<strong><?php _e( '显示模板：', 'begin' ); ?></strong><br /><br />
				<?php _e( '可使用的变量：', 'begin' ); ?><br /><br />
				正常显示计数：%VIEW_COUNT%<br />
				以千单位显示：%VIEW_COUNT_ROUNDED%<br /><br />
				<input type="button" name="RestoreDefault" value="<?php _e( '恢复默认', 'begin' ); ?>" onclick="views_default_templates( 'template' );" class="button" />
			</td>
			<td valign="top">
				<input type="text" id="views_template_template" name="views_template_template" size="70" value="<?php echo htmlspecialchars(stripslashes($views_options['template'] )); ?>" />
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php _e( '保存设置', 'begin' ); ?>" />
	</p>
</div>
</form>