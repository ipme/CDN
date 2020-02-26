<?php

get_header();
$tagName = single_tag_title('',false);
$tagObject = get_term_by('name',$tagName,'post_tag');
$tagID = $tagObject->term_id;

$the_style = get_term_meta($tagID, 'the_style', 'true');
$the_sidebar = (get_term_meta($tagID, 'is_sidebar', 'true')) ? 'right':'none';

$column_classes = cao_column_classes( $the_sidebar );
$latest_layout = ($the_style) ? $the_style : _cao( 'latest_layout', 'grid' ) ;

$the_style = ($the_style) ? 'list' : 'grid' ;
?>

  <div class="container">
    <?php if (!get_term_meta($tagID, 'is_filter', 'true')) {
      get_template_part( 'parts/filter-bar' );
    } ?>
    <div class="row">
      <div class="<?php echo esc_attr( $column_classes[0] ); ?>">
        <div class="content-area">
          <main class="site-main">
            <?php if ( have_posts() ) : ?>
              <div class="row posts-wrapper">
                <?php while ( have_posts() ) : the_post();
                  get_template_part( 'parts/template-parts/content', $latest_layout );
                endwhile; ?>
              </div>
              <?php get_template_part( 'parts/pagination' ); ?>
            <?php else : ?>
              <?php get_template_part( 'parts/template-parts/content', 'none' ); ?>
            <?php endif; ?>
          </main>
        </div>
      </div>
      <?php if ( $the_sidebar !='none' ) : ?>
              <div class="<?php echo esc_attr( $column_classes[1] ); ?>">
                  <?php get_sidebar(); ?>
              </div>
          <?php endif; ?>
    </div>
  </div>
<?php
wp_reset_postdata();

get_footer();
