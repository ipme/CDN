<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('tab_h')) { ?>
<div class="tab-site wow fadeInUp sort ms" data-wow-delay="0.3s" name="<?php echo zm_get_option('tab_h_s'); ?>">
	<div id="layout-tab" class="tab-product<?php if ( zm_get_option('tab_d') == '' ) { ?> tab-product-3<?php } ?>">
		<h3 class="tab-hd">
		    <span class="tab-hd-con"><?php echo zm_get_option('tab_a'); ?></span>
		    <span class="tab-hd-con"><?php echo zm_get_option('tab_b'); ?></span>
		    <span class="tab-hd-con"><?php echo zm_get_option('tab_c'); ?></span>
		    <?php if ( zm_get_option('tab_d') == '' ) { ?><?php } else { ?><span class="tab-hd-con"><?php echo zm_get_option('tab_d'); ?></span><?php } ?>
		</h3>

		<div class="tab-bd dom-display">

			<ul class="tab-bd-con current">
				<?php query_posts('showposts='.zm_get_option('tabt_n').'&cat='.zm_get_option('tabt_id')); while (have_posts()) : the_post(); ?>
				<?php the_title( sprintf( '<li class="list-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
				<?php endwhile; ?>
				<li class="list-title-more">
					<?php
					$cat=get_term_by('id', zm_get_option('tabt_id'), 'category');
					$cat_links=get_category_link($cat->term_id);
					?>
					<a href="<?php echo $cat_links; ?>" title="<?php echo $cat->name; ?>"><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a>
				</li>
				<?php wp_reset_query(); ?>
			</ul>

			<ul class="tab-bd-con">
				<?php query_posts('showposts='.zm_get_option('tabt_n').'&cat='.zm_get_option('tabz_n')); while (have_posts()) : the_post(); ?>
				<?php the_title( sprintf( '<li class="list-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
				<?php endwhile; ?>
				<li class="list-title-more">
					<?php
					$cat=get_term_by('id', zm_get_option('tabz_n'), 'category');
					$cat_links=get_category_link($cat->term_id);
					?>
					<a href="<?php echo $cat_links; ?>" title="<?php echo $cat->name; ?>"><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a>
				</li>
				<?php wp_reset_query(); ?>
		    </ul>

			<ul class="tab-bd-con">
				<?php query_posts('showposts='.zm_get_option('tabt_n').'&cat='.zm_get_option('tabq_n')); while (have_posts()) : the_post(); ?>
				<?php the_title( sprintf( '<li class="list-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
				<?php endwhile; ?>
				<li class="list-title-more">
					<?php
					$cat=get_term_by('id', zm_get_option('tabq_n'), 'category');
					$cat_links=get_category_link($cat->term_id);
					?>
					<a href="<?php echo $cat_links; ?>" title="<?php echo $cat->name; ?>"><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a>
				</li>
				<?php wp_reset_query(); ?>
			</ul>

			<?php if ( zm_get_option('tab_d')) { ?>
			<ul class="tab-bd-con">
				<?php query_posts('showposts='.zm_get_option('tabt_n').'&cat='.zm_get_option('tabp_n')); while (have_posts()) : the_post(); ?>
				<?php the_title( sprintf( '<li class="list-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
				<?php endwhile; ?>
				<li class="list-title-more">
					<?php
					$cat=get_term_by('id', zm_get_option('tabp_n'), 'category');
					$cat_links=get_category_link($cat->term_id);
					?>
					<a href="<?php echo $cat_links; ?>" title="<?php echo $cat->name; ?>"><?php _e( '更多', 'begin' ); ?> <i class="be be-fastforward"></i></a>
				</li>
				<?php wp_reset_query(); ?>
			</ul>
			<?php } ?>

		</div>
	</div>
</div>
<div class="clear"></div>
<?php } ?>