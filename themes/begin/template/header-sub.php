<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (!is_search() && is_archive()) { ?>
	<?php if (zm_get_option('cat_des')) { ?>
		<?php if ( !is_paged() && !is_author() && !is_tag() && category_description()){ ?>
			<?php archive_img(); ?>
		<?php } ?>

		<?php if ( !is_paged() && !is_author() && is_tag() && !is_category()){ ?>
			<?php archive_img(); ?>
		<?php } ?>

	<?php } ?>

	<?php if ( !is_paged() && !is_author()  && !is_page() && !is_tax('notice') && !is_tax('gallery') && !is_tax('gallerytag') && !is_tax('videos') && !is_tax('videotag') && !is_tax('taobao') && !is_tax('taotag') && !is_tax('products') && !is_tax('product_cat') && !is_tax('product_tag') && !is_tax('download_category') && !is_tax('download_tag') && !is_tax('dwqa-question_category') && !is_tax('wqa-question_tag') && !is_tax('favorites') && !is_post_type_archive('product') ) : ?>
		<?php if (zm_get_option('child_cat') && get_category_children(get_category_id($cat)) != "" ) { ?>
			<div class="header-sub">
				<ul class="child-cat wow fadeInUp" data-wow-delay="0.3s">
		    		<?php wp_list_categories('child_of=' . get_category_id($cat) . '&depth=0&hierarchical=0&hide_empty=0&title_li=&use_desc_for_title=0&orderby=id&order=ASC');?>
					<ul class="clear"></ul>
				</ul>
			</div>
		<?php } ?>
	<?php endif; ?>

	<?php if ( is_author() ) : ?>
		<?php
			global $wpdb;
			$author_id = get_the_author_meta( 'ID' );
			$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
		?>
		<div class="header-sub">
			<div class="cat-des wow fadeInUp ms" data-wow-delay="0.3s">
				<div class="cat-des-img"><img src="<?php echo zm_get_option('header_author_img'); ?>" alt="<?php the_author(); ?>"></div>
				<div class="header-author">
					
					<div class="header-author-inf">
						<div class="header-avatar">
							<?php if (zm_get_option('cache_avatar')) { ?>
								<?php echo begin_avatar( get_the_author_meta('user_email'), '96' ); ?>
							<?php } else { ?>
								<?php echo get_avatar( get_the_author_meta('user_email'), '96' ); ?>
							<?php } ?>
						</div>
						<div class="header-user-author">
							<h1 class="des-t"><?php the_author(); ?></h1>
							<?php if ( get_the_author_meta( 'description') ) { ?>
								<p class="header-user-des"><?php the_author_meta( 'user_description' ); ?></p>
							<?php } else { ?>
								<p class="header-user-des">这家伙什么也没有留下！</p>
							<?php } ?>
						</div>
					</div>
				</div>
				<p class="header-user-inf">
					<span><i class="be be-editor"></i><?php the_author_posts(); ?></span>
					<span><i class="be be-speechbubble"></i><?php echo $comment_count;?></span>
					<span><i class="be be-eye"></i><?php $author_views = author_posts_views(get_the_author_meta('ID'),false);echo $author_views;?></span>
				</p>
			</div>
		</div>
	<?php endif; ?>

<?php } ?>

<?php if (zm_get_option('filters')) { ?>
<?php if (in_category(explode(',',zm_get_option('filter_id') ) ) && !is_single() && !is_home() && !is_author() && !is_search() && !is_tag()) { ?>
<div class="header-sub">
	<?php get_template_part( '/inc/filter' ); ?>
</div>
<?php } ?>
<?php } ?>

<?php if (!is_search() && !is_tag() && !is_paged() && !is_author() && !is_page() && !is_home()) { ?>
<?php get_template_part( '/template/header-widget' ); ?>
<?php } ?>

<?php
function archive_img() { ?>
	<div class="header-sub">
		<div class="cat-des wow fadeInUp ms" data-wow-delay="0.3s">
			<?php if (zm_get_option('cat_icon')) { ?>
				<i class="header-cat-icon <?php echo zm_taxonomy_icon_code(); ?>"></i>
			<?php } ?>
			<?php if (zm_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) == ZM_IMAGE_PLACEHOLDER) { ?>
				<div class="cat-des-img"><img src="<?php echo zm_get_option('cat_des_img_d'); ?>" alt="<?php single_cat_title(); ?>"></div>
			<?php } else { ?>
				<?php if (zm_get_option('cat_des_img')) { ?>
					<div class="cat-des-img"><img src="<?php if (function_exists('zm_taxonomy_image_url')) echo get_template_directory_uri().'/prune.php?src='.zm_taxonomy_image_url().'&w='.zm_get_option('img_des_w').'&h='.zm_get_option('img_des_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" alt="<?php single_cat_title(); ?>"></div>
				<?php } else { ?>
					<div class="cat-des-img"><img src="<?php if (function_exists('zm_taxonomy_image_url')) echo zm_taxonomy_image_url(); ?>" alt="<?php single_cat_title(); ?>"></div>
				<?php } ?>
			<?php } ?>
			<div class="des-title">
				<h1 class="des-t"><?php single_cat_title(); ?></h1>
				<?php if (zm_get_option('cat_des_p')) { ?><?php echo the_archive_description( '<div class="des-p">', '</div>' ); ?><?php } ?>
			</div>
		</div>
	</div>
<?php } ?>