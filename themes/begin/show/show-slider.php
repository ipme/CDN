<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="g-row">
	<div class="show-header-box">
		<?php 
			$s_a_img_d = get_post_meta($post->ID, 's_a_img_d', true);
			$s_a_img_x = get_post_meta($post->ID, 's_a_img_x', true);
			$s_a_t_a = get_post_meta($post->ID, 's_a_t_a', true);
			$s_a_t_b = get_post_meta($post->ID, 's_a_t_b', true);
			$s_a_t_c = get_post_meta($post->ID, 's_a_t_c', true);
			$s_a_n_b = get_post_meta($post->ID, 's_a_n_b', true);
			$s_a_n_b_l = get_post_meta($post->ID, 's_a_n_b_l', true);
		?>
		<div class="show-header-img">
			<?php if ( get_post_meta($post->ID, 's_a_img_d', true) ) { ?>
				<div class="show-big-img"><img src="<?php echo $s_a_img_d; ?>"></div>
				<div class="show-header-main">
					<div class="show-small-img wow fadeInLeft" data-wow-delay="0.5s"><img src="<?php echo $s_a_img_x; ?>"></div>
					<?php if ( get_post_meta($post->ID, 's_a_t_b', true) ) { ?>
						<div class="show-header-w">
							<div class="show-header-content">
								<p class="s-t-a wow fadeInRight" data-wow-delay="0.5s"><?php echo $s_a_t_a; ?></p>
								<p class="s-t-b wow fadeInRight" data-wow-delay="0.7s"><?php echo $s_a_t_b; ?></p>
								<p class="s-t-c wow fadeInRight" data-wow-delay="0.9s"><?php echo $s_a_t_c; ?></p>
							</div>
							<?php if ( get_post_meta($post->ID, 's_a_n_b', true) ) { ?>
								<div class="group-img-more wow fadeInRight" data-wow-delay="1.1s"><a href="<?php echo $s_a_n_b_l; ?>" rel="bookmark" target="_blank"><?php echo $s_a_n_b; ?></a></div>
							<?php } ?>
						</div>
					<?php } ?>
					
				</div>
			<?php } ?>
		</div>
	</div>
</div>