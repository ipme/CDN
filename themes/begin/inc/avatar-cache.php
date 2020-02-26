<?php
// 头像缓存
function begin_avatar( $email = 'aaaaa@aaaaaa.com', $size = '64', $default = '', $alt = '') {
	// 设置$email默认值为一个不存在的aaaaa@aaaaaa.com
	$f = md5( strtolower( $email ) );

	// 创建avatar文件夹
	$upload_dir = wp_upload_dir();
	$poster_dir = $upload_dir['basedir'].'/avatar';
	// 检查该文件夹
	if (!is_dir($poster_dir)){
		wp_mkdir_p($poster_dir);
	}

	// 将头像缓存到uploads/avatar目录下
	$a = $upload_dir['baseurl'] . '/avatar/'. $f . $size . '.png';
	$e = $upload_dir['basedir'] . '/avatar/' . $f . $size . '.png';
	$d = $upload_dir['basedir'] . '/avatar/' . $f . '-d.png';

	if($default=='')
	$random_avata = explode(',' , zm_get_option('random_avatar_url'));
	$random_avata_array = array_rand($random_avata);
	$src = $random_avata[$random_avata_array];
	$default = $src;

	$t = 2592000; // 缓存有效期30天, 这里单位:秒
	if ( !is_file($e) || (time() - filemtime($e)) > $t ) {
		if ( !is_file($d) || (time() - filemtime($d)) > $t ) {
		// 验证是否有头像
			$uri = 'http://www.gravatar.com/avatar/' . $f . '?d=404';
			$headers = @get_headers($uri);
			if (!preg_match("|200|", $headers[0])) {
				// 没有头像，则新建一个空白文件作为标记
				$handle = fopen($d, 'w');
				fclose($handle);
				$a = $default;
			} else {
				// 有头像且不存在则更新
				$r = get_option('avatar_rating');
				$g = 'http://www.gravatar.com/avatar/'. $f. '?s='. $size. '&r=' . $r;
				copy($g, $e);
			}
		} else {
			$a = $default;
		}
	}

	$avatar = "<img alt='{$alt}' src='{$a}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
	return apply_filters('begin_avatar', $avatar, $email, $size, $default, $alt);
}