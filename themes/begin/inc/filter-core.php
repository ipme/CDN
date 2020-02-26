<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('filters_a')) { ?>
	<div class="filter-main">
		<span class="filter-name"><i class="be be-sort"></i><?php echo zm_get_option('filters_a_t'); ?></span>
		<span class="filtertag" id="filtersa"<?php if($filtersa!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersa)))).'"';}?>>
			<?php
			$terms = get_terms("filtersa");
			$count = count($terms);
			if ( $count > 0 ){
				foreach ( $terms as $term ) {
					if(strtolower(urlencode(urldecode(urldecode($filtersa))))==$term->slug){
						echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
					}else{
						echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
					}
				}
			}
			?>
		</span>
	</div>
<?php } ?>

<?php if (zm_get_option('filters_b')) { ?>
	<div class="clear"></div>
	<div class="filter-main">
		<span class="filter-name"><i class="be be-sort"></i><?php echo zm_get_option('filters_b_t'); ?></span>
		<span class="filtertag" id="filtersb"<?php if($filtersb!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersb)))).'"';}?>>
			<?php
			$terms = get_terms("filtersb");
			$count = count($terms);
			if ( $count > 0 ){
				foreach ( $terms as $term ) {
					if(strtolower(urlencode(urldecode(urldecode($filtersb))))==$term->slug){
						echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
					}else{
						echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
					}
				}
			}
			?>
		</span>
	</div>
<?php } ?>

<?php if (zm_get_option('filters_c')) { ?>
	<div class="clear"></div>
	<div class="filter-main">
		<span class="filter-name"><i class="be be-sort"></i><?php echo zm_get_option('filters_c_t'); ?></span>
		<span class="filtertag" id="filtersc"<?php if($filtersc!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersc)))).'"';}?>>
			<?php
			$terms = get_terms("filtersc");
			$count = count($terms);
			if ( $count > 0 ){
				foreach ( $terms as $term ) {
					if(strtolower(urlencode(urldecode(urldecode($filtersc))))==$term->slug){
						echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
					}else{
						echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
					}
				}
			}
			?>
		</span>
	</div>
<?php } ?>

<?php if (zm_get_option('filters_d')) { ?>
	<div class="clear"></div>
	<div class="filter-main">
		<span class="filter-name"><i class="be be-sort"></i><?php echo zm_get_option('filters_d_t'); ?></span>
		<span class="filtertag" id="filtersd"<?php if($filtersd!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filtersd)))).'"';}?>>
			<?php
			$terms = get_terms("filtersd");
			$count = count($terms);
			if ( $count > 0 ){
				foreach ( $terms as $term ) {
					if(strtolower(urlencode(urldecode(urldecode($filtersd))))==$term->slug){
						echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
					}else{
						echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
					}
				}
			}
			?>
		</span>
	</div>
<?php } ?>

<?php if (zm_get_option('filters_e')) { ?>
	<div class="clear"></div>
	<div class="filter-main">
		<span class="filter-name"><i class="be be-sort"></i><?php echo zm_get_option('filters_e_t'); ?></span>
		<span class="filtertag" id="filterse"<?php if($filterse!=''){echo ' data="'.strtolower(urlencode(urldecode(urldecode($filterse)))).'"';}?>>
			<?php
			$terms = get_terms("filterse");
			$count = count($terms);
			if ( $count > 0 ){
				foreach ( $terms as $term ) {
					if(strtolower(urlencode(urldecode(urldecode($filterse))))==$term->slug){
						echo '<a class="filter-tag filter-on" data="'. $term->slug .'">' . $term->name . '</a>';
					}else{
						echo '<a class="filter-tag" data="'. $term->slug .'">' . $term->name . '</a>';
					}
				}
			}
			?>
		</span>
	</div>
<?php } ?>