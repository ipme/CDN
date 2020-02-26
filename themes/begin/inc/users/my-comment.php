<ul class="my-comment">
	<?php global $current_user; wp_get_current_user();?>
	<?php
		global $wpdb;
		$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url,comment_author_email, SUBSTRING(comment_content,1,62) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' AND user_id='$current_user->ID' ORDER BY comment_date_gmt DESC LIMIT 20";
		$comments = $wpdb->get_results($sql);
		$output = $pre_HTML;
		foreach ($comments as $comment) {$output .= "\n<li>". " <a href=\"" . get_permalink($comment->ID) ."#comment-" . $comment->comment_ID . "\" title=\"" .$comment->post_title . "\">" . strip_tags($comment->com_excerpt)."</a></li>";}
		$output .= $post_HTML;
		echo $output;
	?> 
</ul>