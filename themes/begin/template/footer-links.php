<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( is_front_page()){ ?>
	<?php if (!zm_get_option('layout') || (zm_get_option("layout") == 'group')) { ?>
	<div class="links-group">
		<div id="links">
			<?php if (zm_get_option('footer_img')) { ?>
				<ul><?php wp_list_bookmarks('title_li=&before=<li class="lx7 wow fadeInUp sup" data-wow-delay="0.4s"><span class="link-f link-img sup">&after=</span></li>&categorize=1&show_images=1&orderby=rating&order=DESC&category='.zm_get_option('link_f_cat')); ?></ul>
			<?php } else { ?>
				<?php wp_list_bookmarks('title_li=&before=<ul class="lx7"><li class="link-f link-name wow fadeInUp sup" data-wow-delay="0.3s">&after=</li></ul>&categorize=0&show_images=0&orderby=rating&order=DESC&category='.zm_get_option('link_f_cat')); ?>
			<?php } ?>
			<?php if ( zm_get_option('link_url') == '' ) { ?><?php } else { ?><ul class="lx7"><li class="link-f wow fadeInUp" data-wow-delay="0.3s"><a href="<?php echo get_permalink( zm_get_option('link_url') ); ?>" target="_blank" title="<?php _e( '更多链接', 'begin' ); ?>"><?php _e( '更多链接', 'begin' ); ?></a></li></ul><?php } ?>
			<div class="clear"></div>
		</div>
	</div>

	<?php } else { ?>
		<?php if (!zm_get_option('footer_link_no') || ( !wp_is_mobile() ) ) { ?>
			<div class="links-box">
				<div id="links">
					<?php if (zm_get_option('footer_img')) { ?>
						<ul><?php wp_list_bookmarks('title_li=&before=<li class="lx7 wow fadeInUp" data-wow-delay="0.4s"><span class="link-f link-img sup">&after=</span></li>&categorize=1&show_images=1&orderby=rating&order=DESC&category='.zm_get_option('link_f_cat')); ?></ul>
					<?php } else { ?>
						<?php wp_list_bookmarks('title_li=&before=<ul class="lx7"><li class="link-f link-name wow fadeInUp sup" data-wow-delay="0.3s">&after=</li></ul>&categorize=0&show_images=0&orderby=rating&order=DESC&category='.zm_get_option('link_f_cat')); ?>
					<?php } ?>
					<?php if ( zm_get_option('link_url') == '' ) { ?><?php } else { ?><ul class="lx7"><li class="link-f wow fadeInUp sup" data-wow-delay="0.3s"><a href="<?php echo get_permalink( zm_get_option('link_url') ); ?>" target="_blank" title="<?php _e( '更多链接', 'begin' ); ?>"><?php _e( '更多链接', 'begin' ); ?></a></li></ul><?php } ?>
					<div class="clear"></div>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
<?php } ?>