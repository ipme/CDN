<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Cache-Control" content="no-transform">
<meta http-equiv="Cache-Control" content="no-siteapp">
<meta name="renderer" content="webkit">
<meta name="applicable-device" content="pc,mobile">
<meta name="HandheldFriendly" content="true"/>
<?php get_template_part( 'inc/functions/seo' ); ?>
<link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/images/favicon.ico">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo esc_url( get_template_directory_uri() ); ?>/images/favicon.png">
<link rel="profile" href="http://gmpg.org/xfn/11">
<!--[if lt IE 9]><script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5-css3.js"></script><![endif]-->
<link rel="stylesheet" id="nfgc-main-style-css" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="all">
<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/scrollmonitor.js"></script>
<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/flexisel.js"></script>
<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/stickySidebar.js"></script>
<?php if (is_home() ) { ?>
<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/wow.js"></script>
<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/slides.js"></script>
<?php } ?>
<?php if (get_option('ygj_bdtjdm')) { ?>
<?php echo stripslashes(get_option('ygj_bdtjdm')); ?>
<?php } ?>
<!--[if IE]>
<div class="tixing"><strong>温馨提示：感谢您访问本站，经检测您使用的浏览器为IE浏览器，为了获得更好的浏览体验，请使用Chrome、Firefox或其他浏览器。</strong>
</div>
<![endif]-->
<link rel="stylesheet" id="font-awesome-four-css" href="<?php echo esc_url( get_template_directory_uri() ); ?>/fonts/fontawesome-all.css" type='text/css' media='all'/>
<?php wp_head(); ?>

</head>
<body <?php if ( !is_author() ){ body_class();} ?>>
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header">
	<nav id="top-header">
		<div class="top-nav">
			<div id="user-profile">
				您好，欢迎访问<?php bloginfo('name'); ?>&nbsp;&nbsp;|&nbsp;<a href="<?php echo esc_url( home_url() ); ?>/wp-admin" target="_blank">登录</a>
			</div>	
		<?php wp_nav_menu( array( 'theme_location' => 'top-menu', 'menu_class' => 'top-menu', 'fallback_cb' => 'default_menu' ) ); ?>
		</div>
	</nav><!-- #top-header -->
	<div id="menu-box">
		<div id="top-menu">
			<?php get_template_part( 'inc/logo' ); ?>
			<span class="nav-search"><i class="fas fa-search"></i></span>
			<div id="site-nav-wrap">
				<div id="sidr-close"><a href="<?php echo esc_url( home_url() ); ?>/#sidr-close" class="toggle-sidr-close">X</a>
			</div>
			
			<nav id="site-nav" class="main-nav">
				<a href="#sidr-main" id="navigation-toggle" class="bars"><i class="fa fa-bars"></i></a>	
				<?php if ( wp_is_mobile() ) { ?>
				<?php wp_nav_menu( array( 'theme_location' => 'mini-menu','menu_class' => 'down-menu nav-menu', 'fallback_cb' => 'default_menu' ) ); ?>	
				<?php }else { ?>				
				<?php wp_nav_menu( array( 'theme_location' => 'header-menu','menu_class' => 'down-menu nav-menu', 'fallback_cb' => 'default_menu' ) ); ?>	
				<?php } ?>				
			</nav>	
			</div><!-- #site-nav-wrap -->
		</div><!-- #top-menu -->
	</div><!-- #menu-box -->
</header><!-- #masthead -->

<div id="main-search">
	<?php get_search_form(); ?>	
	<?php get_template_part('inc/search-tag'); ?>	
	<div class="clear"></div>
</div>
<?php if (is_home()) { ?>
<?php if (!get_option('ygj_ggl_kg')) {?>
<nav class="breadcrumb" style="height:1px;line-height:1px;"></nav>
<?php }else{?>
<nav class="breadcrumb">	
	<div id="scrolldiv">
		<div class="bull"></div>
		<div class="scrolltext">
			<ul style="margin-top: 0px;">
				<?php echo stripslashes(get_option('ygj_ggl_nr')); ?>
			</ul>
		</div>
	</div> 
	<script type="text/javascript">$(document).ready(function() {$("#scrolldiv").textSlider({line: 1, speed: 1000, timer: 3000});})</script>
</nav>
<?php } ?>
<?php }else{the_crumbs(); }?>