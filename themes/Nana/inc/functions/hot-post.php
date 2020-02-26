<?php
//按时间获得浏览器最高的文章
function get_timespan_most_viewed($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*26400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		foreach ($most_viewed as $post) {
			$post_views = intval($post->views);
			$post_views = number_format($post_views);	if ( get_post_meta($post->ID, 'wzshow', true) ) {
    	$image = get_post_meta($post->ID, 'wzshow', true);
		$first_imgh="".get_template_directory_uri()."/timthumb.php?src=".$image."&w=75&h=75&zc=1";
    } else {
	        $content = $post->post_content;
	        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
	        $n = count($strResult[1]);
	        if($n > 0){
				$first_imgh="".get_template_directory_uri()."/timthumb.php?src=".$strResult[1][0]."&w=75&h=75&zc=1";
	        } else { 
				$random = mt_rand(1, 10);
				$first_imgh="".get_template_directory_uri()."/timthumb.php?src=".get_template_directory_uri()."/images/random/". $random .".jpg&w=75&h=75&zc=1";
	        }
	}
			$post_cbtime=get_the_time( 'Y/m/d');
			$temp .= "<a class=\"top_post_item men_post\" href=\"".get_permalink()."\" title=\"".get_the_title()."($post_views 人阅读)\" style=\"display: block;\"  target=\"_blank\"><img src=\"".$first_imgh."\" alt=\"".get_the_title()."\"><div class=\"news-inner\"><p>".get_the_title()."</p><span class=\"views\">阅读 ".$post_views."</span><span class=\"comment\">".$post_cbtime."</span></div><div class=\"clear\"></div></a>";
		}		
	} else {
		$temp = '<a>'.__('N/A', 'Nana').'</a>'."\n";
	}
	if($display) {
		echo $temp;
	} else {
		return $temp;
	}
}?>