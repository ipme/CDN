<?php
$sidebar = 'none';
$column_classes = cao_column_classes( $sidebar );

$mo_postlist_no_cat = _cao('home_last_post');
if(!empty($mo_postlist_no_cat['home_postlist_no_cat'])){
  $args['cat'] = '-'.implode($mo_postlist_no_cat['home_postlist_no_cat'], ',-');
}
$args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 0;

query_posts($args);


?>
<div class="section">
  <div class="container">
    <div class="row">
      <div class="<?php echo esc_attr( $column_classes[0] ); ?>">
        <div class="content-area">
          <main class="site-main">
            <?php if ( have_posts() ) : ?>
              <?php if ( is_home() ) : ?>
                <!-- <h1 class="latest-title">最新文章</h1> -->
                <h3 class="section-title"><span><i class="fa fa-list-alt"></i> 最新文章</span></h3>
              <?php _the_cao_ads('ad_list_header', 'list-header'); endif; ?>
              <div class="row posts-wrapper">
                <?php while ( have_posts() ) : the_post();
                  get_template_part( 'parts/template-parts/content', _cao( 'latest_layout', 'list' ) );
                endwhile; ?>
              </div>
              <?php _the_cao_ads('ad_list_footer', 'list-footer');?>
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
</div>

<?php
wp_reset_postdata();