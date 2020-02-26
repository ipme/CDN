<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( is_tax('gallery') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'gallery' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'gallery' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( is_tax('videos') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'videos' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'videos' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( is_tax('taobao') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'taobao' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'taobao' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( is_tax('notice') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'notice' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'notice' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( 'tao' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'taobao', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'taobao' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'taobao' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	if (wp_is_mobile()) {
		echo '' . sprintf(__( '正文', 'begin' )) . '';
	} else {
		echo '' . the_title() . '';
	}
}

if ( 'picture' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'gallery', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'gallery' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'gallery' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	if (wp_is_mobile()) {
		echo '' . sprintf(__( '正文', 'begin' )) . '';
	} else {
		echo '' . the_title() . '';
	}
}

if ( 'video' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'videos', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'videos' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'videos' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	if (wp_is_mobile()) {
		echo '' . sprintf(__( '正文', 'begin' )) . '';
	} else {
		echo '' . the_title() . '';
	}
}

if ( 'bulletin' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'notice', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'notice' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'notice' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	if (wp_is_mobile()) {
		echo '' . sprintf(__( '正文', 'begin' )) . '';
	} else {
		echo '' . the_title() . '';
	}
}

// woo
if ( is_tax('product_cat') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'product_cat' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'product_cat' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}


if ( 'product' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'product_cat' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . get_the_title() . $after;
}

if ( is_tax('product_tag') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'product_tag' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'product_tag' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

// edd
if ( is_tax('download_category') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'download_category' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'download_category' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( 'download' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'download_category', array( 'orderby' => 'download', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'download_category' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . get_the_title() . $after;
}

if ( is_tax('download_tag') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'download_tag' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'download_tag' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( is_tax('favorites') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'favorites' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'favorites' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

// dwqa
function begin_dw_breadcrumb() {
	global $dwqa_general_settings;
	$title = get_the_title( $dwqa_general_settings['pages']['archive-question'] );
	$search = isset( $_GET['qs'] ) ? esc_html( $_GET['qs'] ) : false;
	$author = isset( $_GET['user'] ) ? esc_html( $_GET['user'] ) : false;
	$output = '';
	if ( !is_singular( 'dwqa-question' ) ) {
		$term = get_query_var( 'dwqa-question_category' ) ? get_query_var( 'dwqa-question_category' ) : ( get_query_var( 'dwqa-question_tag' ) ? get_query_var( 'dwqa-question_tag' ) : false );
		$term = get_term_by( 'slug', $term, get_query_var( 'taxonomy' ) );
		// $tax_name = 'dwqa-question_tag' == get_query_var( 'taxonomy' ) ? __( 'Tag', 'dwqa' ) : __( 'Category', 'dwqa' );
	} else {
		$term = wp_get_post_terms( get_the_ID(), 'dwqa-question_category' );
		if ( $term ) {
			$term = $term[0];
			// $tax_name = __( 'Category', 'dwqa' );
		}
	}

	if ( is_singular( 'dwqa-question' ) || $search || $author || $term ) {
		// $output .= '<div class="dwqa-breadcrumbs">';
	}

	if ( $term || is_singular( 'dwqa-question' ) || $search || $author ) {
		if ( $author ) {
			$output .= '<span class="dwqa-sep"><i class="be be-arrowright"></i></span>';
		}
		$output .= '<a href="'. get_permalink( $dwqa_general_settings['pages']['archive-question'] ) .'">' . $title . '</a>';
	}

	if ( $term ) {
		$output .= '<span class="dwqa-sep"><i class="be be-arrowright"></i></span>';
		if ( is_singular( 'dwqa-question' ) ) {
			$output .= '<a href="'. esc_url( get_term_link( $term, get_query_var( 'taxonomy' ) ) ) .'">' . $tax_name . '' . $term->name . '</a>';
		} else {
			$output .= '<span class="dwqa-current">' . $tax_name . '' . $term->name . '</span>';
		}
	}

	if ( $search ) {
		$output .= '<span class="dwqa-sep"><i class="be be-arrowright"></i></span>';
		$output .= sprintf( '<span class="dwqa-current">%s "%s"</span>', __( '搜索结果', 'begin' ), rawurldecode( $search ) );
	}

	if ( $author ) {
		$output .= '<span class="dwqa-sep"><i class="be be-arrowright"></i></span>';
		$output .= sprintf( '<span class="dwqa-current">%s "%s"</span>', __( '作者', 'begin' ), rawurldecode( $author ) );
	}

	if ( is_singular( 'dwqa-question' ) ) {
		$output .= '<span class="dwqa-sep"><i class="be be-arrowright"></i></span>';
		if ( !dwqa_is_edit() ) {
			$output .= '<span class="dwqa-current">' . get_the_title() . '</span>';
		} else {
			$output .= '<a href="'. get_permalink() .'">'. get_the_title() .'</a>';
			$output .= '<span class="dwqa-sep"><i class="be be-arrowright"></i></span>';
			$output .= '<span class="dwqa-current">'. __( 'Edit', 'dwqa' ) .'</span>';
		}
	}
	if ( is_singular( 'dwqa-question' ) || $search || $author || $term ) {
		// $output .= '</div>';
	}

	echo apply_filters( 'dwqa_breadcrumb', $output );
}

// show
if ( is_tax('products') ) {
	$current_term = $wp_query->get_queried_object();
	$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'products' ) );
	foreach ( $ancestors as $ancestor ) {
		$ancestor = get_term( $ancestor, 'products' );
		echo $before .  '<a href="' . get_term_link( $ancestor ) . '">' . esc_html( $ancestor->name ) . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	echo $before . esc_html( $current_term->name ) . $after;
}

if ( 'show' == get_post_type() && is_single() ) {
	if ( $terms = begin_taxonomy_terms( $post->ID, 'products', array( 'products' => 'parent', 'order' => 'DESC' ) ) ) {
		$main_term = $terms[0];
		$ancestors = get_ancestors( $main_term->term_id, 'products' );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'products' );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				echo $before . '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
			}
		}
		echo $before . '<a href="' . get_term_link( $main_term ) . '">' . $main_term->name . '</a><i class="be be-arrowright"></i>' . $after . $delimiter;
	}
	// echo $before . get_the_title() . $after;
	echo '' . the_title() . '';
}