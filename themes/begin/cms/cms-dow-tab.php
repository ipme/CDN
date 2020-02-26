<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cms_edd')) { ?>
<div class="line-tab sort" name="<?php echo zm_get_option('cms_edd_s'); ?>">
	<div class="img-tab-site wow fadeInUp" data-wow-delay="0.3s">
		<div id="img-tab" class="img-tab-product ms">
		    <div class="img-tab-hd">
				<?php if ( zm_get_option('dow_tab_a') == '' ) { ?><?php } else { ?><span class="img-tab-hd-con"><a href="javascript:"><?php echo zm_get_option('dow_tab_a'); ?></a></span><?php } ?>
				<?php if ( zm_get_option('dow_tab_b') == '' ) { ?><?php } else { ?><span class="img-tab-hd-con"><a href="javascript:"><?php echo zm_get_option('dow_tab_b'); ?></a></span><?php } ?>
				<?php if ( zm_get_option('dow_tab_c') == '' ) { ?><?php } else { ?><span class="img-tab-hd-con"><a href="javascript:"><?php echo zm_get_option('dow_tab_c'); ?></a></span><?php } ?>
		    </div>

			<div class="img-tab-bd img-dom-display wow fadeIn" data-wow-delay="0.5s">

				<div class="img-tab-bd-con img-current">
					<p class="edd-inf"><?php echo zm_get_option('dow_tab_a_s'); ?></p>
					<?php 
						$args = array('tax_query' => array( array('taxonomy' => 'download_category', 'field' => 'id', 'terms' => explode(',',zm_get_option('cms_edd_a_id') ))), 'posts_per_page' => zm_get_option('cms_edd_n'));
						query_posts($args); while ( have_posts() ) : the_post();
					?>
					<article id="post-<?php the_ID(); ?>" class="w4 x4 wow fadeIn" data-wow-delay="0.3s">
						<div class="picture">
							<figure class="picture-img">
								<?php tao_thumbnail(); ?>
							</figure>
							<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<div class="img-tab-meta">
								<div class="img-cat">分类：<?php echo get_the_term_list($post->ID,  'download_category', '', ', ', ''); ?></div>
								<div class="clear"></div>
							</div>
						</div>
					</article>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
				</div>

				<div class="img-tab-bd-con">
					<p class="edd-inf"><?php echo zm_get_option('dow_tab_b_s'); ?></p>
					<?php 
						$args = array('tax_query' => array( array('taxonomy' => 'download_category', 'field' => 'id', 'terms' => explode(',',zm_get_option('cms_edd_b_id') ))), 'posts_per_page' => zm_get_option('cms_edd_n'));
						query_posts($args); while ( have_posts() ) : the_post();
					?>
					<article id="post-<?php the_ID(); ?>" class="w4 x4">
						<div class="picture">
							<figure class="picture-img">
								<?php tao_thumbnail(); ?>
							</figure>
							<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<div class="img-tab-meta">
								<div class="img-cat">分类：<?php echo get_the_term_list($post->ID,  'download_category', '', ', ', ''); ?></div>
								<div class="clear"></div>
							</div>
						</div>
					</article>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
				</div>

				<div class="img-tab-bd-con">
					<p class="edd-inf"><?php echo zm_get_option('dow_tab_c_s'); ?></p>
					<?php 
						$args = array('tax_query' => array( array('taxonomy' => 'download_category', 'field' => 'id', 'terms' => explode(',',zm_get_option('cms_edd_c_id') ))), 'posts_per_page' => zm_get_option('cms_edd_n'));
						query_posts($args); while ( have_posts() ) : the_post();
					?>
					<article id="post-<?php the_ID(); ?>" class="w4 x4">
						<div class="picture">
							<figure class="picture-img">
								<?php tao_thumbnail(); ?>
							</figure>
							<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<div class="img-tab-meta">
								<div class="img-cat">分类：<?php echo get_the_term_list($post->ID,  'download_category', '', ', ', ''); ?></div>
								<div class="clear"></div>
							</div>
						</div>
					</article>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php } ?>