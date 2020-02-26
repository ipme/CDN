<?php
$mode_catbox = _cao('mode_catbox2');
$slugs = ($mode_catbox['cat_id']) ? $mode_catbox['cat_id'] : array(1,1,1,1);

ob_start(); ?>
<div class="section bgcolor-fff">
	<div class="container">
		<div class="module category-boxes boxes2 owl">
			<?php foreach ( $slugs as $cat_id ) : ?>
				<div class="category-box">
					<?php $category = get_category( $cat_id );
					$seo_str     = $category->description;
					$args = array( 'cat' => $cat_id, 'posts_per_page' => 3 );
					$category_posts = get_posts( $args );
					$thumbnails = array();
					$index = 0;

					foreach ( $category_posts as $category_post ) :
						$thumbnail_url  = _get_post_thumbnail_url( $category_post);
						if ($thumbnail_url) {
							$thumbnails[] =$thumbnail_url;
						}else{
							$thumbnails[] = _the_theme_thumb();
						}
						$index++;
					endforeach; ?>
					<div class="entry-thumbnails-17codesign">
							<h3 class="entry-title-17codesign"><?php echo esc_html( $category->cat_name ); ?></h3>
							<span class="description-17codesign"><p><?php echo esc_html( $seo_str ); ?></p></span>
							<div class="group-17codesign">
								<?php for ($i=0; $i < 3; $i++) {
									$_thumb = (isset($thumbnails[$i])) ? $thumbnails[$i] : $thumbnails[0] ;
									echo '<div class="thumbnail-17codesign">';
									echo '<img class="lazyload" data-src="'.esc_url( $_thumb).'">';
									echo '</div>';
								} ?>
								<span>+<?php echo esc_html($category->category_count); ?></span>
							</div>
						</div>
					<a<?php echo _target_blank();?> class="u-permalink" href="<?php echo esc_url( get_category_link( $category->cat_ID ) ); ?>"></a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>