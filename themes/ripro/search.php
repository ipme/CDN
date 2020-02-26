<?php
get_header();
$sidebar = cao_sidebar();
$column_classes = cao_column_classes( $sidebar );
?>
<div class="container">
  <?php if ( have_posts() ) : ?>
  <?php get_template_part( 'parts/filter-bar' ); ?>
  <?php endif; ?>
    <div class="row">
      <div class="<?php echo esc_attr( $column_classes[0] ); ?>">
        <div class="content-area">
          <main class="site-main">
            <?php if ( have_posts() ) : ?>
              <?php if ( is_home() ) : ?>
                <!-- <h1 class="latest-title">最新文章</h1> -->
                <h3 class="section-title"><span><i class="fa fa-list-alt"></i> 最新文章</span></h3>
              <?php endif; ?>
              <div class="row posts-wrapper">
                <?php while ( have_posts() ) : the_post();
                  get_template_part( 'parts/template-parts/content', _cao( 'latest_layout', 'list' ) );
                endwhile; ?>
              </div>
              <?php get_template_part( 'parts/pagination' ); ?>
            <?php else : ?>
              <?php get_template_part( 'parts/template-parts/content', 'none' ); ?>
            <?php endif; ?>
          </main>
        </div>
      </div>
      <?php if ( $sidebar != 'none' ) : ?>
              <div class="<?php echo esc_attr( $column_classes[1] ); ?>">
                  <?php get_sidebar(); ?>
              </div>
          <?php endif; ?>
    </div>
</div>

<?php
wp_reset_postdata();
get_footer();
