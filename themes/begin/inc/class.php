<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// 格子图标
function grid_md_cms() { ?>
<div class="grid-md<?php echo zm_get_option('grid_ico_cms_n'); ?> sort" name="<?php echo zm_get_option('grid_ico_cms_s'); ?>">
	<?php $posts = get_posts( array( 'post_type' => 'any', 'meta_key' => 'gw_title', 'numberposts' => '16') ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
		<?php 
			$gw_ico = get_post_meta($post->ID, 'gw_ico', true);
			$gw_img = get_post_meta($post->ID, 'gw_img', true);
			$gw_title = get_post_meta($post->ID, 'gw_title', true);
			$gw_content = get_post_meta($post->ID, 'gw_content', true);
			$gw_link = get_post_meta($post->ID, 'gw_link', true);
		?>
	<div class="gw-box<?php echo zm_get_option('grid_ico_cms_n'); ?>">
		<div class="gw-main sup gw-main-<?php if ( zm_get_option('cms_ico_b')) { ?>b<?php } ?>">
			<?php if ( get_post_meta($post->ID, 'gw_img', true) ) { ?><div class="gw-img"><img src="<?php echo get_template_directory_uri().'/prune.php?src='.$gw_img.'&w=300&h=300&zc=1'; ?>" alt="<?php echo $gw_title; ?>" /></div><?php } ?>
			<div class="gw-area">
				<?php if ( get_post_meta($post->ID, 'gw_ico', true) ) { ?><div class="gw-ico"><i class="<?php echo $gw_ico; ?>"></i></div><?php } ?>
				<?php if ( get_post_meta($post->ID, 'gw_link', true) ) { ?><a class="gw-link" href="<?php echo $gw_link; ?>" title="了解更多" rel="bookmark"><?php } ?>
					<h3 class="gw-title"><?php echo $gw_title; ?></h3>
				<?php if ( get_post_meta($post->ID, 'gw_link', true) ) { ?></a><?php } ?>
				<?php if ( get_post_meta($post->ID, 'gw_content', true) ) { ?><div class="gw-content"><?php echo $gw_content; ?></div><?php } ?>	
			</div>
		</div>
		
	</div>
	<?php endforeach; endif; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php }

function grid_md_group() { ?>
<div class="g-row <?php if (zm_get_option('bg_19')) { ?>g-line<?php } ?> sort" name="<?php echo zm_get_option('group_ico_s'); ?>">
	<div class="g-col">
		<div class="grid-md<?php echo zm_get_option('grid_ico_group_n'); ?>">
			<div class="group-title wow fadeInDown" data-wow-delay="0.5s">
				<?php if ( zm_get_option('group_ico_t') == '' ) { ?>
				<?php } else { ?>
					<h3><?php echo zm_get_option('group_ico_t'); ?></h3>
					<div class="separator"></div>
				<?php } ?>
				<div class="group-des"><?php echo zm_get_option('group_ico_des'); ?></div>
				<div class="clear"></div>
			</div>
			<?php $posts = get_posts( array( 'post_type' => 'any', 'meta_key' => 'gw_title', 'numberposts' => '16') ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
				<?php 
					$gw_ico = get_post_meta($post->ID, 'gw_ico', true);
					$gw_img = get_post_meta($post->ID, 'gw_img', true);
					$gw_title = get_post_meta($post->ID, 'gw_title', true);
					$gw_content = get_post_meta($post->ID, 'gw_content', true);
					$gw_link = get_post_meta($post->ID, 'gw_link', true);
				?>
			<div class="gw-box<?php echo zm_get_option('grid_ico_group_n'); ?>">
				<div class="gw-main sup gw-main-<?php if ( zm_get_option('group_ico_b')) { ?>b<?php } ?>">
					<?php if ( get_post_meta($post->ID, 'gw_img', true) ) { ?><div class="gw-img"><img src="<?php echo get_template_directory_uri().'/prune.php?src='.$gw_img.'&w=300&h=300&zc=1'; ?>" alt="<?php echo $gw_title; ?>" /></div><?php } ?>
					<div class="gw-area">
						<?php if ( get_post_meta($post->ID, 'gw_ico', true) ) { ?><div class="gw-ico"><i class="<?php echo $gw_ico; ?>"></i></div><?php } ?>
						<?php if ( get_post_meta($post->ID, 'gw_link', true) ) { ?><a class="gw-link" href="<?php echo $gw_link; ?>" title="了解更多" rel="bookmark"><?php } ?>
							<h3 class="gw-title"><?php echo $gw_title; ?></h3>
						<?php if ( get_post_meta($post->ID, 'gw_link', true) ) { ?></a><?php } ?>
						<?php if ( get_post_meta($post->ID, 'gw_content', true) ) { ?><div class="gw-content"><?php echo $gw_content; ?></div><?php } ?>
					</div>
				</div>
			</div>
			<?php endforeach; endif; ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php }

// menu
function menu_top() { ?>
<div class="nav-top">
	<?php if (zm_get_option('profile')) { ?>
		<?php get_template_part( 'inc/users/user-profile' ); ?>
	<?php } ?>

	<?php
		wp_nav_menu( array(
			'theme_location'	=> 'header',
			'menu_class'		=> 'top-menu',
			'fallback_cb'		=> 'default_top_menu'
		) );
	?>
</div>
<?php }

function small_logo() { ?>
<?php if ( !zm_get_option('logos') && zm_get_option('logo_small'))  { ?><span class="logo-small"><img src="<?php echo zm_get_option('logo_small_b'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></span><?php } ?>
<?php }

function site_description() { ?>
	<?php $description = get_bloginfo( 'description', 'display' ); if ( $description || is_customize_preview() ) : ?>
		<p class="site-description<?php if ( !zm_get_option('logos') && zm_get_option('logo_small'))  { ?> clear-small<?php } ?>"><?php echo $description; ?></p>
	<?php endif; ?>
<?php }

function menu_logo() { ?>
<?php
	if ( is_front_page() || is_home() ) : ?>
		<?php if (zm_get_option('logos')) { ?>
		<h1 class="site-title">
			<?php if ( zm_get_option('logo') ) { ?>
				<a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo zm_get_option('logo'); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" rel="home" /><?php small_logo(); ?><span class="site-name"><?php bloginfo( 'name' ); ?></span></a>
			<?php } ?>
		</h1>
		<?php } else { ?>
		<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php small_logo(); ?><?php bloginfo( 'name' ); ?></a></h1>
		<?php site_description(); ?>
	<?php } ?>
	<?php else : ?>
	<?php if (zm_get_option('logos')) { ?>
		<p class="site-title">
			<?php if ( zm_get_option('logo') ) { ?>
				<a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo zm_get_option('logo'); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" rel="home" /><?php small_logo(); ?><span class="site-name"><?php bloginfo( 'name' ); ?></span></a>
			<?php } ?>
		</p>
	<?php } else { ?>
		<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php small_logo(); ?><?php bloginfo( 'name' ); ?></a></p>
		<?php site_description(); ?>
	<?php } ?>
<?php endif; ?>
<?php }

function menu_nav() { ?>
<?php if (zm_get_option('nav_no')) { ?>
	<span class="nav-mobile"><a href="<?php echo get_permalink( zm_get_option('nav_url') ); ?>"><i class="be be-menu"></i></a></span>
<?php } else { ?>
	<?php if (zm_get_option('m_nav')) { ?>
		<?php if ( wp_is_mobile() ) { ?>
			<span class="nav-mobile"><i class="be be-menu"></i></span>
		<?php } else { ?>
			<span id="navigation-toggle" class="bars"><i class="be be-menu"></i></span>
		<?php } ?>
	<?php } else { ?>
		<span id="navigation-toggle" class="bars"><i class="be be-menu"></i></span>
	<?php } ?>
<?php } ?>
	<?php
		wp_nav_menu( array(
			'theme_location'	=> 'navigation',
			'menu_class'		=> 'down-menu nav-menu',
			'fallback_cb'		=> 'default_menu'
		) ); 
	?>
<div id="overlay"></div>
<?php }

function mobile_login() { ?>
	<?php if ( zm_get_option('mobile_login') ) { ?>
		<?php if( is_user_logged_in() ) { ?>
			<?php global $user_identity, $user_level; wp_get_current_user(); ?>
			<div class="mobile-login show-layer" data-show-layer="login-layer" role="button">
				<?php if (zm_get_option('cache_avatar')) { ?>
					<?php global $userdata; wp_get_current_user(); echo begin_avatar($userdata->user_email, 96); ?>
				<?php } else { ?>
					<?php global $userdata; wp_get_current_user(); echo get_avatar($userdata->ID, 96); ?>
				<?php } ?>
				<span class="mobile-login-name"><?php _e( '您已登录：', 'begin' ); ?><?php echo $user_identity; ?></span>
			</div>
		<?php } else { ?>
			<?php if (zm_get_option('user_l')) { ?>
				<?php
				global $user_identity,$user_level;
				wp_get_current_user();
				if ($user_identity) { ?>
					<div class="mobile-login mobile-login-l show-layer" data-show-layer="login-layer" role="button"><i class="be be-timerauto"></i><span class="mobile-login-t"><?php _e( '登录', 'begin' ); ?></span></div>
				<?php } else { ?>
					<div class="mobile-login mobile-login-l"><a href="<?php echo wp_login_url( home_url() ); ?>" title="Login"><i class="be be-timerauto"></i><span class="mobile-login-t"><?php _e( '登录', 'begin' ); ?></span></a></div>
				<?php } ?>
			<?php } else { ?>
				<div class="mobile-login mobile-login-l show-layer" data-show-layer="login-layer" role="button"><i class="be be-timerauto"></i><span class="mobile-login-t"><?php _e( '登录', 'begin' ); ?></span></div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php }

// title span
function title_i() { ?>
<span class="title-i"><span></span><span></span><span></span><span></span></span>
<?php }
function more_i() { ?>
<span class="more-i"><span></span><span></span><span></span></span>
<?php }

// all author
function allauthor() { ?>
<?php
	$exclude.="user_login!='".trim($array[$excludeauthor])."'";
	$where = "WHERE ".$exclude."";
	global $wpdb;
	$table_prefix.=$wpdb->base_prefix;
	$table_prefix.="users";
	$table_prefix1.=$wpdb->base_prefix;
	$table_prefix1.="posts";

	$get_results="SELECT count(p.post_author) as post1,c.id, c.user_login, c.display_name, c.user_email, c.user_url, c.user_registered FROM {$table_prefix} as c , {$table_prefix1} as p {$where} and p.post_type = 'post' AND p.post_status = 'publish' and c.id=p.post_author GROUP BY p.post_author order by post1 DESC limit 1000";
	$comment_counts = $wpdb->get_results("$get_results");

	foreach ( $comment_counts as $count ) {
		$user = get_userdata($count->id);
		echo '<div class="cx6"><div class="author-all ms">';
		$post_count = get_usernumposts($user->ID);
		if (zm_get_option('cache_avatar')) {
			$postount = begin_avatar( $user->user_email, $size = 200);
		} else {
			$postount = get_avatar( $user->user_email, $size = 200);
		}

			$temp=explode(" ",$user->display_name);
		 	$link = sprintf(
				'<a href="%1$s" title="%2$s" >'.$postount.'<div class="author-name">%3$s %4$s %5$s</a></div>',
				get_author_posts_url( $user->ID, $user->user_login ),
				esc_attr( sprintf( ' %s 发表 %s 篇文章', $user->display_name,get_usernumposts($user->ID) ) ),
				$temp[0],$temp[1],$temp[2]
			);
		echo $link;
		echo "</div></div>";
	}
?>
<?php }

// 作者信息
function author_inf() { ?>
<?php
	global $wpdb;
	$author_id = get_the_author_meta( 'ID' );
	$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
?>
<div class="meta-author-box">
	<div class="arrow-up"></div>
	<div class="meta-author-inf">
		<div class="meta-inf-avatar">
			<?php if (zm_get_option('cache_avatar')) { ?>
				<?php echo begin_avatar( get_the_author_meta('user_email'), '96' ); ?>
			<?php } else { ?>
				<?php echo get_avatar( get_the_author_meta('user_email'), '96' ); ?>
			<?php } ?>
		</div>
		<div class="meta-inf-name"><?php the_author(); ?></div>
		<div class="meta-inf meta-inf-posts"><span><?php the_author_posts(); ?></span><br /><?php _e( '文章', 'begin' ); ?></div>
		<div class="meta-inf meta-inf-comment"><span><?php echo $comment_count;?></span><br /><?php _e( '评论', 'begin' ); ?></div>
		<div class="clear"></div>
		<div class="meta-inf-author"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" rel="external nofollow"><?php _e( '更多', 'begin' ); ?></a></div>
	</div>
<div class="clear"></div>
</div>
<?php }


// 作者信息(图片)
function grid_author_inf() { ?>
<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>" rel="external nofollow">
	<span class="meta-author  grid-meta-author">
		<span class="meta-author-avatar" title="<?php _e( '作者信息', 'begin' ); ?>">
			<?php if (zm_get_option('cache_avatar')) { ?>
				<?php echo begin_avatar( get_the_author_meta('email'), '64' ); ?>
			<?php } else { ?>
				<?php echo get_avatar( get_the_author_meta('email'), '64' ); ?>
			<?php } ?>
		</span>
	</span>
</a>
<?php }

// 搜索
function search_class() { ?>
<div class="single-content">
	<div class="searchbar ad-searchbar">
		<form method="get" id="searchform" action="<?php echo esc_url( home_url() ); ?>/">
			<?php if (zm_get_option('search_option') == 'search_cat') { ?>
				<span class="ad-search-cat">
					<span class="ad-search-cat-t">选择分类</span>
					<?php $args = array(
						'show_option_all' => '全部分类',
						'hide_empty'      => 0,
						'name'            => 'cat',
						'show_count'      => 0,
						'taxonomy'        => 'category',
						'hierarchical'    => 1,
						'depth'           => -1,
						'echo'            => 1,
						'exclude'         => zm_get_option('not_search_cat'),
					); ?>
					<?php wp_dropdown_categories( $args ); ?>
				</span>
			<?php } ?>

			<span class="search-input ad-search-input">
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e( '输入搜索内容', 'begin' ); ?>" required />
				<button type="submit" id="searchsubmit"><i class="be be-search"></i></button>
			</span>
		</form>
	</div>

	<?php if (zm_get_option('search_nav')) { ?>
		<nav class="ad-search-nav">
			<div class="ad-search-nav-t"><?php _e( '关键词', 'begin' ); ?></div>
			<div class="clear"></div>
			<?php
				wp_nav_menu( array(
					'theme_location'=> 'search',
					'menu_class'	=> 'ad-search-menu',
					'fallback_cb'	=> 'ad-search-menu'
				) );
			?>
		</nav>
	<?php } ?>
</div>
<?php }

// 展开全文
function all_content() { ?>
<?php if (word_num() > 800) { ?>
	<div class="all-content-box">
		<div class="all-content" onclick="all_more()">展开全文</div>
	</div>
<?php } ?>
<?php }

// 友情链接
function begin_get_the_link_items( $id = null ) {
	global $wpdb,$post;
	$args  = array(
		'orderby'       => 'rand', //排序date
		'order'         => 'ASC',
		'exclude'       => '', // 排除的链接ID
		'category' => $id,
	);

	$bookmarks = get_bookmarks( $args );
	$output = "";
	if ( !empty( $bookmarks ) ) {
		foreach ($bookmarks as $bookmark) {
			$output .= '<div class="link-box"><div class="link-main sup">';
			if ( get_post_meta($post->ID, 'link_img', true) ) {
				$output .= '<div class="page-link-img"><img src="http://f.ydr.me/' . $bookmark->link_url . '" alt="' . $bookmark->link_name . '" /></div><div class="link-name-link"><div class="page-link-name"><a href="' . $bookmark->link_url . ' " target="_blank" >' . $bookmark->link_name . '</div><div class="links-url">' . $bookmark->link_url . '</div></div><div class="link-des-box"><div class="link-des">' . $bookmark->link_description . '</div></div></a></li>';
			} else {
				$output .= '<div class="link-letter">' . getFirstCharter($bookmark->link_name) . '</div><div class="link-name-link"><div class="page-link-name"><a href="' . $bookmark->link_url . ' " target="_blank" >' . $bookmark->link_name . '</div><div class="links-url">' . $bookmark->link_url . '</div></div><div class="link-des-box"><div class="link-des">' . $bookmark->link_description . '</div></div></a></li>';
			}
			$output .= '</div></div>';
		}
	}
	return $output;
}

function begin_get_link_items() {
	$linkcats = get_terms( 'link_category' );
	if ( !empty( $linkcats ) ) {
		foreach( $linkcats as $linkcat ){
			$result .= '<div class="clear"></div><h3 class="link-cat">'.$linkcat->name.'</h3>';
			if( $linkcat->description ) $result .= '<div class="linkcat-des">'.$linkcat->description .'</div>';
			$result .= begin_get_the_link_items( $linkcat->term_id );
		}
	} else {
		$result = begin_get_the_link_items();
	}
	return $result;
}