<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="searchbar">
	<form method="get" id="searchform" action="<?php echo esc_url( home_url() ); ?>/">
		<span class="search-input">
			<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e( '输入搜索内容', 'begin' ); ?>" required />
			<button type="submit" id="searchsubmit"><i class="be be-search"></i></button>
		</span>
		<?php if (zm_get_option('search_option') == 'search_cat') { ?><?php search_cat_args( ); ?><?php } ?>
	</form>
</div>