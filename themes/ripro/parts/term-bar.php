<?php
if (_cao('is_fancybox_img_one','0')) {
  $image = _cao('deft_term_bar_img');
}else{
  $image = (get_the_post_thumbnail_url()) ? get_the_post_thumbnail_url() : _cao('deft_term_bar_img');
}
?>
<div class="term-bar lazyload visible" data-bg="<?php echo esc_url( $image ); ?>">
  <?php 
    if ( is_archive() ) {
      the_archive_title( '<h1 class="term-title">', '</h1>' );
    } elseif ( is_search() ) {
    	$titles= (get_search_query()) ? get_search_query() : '自定义筛选' ;
      echo '<h1 class="term-title">' . sprintf( esc_html__( '搜索：%s', 'cao' ), $titles ) . '</h1>';
    }elseif ( is_page() ) {
      echo '<h1 class="term-title">' .trim(wp_title('', false)). '</h1>';
    }
  ?>
</div>