<?php
// 自动缩略图
function ygj_thumbnail($sltw, $slth) {
    global $post;
    if ( get_post_meta($post->ID, 'wzshow', true) ) {
    	$image = get_post_meta($post->ID, 'wzshow', true);
		echo '<a href="'.get_permalink().'"><img src="'.get_template_directory_uri().'/timthumb.php?src='.$image.'&w='.$sltw.'&h='.$slth.'&zc=1" alt="'.$post->post_title .'" /></a>';
    } else {
	        $content = $post->post_content;
	        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
	        $n = count($strResult[1]);
	        if($n > 0){
				echo '<a href="'.get_permalink().'"><img src="'.get_template_directory_uri().'/timthumb.php?src='.$strResult[1][0].'&w='.$sltw.'&h='.$slth.'&zc=1" alt="'.$post->post_title .'" /></a>';
	        } else { 
				$random = mt_rand(1, 10);
				echo '<a href="'.get_permalink().'"><img src="'.get_template_directory_uri().'/timthumb.php?src='.get_template_directory_uri().'/images/random/'. $random .'.jpg&w='.$sltw.'&h='.$slth.'&zc=1" alt="'.$post->post_title .'" /></a>';
	        }
	}
}

function ygj_thumbnailnolink($sltww, $slthh) {
    global $post;
    if ( get_post_meta($post->ID, 'wzshow', true) ) {
    	$image = get_post_meta($post->ID, 'wzshow', true);
		echo '<img src="'.get_template_directory_uri().'/timthumb.php?src='.$image.'&w='.$sltww.'&h='.$slthh.'&zc=1" alt="'.$post->post_title .'" />';
    } else {
	        $content = $post->post_content;
	        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
	        $n = count($strResult[1]);
	        if($n > 0){
				echo '<img src="'.get_template_directory_uri().'/timthumb.php?src='.$strResult[1][0].'&w='.$sltww.'&h='.$slthh.'&zc=1" alt="'.$post->post_title .'" />';
	        } else { 
				$random = mt_rand(1, 10);
				echo '<img src="'.get_template_directory_uri().'/timthumb.php?src='.get_template_directory_uri().'/images/random/'. $random .'.jpg&w='.$sltww.'&h='.$slthh.'&zc=1" alt="'.$post->post_title .'" />';
	        }
	}
}

function ygj_thumbnailnolinkwz($sltww, $slthh) {
    global $post;
    if ( get_post_meta($post->ID, 'wzshow', true) ) {
    	$image = get_post_meta($post->ID, 'wzshow', true);
		return '<img src="'.get_template_directory_uri().'/timthumb.php?src='.$image.'&w='.$sltww.'&h='.$slthh.'&zc=1" alt="'.$post->post_title .'" />';
    } else {
	        $content = $post->post_content;
	        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
	        $n = count($strResult[1]);
	        if($n > 0){
				return '<img src="'.get_template_directory_uri().'/timthumb.php?src='.$strResult[1][0].'&w='.$sltww.'&h='.$slthh.'&zc=1" alt="'.$post->post_title .'" />';
	        } else { 
				$random = mt_rand(1, 10);
				return '<img src="'.get_template_directory_uri().'/timthumb.php?src='.get_template_directory_uri().'/images/random/'. $random .'.jpg&w='.$sltww.'&h='.$slthh.'&zc=1" alt="'.$post->post_title .'" />';
	        }
	}
}