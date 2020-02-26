<article id="post-<?php the_ID(); ?>" class="article-content">
  <?php get_template_part( 'parts/video-box' );?>
  <?php get_template_part( 'parts/single-top' ); ?>
  <div class="container">
    <div class="entry-wrapper">
      <?php  _the_cao_ads('ad_post_header', 'single-header'); ?>
      <div class="entry-content u-text-format u-clearfix">
        <?php the_content(); ?>
      </div>
      <div id="pay-single-box"></div>
      <?php
          wp_link_pages(array('before' => '<div class="fenye">分页阅读：', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '上一页', 'nextpagelink' => "")); ?> <?php wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); ?> <?php wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => "下一页"));
        get_template_part( 'parts/entry-tags' );
        if( _cao('post_copyright_s') ){
          get_template_part( 'parts/entry-cop' );
        }
        _the_cao_ads('ad_post_footer', 'single-footer');
        get_template_part( 'parts/author-box' );
      ?>
    </div>
  </div>
</article>

<?php get_template_part( 'parts/entry-navigation' );?>

<?php 
if ( comments_open() || get_comments_number() ) :
  comments_template();
endif;
?>