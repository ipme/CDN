<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div id="related-img" class="wow fadeInUp ms" data-wow-delay="0.3s">
		<?php
			$post_num = zm_get_option('related_n');
			global $post;
			$tmp_post = $post;
			$tags = ''; $i = 0;
			if ( get_the_tags( $post->ID ) ) {
			foreach ( get_the_tags( $post->ID ) as $tag ) $tags .= $tag->slug . ',';
			$tags = strtr(rtrim($tags, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&tag='.$tags.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>

	<div class="r4">
		<div class="related-site">
			<figure class="related-site-img">
				<?php zm_thumbnail(); ?>
			 </figure>
			<div class="related-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
		</div>
	</div>

		<?php
			$i += 1;
			}
			}
			if ( $i < $post_num ) {
			$post = $tmp_post; setup_postdata($post);
			$cats = ''; $post_num -= $i;
			foreach ( get_the_category( $post->ID ) as $cat ) $cats .= $cat->cat_ID . ',';
			$cats = strtr(rtrim($cats, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&category='.$cats.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>

	<div class="r4">
		<div class="related-site">
			<figure class="related-site-img">
				<?php zm_thumbnail(); ?>
			 </figure>
			<div class="related-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
		</div>
	</div>

		<?php
		}
		}
		$post = $tmp_post; setup_postdata($post);
		?>
	<div class="clear"></div>
</div>