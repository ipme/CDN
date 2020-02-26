<?php
function pagenavi( $before = '', $after = '', $p = 3 ) {
	if ( is_singular() ) return;
	global $wp_query, $paged;
	$max_page = $wp_query->max_num_pages;
	if ( $max_page == 1 ) return;
	if ( empty( $paged ) ) $paged = 1;
	echo $before.'<nav class="navigation pagination" role="navigation"><div class="nav-links">'."\n";
	if ( $paged > 1 ) {echo '<span class="prev">'; p_link( $paged - 1, '上页', '<i class="fa fa-angle-left"></i>' );echo '</span>';}
	if ( $paged > $p + 1 ) p_link( 1, '第一页' );
	if ( $paged > $p + 2 ) echo '<span class="page-numbers dots">...</span>';
	for( $i = $paged - $p; $i <= $paged + $p; $i++ ) {
		if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<span class='page-numbers current'><span class='pages'>第</span><input class='current-page-selector' id='current-page-selector' type='text' max='{$max_page}' size='2' name='paged_selectors' value='{$i}'><span class='pagesym'>{$i}</span><span class='pages'>页</span></span>" : p_link( $i );
	}
	if ( $paged < $max_page - $p - 1 ) echo '<span class="page-numbers dots">...</span>';
	if ( $paged < $max_page - $p ) p_link( $max_page, '最后一页' );
	if ( $paged < $max_page ) {echo '<span class="next">'; p_link( $paged + 1,'下页', '<i class="fa fa-angle-right"></i>' );echo '</span>';}
	echo '</div></nav>'.$after."\n";
}
function p_link( $i, $title = '', $linktype = '' ) {
	if ( $title == '' ) $title = "第 {$i} 页";
	if ( $linktype == '' ) { $linktext = $i; } else { $linktext = $linktype; }
	echo "<a class='page-numbers' href='", esc_html( get_pagenum_link( $i ) ), "' title='{$title}'>{$linktext}</a>";
}
?>