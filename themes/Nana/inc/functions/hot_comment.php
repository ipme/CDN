<?php
//按时间获得评论数最多的文章
function hot_comment_viewed($number, $days){global $wpdb, $post; 
$limit_date = current_time('timestamp') - ($days*26400);
if($limit_date < 0){$limit_date=0;}
$limit_date = date("Y-m-d H:i:s",$limit_date);     $sql = "SELECT *           FROM $wpdb->posts           WHERE post_type = 'post' AND post_status = 'publish' AND post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."'           ORDER BY comment_count DESC LIMIT 0 , $number ";    $posts = $wpdb->get_results($sql);    $output = "";	    foreach ($posts as $post){
	if ( get_post_meta($post->ID, 'wzshow', true) ) {
    	$image = get_post_meta($post->ID, 'wzshow', true);
		$first_img="".get_template_directory_uri()."/timthumb.php?src=".$image."&w=75&h=75&zc=1";
    } else {
	        $content = $post->post_content;
	        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
	        $n = count($strResult[1]);
	        if($n > 0){
				$first_img="".get_template_directory_uri()."/timthumb.php?src=".$strResult[1][0]."&w=75&h=75&zc=1";
	        } else { 
				$random = mt_rand(1, 10);
				$first_img="".get_template_directory_uri()."/timthumb.php?src=".get_template_directory_uri()."/images/random/". $random .".jpg&w=75&h=75&zc=1";
	        }
	}
	$post_cbtimes=get_the_date('Y/m/d');        $output .= "<a class=\"top_post_item ping_post\" href= \"".get_permalink($post->ID)."\" title=\"".$post->post_title." (".$post->comment_count."条评论)\" style=\"display: block;\"  target=\"_blank\"><img src=\"".$first_img."\"  alt=\"".get_the_title()."\"><div class=\"news-inner\"><p>".$post->post_title."</p><span class=\"views\">评论 ".$post->comment_count."</span><span class=\"comment\">".$post_cbtimes."</span></div><div class=\"clear\"></div></a>";    }    echo $output;}