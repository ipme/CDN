<?php
// 文章信息
function begin_meta_tts() {
	if ($words <= 1800):
	echo '<span class="tts-play">';
	echo '<a class="tts-button" href="javascript:playPause();" title=" ' . sprintf(__( '朗读文章', 'begin' )) . '"><i class="be be-volumedown"></i><span></span><span></span><span></span></a>';
	echo '</span>';
	endif;
}

function begin_entry_meta() {
	if ( ! is_single() ) :
		if (zm_get_option('meta_author')) {
			if (zm_get_option('author_hide')) {echo '<span class="meta-author author-hide">';} else {echo '<span class="meta-author">';}
			echo '<span class="meta-author-avatar">';
			if (zm_get_option('cache_avatar')) {
				echo begin_avatar( get_the_author_meta('email'), '64' );
			} else {
				echo get_avatar( get_the_author_meta('email'), '64' );
			}
			echo '</span>';
			author_inf();
		echo '</span>';
		}
	echo '<span class="date">';
		time_ago( $time_type ='post' );
	echo '</span>';
	if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); }
	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c"></i> ' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		echo '<span class="comment">';
			comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble"></i> ' . sprintf(__( '评论', 'begin' )) . '</span>', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
		echo '</span>';
	}
 	else :

	echo '<ul class="single-meta">';
		edit_post_link('<i class="be be-editor"></i>', '<li class="edit-link">', '</li>' );
		if (zm_get_option('print_on')) {
			echo '<li class="print"><a href="javascript:printme()" target="_self" title="' . sprintf(__( '打印', 'begin' )) . '"><i class="be be-print"></i></a></li>';
		}
		if ( post_password_required() ) { 
			echo '<li class="comment"><a href="#comments">' . sprintf(__( '密码保护', 'begin' )) . '</a></li>';
		} else {
			echo '<li class="comment">';
				comments_popup_link( '<i class="be be-speechbubble"></i> ' . sprintf(__( '评论', 'begin' )) . '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
			echo '</li>';
		}

		if (zm_get_option('baidu_record')) {baidu_record_t();}

		if( function_exists( 'the_views' ) ) { the_views(true, '<li class="views"><i class="be be-eye"></i> ','</li>');  }
		echo '<li class="r-hide"><span class="off-side"></span></li>';
	echo '</ul>';

	echo '<div class="single-cat-tag">';
		echo '<div class="single-cat"><i class="be be-sort"></i>';
			the_category( ' ' );
		echo '</div>';
	echo '</div>';

	endif;
}

// 日志信息
function begin_format_meta() {
	if (zm_get_option('meta_author')) {
		echo '<span class="meta-author">';
			echo '<span class="meta-author-avatar">';
			if (zm_get_option('cache_avatar')) {
				echo begin_avatar( get_the_author_meta('email'), '64' );
			} else {
				echo get_avatar( get_the_author_meta('email'), '64' );
			}
			echo '</span>';
			author_inf();
		echo '</span>';
	}
	echo '<span class="date">';
		time_ago( $time_type ='post' );
	echo '</span>';
	echo '<span class="format-cat"><i class="be be-folder"></i> ';
		zm_category();
	echo '</span>';
	if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); }
	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c"></i> ' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		echo '<span class="comment">';
			comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble"></i> ' . sprintf(__( '评论', 'begin' )) . '</span>', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
		echo '</span>';
	}
}

function begin_single_meta() {
	echo '<div class="begin-single-meta">';
		if (zm_get_option('meta_author_single')) {
			echo '<span class="meta-author">';
				echo '<span class="meta-author-avatar">';
				if (zm_get_option('cache_avatar')) {
					echo begin_avatar( get_the_author_meta('email'), '64' );
				} else {
					echo get_avatar( get_the_author_meta('email'), '64' );
				}
				echo '</span>';
				author_inf();
			echo '</span>';
		}

		echo '<span class="my-date"><i class="be be-schedule"></i> ';
		time_ago( $time_type ='posts' );
		echo '</span>';

		global $wpdb, $post;
		$from = get_post_meta($post->ID, 'from', true);
		$copyright = get_post_meta($post->ID, 'copyright', true);
		if ( get_post_meta($post->ID, 'from', true) ) :
			echo '<span class="meta-source">';
			echo sprintf(__( '来源：', 'begin' ));
			if ( get_post_meta($post->ID, 'copyright', true) ) :
				echo '<a href="';
				echo $copyright;
				echo '" rel="nofollow" target="_blank">';
				echo $from;
				echo '</a>';
			else:
				echo $from;
			endif;
			echo '</span>';
		endif;

		if ( post_password_required() ) { 
			echo '<span class="comment"><a href="#comments">' . sprintf(__( '密码保护', 'begin' )) . '</a></li>';
		} else {
			echo '<span class="comment">';
				comments_popup_link( '<i class="be be-speechbubble"></i> ' . sprintf(__( '评论', 'begin' )) . '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
			echo '</span>';
		}

		if (zm_get_option('baidu_record')) {baidu_record_b();}

		if( function_exists( 'the_views' ) ) { the_views(true, '<span class="views"><i class="be be-eye"></i> ','</span>');  }
		if (zm_get_option('print_on')) {
			echo '<span class="print"><a href="javascript:printme()" target="_self" title="' . sprintf(__( '打印', 'begin' )) . '"><i class="be be-print"></i></a></span>';
		}
		if (zm_get_option('word_time') && wp_is_mobile()) {} else {
			echo '<span class="word-time">';
				if (zm_get_option('word_count')) {echo count_words ($text);}
				if (zm_get_option('reading_time')) {reading_time();}
			echo '</span>';
		}
		edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link">', '</span>' );
		if (zm_get_option('tts_play') && !get_post_meta($post->ID, 'not_tts', true)) {begin_meta_tts();}
		echo '<span class="s-hide"><span class="off-side"></span></span>';
	echo '</div>';
}

function begin_single_cat() {
	global $wpdb, $post;
	if ( get_post_meta($post->ID, 'header_img', true) || get_post_meta($post->ID, 'header_bg', true) ):
	else:
	endif;
	echo '<div class="single-cat-tag">';
		echo '<div class="single-cat"><i class="be be-sort"></i>';
			the_category( ' ' );
		echo '</div>';
	echo '</div>';
}

// 页面信息
function begin_page_meta() {
	echo '<ul class="single-meta">';
		if (zm_get_option('word_time') && wp_is_mobile()) {} else {
			echo '<span class="word-time">';
				if (zm_get_option('word_count')) {echo count_words ($text);}
				if (zm_get_option('reading_time')) {reading_time();}
			echo '</span>';
		}
		edit_post_link('<i class="be be-editor"></i>', '<li class="edit-link">', '</li>' );
		begin_meta_tts();
		if (zm_get_option('print_on')) {
			echo '<li class="print"><a href="javascript:printme()" target="_self" title="' . sprintf(__( '打印', 'begin' )) . '"><i class="be be-print"></i></a></li>';
		}
		echo '<li class="comment">';
		comments_popup_link( '<i class="be be-speechbubble"></i> ' . sprintf(__( '评论', 'begin' )) . '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
		echo '</li>';
		if( function_exists( 'the_views' ) ) { the_views(true, '<li class="views"><i class="be be-eye"></i> ','</li>');  }
		echo '<li class="r-hide"><span class="off-side"></span></li>';
	echo '</ul>';
}

// 专题
function begin_page_meta_zt() {
	echo '<div class="single-meta-zt begin-single-meta">';
		echo '<span class="my-date"><i class="be be-schedule"></i> ';
		time_ago( $time_type ='pages' );
		echo '</span>';
		
		echo '<span class="comment">';
		comments_popup_link( '<i class="be be-speechbubble"></i> ' . sprintf(__( '评论', 'begin' )) . '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
		echo '</span>';
		if( function_exists( 'the_views' ) ) { the_views(true, '<span class="views"><i class="be be-eye"></i> ','</span>');  }
		edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link">', '</span>' );
	echo '</div>';
}

// 其它信息
function begin_grid_meta() {
	echo '<span class="date">';
		the_time( 'm/d' ); 
	echo '</span>';
	if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); }
	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c"></i> ' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		echo '<span class="comment">';
			comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble"></i> ' . sprintf(__( '评论', 'begin' )) . '</span>', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
		echo '</span>';
	}
}

// 时间
if (zm_get_option('meta_time')) {
function time_ago( $time_type ){
	switch( $time_type ){
		case 'comment': //评论时间
			printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time());
			break;
		case 'post'; //日志时间
			echo get_the_date();
			break;
		case 'posts'; //日志时间年
			echo get_the_date();
			if (zm_get_option('meta_time_second')) {
				echo '<i class="i-time">' . get_the_time('H:i:s') . '</i>';
			}
			break;
		case 'pages'; //年
			echo get_the_date();
			break;
	}
}
} else { 
function time_ago( $time_type ){
	switch( $time_type ){
		case 'comment': //评论时间
			$time_diff = current_time('timestamp') - get_comment_time('U');
			if( $time_diff <= 300 )
				echo ('刚刚');
			elseif(  $time_diff>=300 && $time_diff <= 86400 ) //24 小时之内
				echo human_time_diff(get_comment_time('U'), current_time('timestamp')).'前';
			else
				printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time());
			break;
		case 'post'; //日志时间
			$time_diff = current_time('timestamp') - get_the_time('U');
			if( $time_diff <= 300 )
				echo ('刚刚');
			elseif(  $time_diff>=300 && $time_diff <= 86400 ) //24 小时之内
				echo human_time_diff(get_the_time('U'), current_time('timestamp')).'前';
			else
				echo the_time( 'm月d日' );
			break;
		case 'posts'; //日志时间年
			//$time_diff = current_time('timestamp') - get_the_time('U');
			//if( $time_diff <= 300 )
				//echo ('刚刚');
			//elseif(  $time_diff>=300 && $time_diff <= 86400 ) //24 小时之内
				//echo human_time_diff(get_the_time('U'), current_time('timestamp')).'前';
			//else
				echo get_the_date();
				if (zm_get_option('meta_time_second')) {
					echo '<i class="i-time">' . get_the_time('H:i:s') . '</i>';
				}
			break;
		case 'pages'; //年
			echo get_the_date();
			break;
	}
}
}