<?php
$type = 'tag';
$terms = get_the_tags();

if ( ! $terms ) {
  $terms = get_the_category();
  $type = 'category';
}

if ( $terms && _cao( 'disable_related_posts') == 1 ) {
  $args = array(
    'orderby' => 'rand',
    'post__not_in' => array( get_the_ID() ),
    'posts_per_page' => 4,
  );

  $term_ids = array();

  foreach ( $terms as $term ) {
    $term_ids[] = $term->term_id;
  }

  switch ( $type ) {
    case 'tag' :
      $args['tag__in'] = $term_ids;
      break;
    case 'category' :
      $args['category__in'] = $term_ids;
      break;
  }

  $related_posts = new WP_Query( $args );

  if ( $related_posts->have_posts() ) : ?>
    <div class="bottom-area bgcolor-fff">
      <div class="container">
        <div class="related-posts">
          <h3 class="u-border-title">相关推荐</h3>
          <div class="row">
            <?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
              <div class="col-lg-6">
                <article class="post">
                  <?php cao_entry_media( array( 'layout' => 'rect_300' ) ); ?>
                  <div class="entry-wrapper">
                    <?php cao_entry_header( array( 'tag' => 'h4' ,'author'=>true) ); ?>
                    <div class="entry-excerpt u-text-format">
                      <?php echo _get_excerpt($limit = 55, $after = '...'); ?>
                    </div>
                    <?php get_template_part( 'parts/entry-footer' ); ?>
                  </div>
                </article>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif;

  wp_reset_postdata();
}