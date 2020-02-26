<?php
  $menu_class = 'mobile-menu';
  if ( cao_compare_options( _cao( 'navbar_hidden', false ), rwmb_meta( 'navbar_hidden' ) ) == false ) {
    $menu_class .= ' hidden-lg hidden-xl';
  }
  $logo_regular = _cao( 'site_logo');
?>

<div class="off-canvas">
  <div class="canvas-close"><i class="mdi mdi-close"></i></div>
  <div class="logo-wrapper">
  <?php if ( ! empty( $logo_regular ) ) : ?>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <img class="logo regular" src="<?php echo esc_url( $logo_regular ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
    </a>
  <?php else : ?>
    <a class="logo text" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
  <?php endif; ?>
  </div>
  <div class="<?php echo esc_attr( $menu_class ); ?>"></div>
  <aside class="widget-area">
    <?php dynamic_sidebar( 'off_canvas' ); ?>
  </aside>
</div>