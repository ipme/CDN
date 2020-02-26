<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div id="all-cat-grid" class="all-cat-grid all-cat ms wow fadeInUp ms" data-wow-delay="0.3s">
	<ul class="grid-cat-all">
		<?php
			$args = array(
				'exclude' => explode(',',zm_get_option('cat_all_e')),
				'hide_empty' => 0
			);
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
			query_posts( 'cat=' . $cat->cat_ID );
		?>
		<li class="list-cat"><a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><?php single_cat_title(); ?></a></li>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(document).ready(function(){
function topmenu() {
	var totalWidth = $(".grid-cat-all").width();
	var topmenuR = $(".all-cat-grid").offset().left + totalWidth;
	var $list = $(".list-cat");
	var drowMenu = '';
	var more = "";
	var listD = '';
	for (var i = 0; i < $list.length; i++) {
		var liWidth = $($list[i]).width();
		var liR = $($list[i]).offset().left + liWidth;
		if (liR > topmenuR) {
			drowMenu += '<ul id="more-list" class="more-list">'
			for (var j = i; j < $list.length; j++) {
				listD += '<li class="list-cat">' + $($list[j]).html() + '</li>';
				$($list[j]).remove();
			}
			drowMenu += listD + '</ul>'
			more = '<li><a id="more-cat" class="more-cat"><p class="all-cat-ico"></p></a></li>';
			$(".grid-cat-all").append(more);
			$("#more-cat").append(drowMenu);
			break;
		}
	}
}
topmenu();
window.onresize = function() {
	var appendL = $("#more-list").html();
	$("#more-cat").remove();
	$(".grid-cat-all").append(appendL);
	topmenu();
}
});
</script>