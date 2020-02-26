<?php
// 面包屑导航
function begin_breadcrumb() {
	if (is_home()) {
		if ( zm_get_option('bulletin') ) {
			echo '<div class="bull">';
			echo '<i class="be be-volumedown"></i>';
			echo "</div>";
			get_template_part( 'template/bulletin' );
		} else {
			echo '' . sprintf(__( '现在位置', 'begin' )) . '<i class="be be-arrowright"></i>' . sprintf(__( '首页', 'begin' )) . '';
		}
	}

	if ( !is_home() && !is_front_page() ) {
		echo '<a class="crumbs" href="';
		echo home_url('/');
		echo '">';
		echo '' . sprintf(__( '首页', 'begin' )) . '';
		echo "</a>";
	}

	if ( !is_search() && is_category() ) {
		echo '<i class="be be-arrowright"></i>';
		echo get_category_parents( get_query_var('cat') , true , '<i class="be be-arrowright"></i>' );
		// echo '' . sprintf(__( '文章', 'begin' )) . ' ';
	}

	if ( is_tax('notice') ) {
		echo '<i class="be be-arrowright"></i>';
		
	}
	if ( is_tax('gallery') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('gallerytag') ) {
		echo '<i class="be be-arrowright"></i>';
		echo setTitle();
	}

	if ( is_tax('videos') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('videotag') ) {
		echo '<i class="be be-arrowright"></i>';
		echo setTitle();
	}

	if ( is_tax('taobao') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('taotag') ) {
		echo '<i class="be be-arrowright"></i>';
		echo setTitle();
	}

	if ( is_tax('products') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('product_cat') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('product_tag') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('favorites') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if (function_exists( 'is_shop' )) {
		if ( zm_get_option('woo') ) {
			if ( is_shop('shop') && !is_front_page() ) {
				echo '<i class="be be-arrowright"></i>';
				echo trim( wp_title( '',0 ) );
			}
		}
	}

	if ( is_tax('download_category') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('download_tag') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('dwqa-question_category') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('dwqa-question_tag') ) {
		echo '<i class="be be-arrowright"></i>';
	}

	if ( is_tax('filtersa') || is_tax('filtersb') || is_tax('filtersc') ) {
		echo '<i class="be be-arrowright"></i>筛选结果';
	}

	if (is_single()) {
		echo '<i class="be be-arrowright"></i>';
		echo the_category('<i class="be be-arrowright"></i>', 'multiple');
		if ( 'post' == get_post_type() ) {
			echo '<i class="be be-arrowright"></i>';
			if (wp_is_mobile()) {
				echo '' . sprintf(__( '正文', 'begin' )) . '';
			} else {
				echo '' . the_title() . '';
			}
		}
		if (is_attachment() ) {echo '' . sprintf(__( '附件', 'begin' )) . ''; }
	}

	if ( is_page() && !is_front_page() ) {
		echo '<i class="be be-arrowright"></i>';
		echo the_title();
	}

	if ( is_page() && is_front_page() ) {
		if (zm_get_option('bulletin')) {
			echo '<div class="bull">';
			echo '<i class="be be-volumedown"></i>';
			echo "</div>";
			get_template_part( 'template/bulletin' );
		} else {
			echo '' . sprintf(__( '现在位置', 'begin' )) . '<i class="be be-arrowright"></i>' . sprintf(__( '首页', 'begin' )) . '';
		}
	}

	elseif ( is_tag() ) {echo '<i class="be be-arrowright"></i>';single_tag_title();echo '';}
	elseif ( is_day() ) {echo '<i class="be be-arrowright"></i>';echo"发表于"; the_time('Y年m月d日'); echo'的文章';}
	elseif ( is_month() ) {echo '<i class="be be-arrowright"></i>';echo"发表于"; the_time('Y年m月'); echo'的文章';}
	elseif ( is_year() ) {echo '<i class="be be-arrowright"></i>';echo"发表于"; the_time('Y年'); echo'的文章';}
	elseif ( is_author() ) {echo '<i class="be be-arrowright"></i>';echo wp_title( ''); echo'发表的文章';}
	elseif ( is_404() ) {echo '<i class="be be-arrowright"></i>';echo'' . sprintf(__( '亲，你迷路了！', 'begin' )) . ''; echo'';}
	elseif ( is_search()) {
		echo '<i class="be be-arrowright"></i>' . sprintf(__( '搜索', 'begin' )) . ' ';
		echo '<i class="be be-arrowright"></i>';
		echo search_results();
	}
}