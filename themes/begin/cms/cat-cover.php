<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('h_cat_cover') && zm_get_option('cat_cover')) { ?>
<div class="cat-cover-box sort" name="<?php echo zm_get_option('cms_top_s'); ?>">
	<?php if (zm_get_option('cat_tag_cover')) { ?>

		<?php 
			$args=array( 'include' => zm_get_option('cat_cover_id') );
			$tags = get_tags($args);
			foreach ($tags as $tag) { 
				$tagid = $tag->term_id; 
				query_posts("tag_id=$tagid");
		?>
		<div class="cover4x">
			<div class="cat-cover-main wow fadeInUp ms" data-wow-delay="0.3s">
				<div class="cat-cover-img">
					<?php if (zm_get_option('cat_icon')) { ?><i class="cover-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
					<a href="<?php echo get_tag_link($tagid);?>" rel="bookmark">
						<?php if (zm_get_option('cat_cover_img')) { ?>
							<figure class="cover-img"><img src="<?php echo get_template_directory_uri().'/prune.php?src='.cat_cover_url().'&w='.zm_get_option('img_co_w').'&h='.zm_get_option('img_co_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" alt="<?php echo $tag->name; ?>"></figure>
						<?php } else { ?>
							<figure class="cover-img"><img src="<?php echo cat_cover_url(); ?>" alt="<?php echo $tag->name; ?>"></figure>
						<?php } ?>
						<div class="cover-des-box"><?php echo the_archive_description( '<div class="cover-des">', '</div>' ); ?></div>
					</a>
					<div class="clear"></div>
				</div>
				<a href="<?php echo get_tag_link($tagid);?>" rel="bookmark"><h4 class="cat-cover-title"><?php echo $tag->name; ?></h4></a>
			</div>
		</div>
	<?php } wp_reset_query(); ?>
	<?php } else { ?>
		<?php
			$args=array( 'include' => zm_get_option('cat_cover_id') );
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
				query_posts( 'cat=' . $cat->cat_ID );
		?>
		<div class="cover4x">
			<div class="cat-cover-main wow fadeInUp ms" data-wow-delay="0.3s">
				<div class="cat-cover-img">
					<?php if (zm_get_option('cat_icon')) { ?><i class="cover-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
					<a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark">
						<?php if (zm_get_option('cat_cover_img')) { ?>
							<figure class="cover-img"><img src="<?php echo get_template_directory_uri().'/prune.php?src='.cat_cover_url().'&w='.zm_get_option('img_co_w').'&h='.zm_get_option('img_co_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" alt="<?php single_cat_title(); ?>"></figure>
						<?php } else { ?>
							<figure class="cover-img"><img src="<?php echo cat_cover_url(); ?>" alt="<?php single_cat_title(); ?>"></figure>
						<?php } ?>
						<div class="cover-des-box"><?php echo the_archive_description( '<div class="cover-des">', '</div>' ); ?></div>
					</a>
					<div class="clear"></div>
				</div>
				<a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><h4 class="cat-cover-title"><?php echo $cat->cat_name; ?></h4></a>
			</div>
		</div>
		<?php } wp_reset_query(); ?>
	<?php } ?>
	<div class="clear"></div>
</div>
<?php } ?>