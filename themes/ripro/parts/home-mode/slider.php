<?php
$mode_slider = _cao('mode_slider');?>
<?php if ($mode_slider['diy_slider']) : ?>
<div class="section pt-0 pb-0">
 
    <div class="module slider big diy owl <?php echo esc_attr( $mode_slider['autoplay'] ? ' autoplay' : '' ); ?>">

    <?php foreach ($mode_slider['diy_data'] as $key => $item) {
        echo '<article class="post lazyload visible" data-bg="'.esc_url( $item['_img'] ).'">';
          echo '<div class="container">';
          echo '<h2 class="slider-title">'.$item['_title'].'</h2>';
          echo '<h3 class="slider-desc">'.$item['_desc'].'</h3>';
          echo '<a'.( $item['_blank'] ? ' target="_blank"' : '' ).' class="u-permalink" href="'.esc_url( $item['_href'] ).'"></a>';
          echo '</div>';
        echo '</article>';
    } ?>
    </div>
</div>
<?php else : ?>
  <?php 
    $args = array(
        'cat'       => $mode_slider['category'],
        'ignore_sticky_posts' => true,
        'post_status'         => 'publish',
        'posts_per_page'      => $mode_slider['count'],
        'offset'              => $mode_slider['offset'],
        'orderby'              => $mode_slider['orderby'],
    );
    $data = new WP_Query($args);
    if ($mode_slider['is_styles_rand']) {
        $mode_slider_style = mt_rand(0,1);
    }else{
        $mode_slider_style = $mode_slider['styles'];
    }
  ?>
  <?php if ($mode_slider_style == '1') : ?>
  <div class="section">
    <div class="container">
        <div class="module slider big owl nav-white<?php echo esc_attr( $mode_slider['autoplay'] ? ' autoplay' : '' ); ?>">
          <?php while ( $data->have_posts() ) : $data->the_post();
              $bg_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); 
              $bg_image_src = ($bg_image) ? $bg_image[0] : _the_theme_thumb_full();
              ?>
              <article <?php post_class( 'post lazyload visible' ); ?> data-bg="<?php echo esc_url( $bg_image_src ); ?>">
                <div class="entry-wrapper">
                  <?php cao_entry_header( array( 'tag' => 'h2', 'link' => false, 'white' => true, 'category' => true,'author'=>true ) ); ?>
                  <?php if ( $_get_excerpt = _get_excerpt(120) ) : ?>
                    <div class="entry-excerpt u-text-format">
                      <?php echo $_get_excerpt; ?>
                    </div>
                  <?php endif; ?>
                  <?php get_template_part( 'parts/entry-footer' ); ?>
                </div>
                <a<?php echo _target_blank();?> class="u-permalink" href="<?php echo esc_url( get_permalink() ); ?>"></a>
              </article>
            <?php endwhile; ?>
        </div>
    </div>
  </div>
  <?php else : ?>
  <div class="section bgcolor-fff pt-0">
      <div class="module slider center owl<?php echo esc_attr( $mode_slider['autoplay'] ? ' autoplay' : '' ); ?>">

        <?php while ( $data->have_posts() ) : $data->the_post();
            $bg_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); 
            $bg_image_src = ($bg_image) ? $bg_image[0] : _the_theme_thumb_full();
            ?>
            <article <?php post_class( 'post lazyload visible' ); ?> data-bg="<?php echo esc_url( $bg_image_src ); ?>">
              <div class="entry-wrapper">
                <?php cao_entry_header( array( 'tag' => 'h2', 'link' => false, 'white' => true, 'category' => true ,'author'=>true) ); ?>
                <?php if ( $_get_excerpt = _get_excerpt(120) ) : ?>
                  <div class="entry-excerpt u-text-format">
                    <?php echo $_get_excerpt; ?>
                  </div>
                <?php endif; ?>
                <?php get_template_part( 'parts/entry-footer' ); ?>
              </div>
              <a<?php echo _target_blank();?> class="u-permalink" href="<?php echo esc_url( get_permalink() ); ?>"></a>
            </article>
          <?php endwhile; ?>
        
      </div>
  </div>
  <?php endif;?>
<?php endif; ?>

<?php
wp_reset_postdata();