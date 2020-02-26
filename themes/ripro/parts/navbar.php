<?php
  global $current_user;
  $container = _cao( 'navbar_full', false );
  $menu_class = 'main-menu hidden-xs hidden-sm hidden-md';
  if ( cao_compare_options( _cao( 'navbar_hidden', false ), rwmb_meta( 'navbar_hidden' ) ) == true ) {
    $menu_class .= ' hidden-lg hidden-xl';
  }
  $logo_regular = _cao( 'site_logo');
  $logo_regular_dark = _cao( 'site_dark_logo');
  
?>

<header class="site-header">
  <?php if ( $container == false ) : ?>
    <div class="container">
  <?php endif; ?>
    <div class="navbar">
      <div class="logo-wrapper">
      <?php if ( ! empty( $logo_regular ) ) : ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <img class="logo regular tap-logo" src="<?php echo esc_url( $logo_regular ); ?>" data-dark="<?php echo esc_url(_cao( 'site_dark_logo')); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
        </a>
      <?php else : ?>
        <a class="logo text" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
      <?php endif; ?>
      </div>
      <div class="sep"></div>
      
      <nav class="<?php echo esc_attr( $menu_class ); ?>">
        <?php wp_nav_menu( array(
          'container' => false,
          'fallback_cb' => 'Cao_Walker_Nav_Menu::fallback',
          'menu_class' => 'nav-list u-plain-list',
          'theme_location' => 'menu-1',
          'walker' => new Cao_Walker_Nav_Menu( true ),
        ) ); ?>
      </nav>
      
      <div class="main-search">
        <?php get_search_form(); ?>
        <div class="search-close navbar-button"><i class="mdi mdi-close"></i></div>
      </div>

      <div class="actions">
        <?php if (is_site_shop_open()) : ?>
          <!-- user -->
          <?php if (is_user_logged_in()) : ?>
            <?php if (_cao('is_navbar_newhover','1')) { 
              get_template_part( 'parts/navbar-hover' );
            }else{ ?>
              <a class="user-pbtn" href="<?php echo esc_url(home_url('/user')) ?>"><?php echo get_avatar($current_user->user_email); ?>
              <?php if(!_cao('is_navbar_ava_name','0')){
                echo '<span>'.$current_user->display_name.'</span>';
              }?>
              </a>
            <?php } ?>
            
          <?php else: ?>
              <div class="login-btn navbar-button"><i class="mdi mdi-account"></i> 登录</div>
          <?php endif; ?>
        <?php endif; ?>
        <!-- user end -->
        <div class="search-open navbar-button"><i class="mdi mdi-magnify"></i></div>
        <?php if (_cao('is_ripro_dark_btn')) : ?>
        <div class="tap-dark navbar-button"><i class="mdi mdi-brightness-4"></i></div>
        <?php endif; ?>
        <div class="burger"></div>
      </div>
    </div>
  <?php if ( $container == false ) : ?>
    </div>
  <?php endif; ?>
</header>

<div class="header-gap"></div>