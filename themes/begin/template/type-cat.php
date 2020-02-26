<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( is_tax('videos') ) {
	$terms = get_terms("videos");
}
if ( is_tax('gallery') ) {
	$terms = get_terms("gallery");
}
if ( is_tax('taobao') ) {
	$terms = get_terms("taobao");
}
$count = count($terms);
if ( $count > 0 ){
	echo '<div class="type-cat">';
	foreach ( $terms as $term ) {
		echo '<span class="lx7 wow fadeInUp" data-wow-delay="0.3s">';
		echo '<span><a href="' . get_term_link( $term ) . '" >' . $term->name . '</a></span>';
		echo '</span>';
	}
	echo '<div class="clear"></div></div>';
}
?>