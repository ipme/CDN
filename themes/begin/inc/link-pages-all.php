<?php
class wpLinkPagesAll {
	public function __construct() {
		add_filter( 'wp_link_pages_args', array( $this, 'wp_link_pages_all_args' ) );
		add_filter( 'query_vars', array( $this, 'wp_link_pages_all_parameter_queryvars' ) );
		add_action( 'the_post', array( $this, 'wp_link_pages_all_the_post' ), 0 );
	}
	function wp_link_pages_all_parameter_queryvars( $queryvars ) {
		$queryvars[] = 'viewall';
		return( $queryvars );
	}
	function wp_link_pages_all_args( $default ) {
		global $page, $post, $numpages, $wp_query;
		$args = array(
					'next_or_number' => 'number',
					'pagelink' => '%',
					'echo' => 1,
					'before' => '',
					'after' => '',
					'link_before' => '<span class="next-page">',
					'link_after' => '</span>',
					'nextpagelink' => '<span class="all-page-link"><i class="be be-arrowright"></i></span>',
					'previouspagelink' => '<span class="all-page-link"><i class="be be-arrowleft"></i></span>',
					'firstpagelink' => '<span class="back-paging">' .__( '分页阅读', 'begin' ). ' </span>',
					'viewalllink' => '<span class="back-paging">' .__( '显示全文', 'begin' ). ' </span>',
				);
		if ( is_home() ) {
			return( array( 'echo' => 0 ) );
		} else
		if ( isset( $wp_query -> query_vars[ 'viewall' ] ) && ( 'true' === $wp_query -> query_vars[ 'viewall' ] ) ) {
			$numpages = substr_count( $post -> post_content, 'class="wp-link-pages-all"' ) + 1;
			$args[ 'before' ] .= '<div class="page-links all-count">';
			$args[ 'before' ] .= _wp_link_page( 1 ) . $args[ 'firstpagelink' ] . '</a>';
			$args[ 'after' ] .= '</div>';
			return( $args );
		} else
		if ( 1 < $numpages ) {
			$args[ 'before' ] .= '<div class="page-links">';
			if ( 1 === $page ) {
				$args[ 'before' ] .= $args[ 'previouspagelink' ];
			} else {
				$args[ 'before' ] .= _wp_link_page( $page - 1 ) . $args[ 'previouspagelink' ] . '</a>';
			}
			if ( $page < $numpages ) {
				$args[ 'after' ] .= _wp_link_page( $page + 1 ) . $args[ 'nextpagelink' ] . '</a>';
			} else {
				$args[ 'after' ] .= $args[ 'nextpagelink' ];
			}
			$args[ 'after' ] .= '<br/>';
			$args[ 'after' ] .= '<a href="' . get_pagenum_link( 1 ) . ( preg_match( '/\?/', get_pagenum_link( 1 ) ) ? '&' : '?' ) . 'viewall=true' . '">' . $args[ 'viewalllink' ] . '</a>';
			$args[ 'after' ] .= '</div>';
			return ( $args );
		} else {
			return( $default );
		}
	}
	function wp_link_pages_all_the_post( $post ) {
		global $pages, $multipage, $wp_query;
		if ( isset( $wp_query -> query_vars[ 'viewall' ] ) && ( 'true' === $wp_query -> query_vars[ 'viewall' ] ) ) {
			$multipage = true;
			$post -> post_content = str_replace( '<!--nextpage-->', '', $post -> post_content );
			$pages = array( $post -> post_content );
		}
	}
}
$wpLinkPagesAll = new wpLinkPagesAll;