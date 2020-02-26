<div id="searchbartag" class="plxiaoshi">
	<ul id="alert_box_tags">
	<?php $posttags = get_tags(array('number' => 15, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true));  
if($posttags) {  
foreach($posttags as $tag) {  
echo '<li class="alert_box_tags_item"><a href="' . get_tag_link( $tag ) . '" title="' . $tag->name . '有' . $tag->count . '篇文章" rel="tag" target="_blank">' . $tag->name . '</a> </li>';    
}  
}  
?>
				<div class="clear"></div>
            </ul>
			<ul id="alert_box_more">
                <li class="alert_box_more_left"></li>
                <p class="alert_box_more_main"><a href="<?php echo stripslashes(get_option('ygj_tagurl')); ?>"  target="_blank">查看更多热门标签</a></p>
                <li class="alert_box_more_right"></li>
                <div class="clear"></div>
            </ul>
</div>	