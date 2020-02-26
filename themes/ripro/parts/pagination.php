<?php
  $pagination = _cao( 'pagination', 'infinite_button' );

  switch ( $pagination ) {
  	case 'numeric' :
      global $wp_query;

      $total = $wp_query->max_num_pages;
      $big = 999999999;
      
      if ( $total > 1 ) {
        if ( ! $current_page = get_query_var( 'paged' ) ) {
          $current_page = 1;
        }
      
        if ( get_option( 'permalink_structure' ) ) {
          $format = 'page/%#%/';
        } else {
          $format = '&paged=%#%';
        }
      
        echo '<div class="numeric-pagination">';
        echo paginate_links( array(
          'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
          'format'    => $format,
          'current'   => max( 1, get_query_var( 'paged' ) ),
          'total'     => $total,
          'mid_size'  => 3,
          'type'      => 'list',
          'prev_text' => '<i class="mdi mdi-chevron-left"></i>',
          'next_text' => '<i class="mdi mdi-chevron-right"></i>',
        ) );
        echo '</div>';
      }
      break;

    default :
      the_posts_navigation(
        array('prev_text' => '下一页', 'next_text' => '上一页')
      );
  }
?>

<?php if ( strpos( $pagination, 'infinite' ) !== false ) : ?>
  <div class="infinite-scroll-status">
    <div class="infinite-scroll-request"></div>
  </div>
  <div class="infinite-scroll-action">
    <div class="infinite-scroll-button button">加载更多</div>
  </div>
<?php endif; ?>