<?php

$mode_catbox = _cao('mode_catbox');

$slugs = ($mode_catbox['cat_id']) ? $mode_catbox['cat_id'] : array(1,1,1,1);

ob_start(); ?>
<div class="section bgcolor-fff">
	<div class="container">
		<div class="module category-boxes owl">
			<?php foreach ( $slugs as $cat_id ) : ?>
				<div class="category-box">
					<?php $category = get_category( $cat_id );
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
					<div class="entry-thumbnails">
						<div class="big thumbnail">
								<h3 class="entry-title"><?php echo esc_html( $category->cat_name ); ?></h3>
							<img class="lazyload" data-src="<?php echo esc_url( $thumbnails[0] ); ?>">
						</div>
						<div class="small">
							<div class="thumbnail">
								<?php if ( isset($thumbnails[1]) ) : ?>
									<img class="lazyload" data-src="<?php echo esc_url( $thumbnails[1] ); ?>">
								<?php else : ?>
									<img class="lazyload" data-src="<?php echo esc_url( $thumbnails[0] ); ?>">
								<?php endif; ?>
							</div>
							<div class="thumbnail">
								<?php if ( isset($thumbnails[2]) ) : ?>
									<img class="lazyload" data-src="<?php echo esc_url( $thumbnails[2] ); ?>">
								<?php else : ?>
									<img class="lazyload" data-src="<?php echo esc_url( $thumbnails[0] ); ?>">
								<?php endif; ?>
								<span>+<?php echo esc_html($category->category_count); ?></span>
							</div>
						</div>
					</div>
					<a<?php echo _target_blank();?> class="u-permalink" href="<?php echo esc_url( get_category_link( $category->cat_ID ) ); ?>"></a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>