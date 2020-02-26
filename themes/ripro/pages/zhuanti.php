<?php 
/**
 * Template name: 专题
 * Description:   A zhuanti page
 */

get_header();

$cat_args=array(
    'orderby' => 'name',
    'order' => 'ASC'
);
$categories=get_categories($cat_args);
$i = 0;
?>

<div class="container">
        <main class="site-main">

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post zhuanti' ); ?>>
              <div class="col-12">
              	<div class="row zhuanti">
              		<?php foreach($categories as $category) : ?>
              			<?php 
              				$i++;
              				if ($i>30) { break; } // 最多显示30个
							$args = array( 'cat' => $category->cat_ID, 'posts_per_page' => 1 );
							$posts = get_posts( $args );
							if ($posts) {
								foreach( $posts as $post ) {
									setup_postdata( $post );
									$bg_image = get_the_post_thumbnail_url();
									break;
								}
							}
						?>
              			<div class="col-12 col-sm-6 col-md-4 col-lg-3">
							<div class="zhuanti-tile">
								<div class="zhuanti-tile__wrap">
									<div class="background-img lazyload" data-bg="<?php echo esc_url( $bg_image ); ?>">
									</div>
						            <div class="zhuanti-tile__inner">
					                	<div class="zhuanti-tile__text inverse-text">
					                    	<a class="zhuanti-tile__name cat-theme-bg" href="<?php echo get_category_link( $category->term_id );?>" title="<?php echo $category->name;?>"><?php echo $category->name;?></a>
					                    	<div class="zhuanti-tile__description"><?php echo $category->count;?>篇文章</div>
					                    </div>
					                </div>
							    </div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
	
            </article>

        </main>
</div>

<?php get_footer(); ?>


