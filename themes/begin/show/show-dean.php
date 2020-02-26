<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php 
	$s_j_t = get_post_meta($post->ID, 's_j_t', true);
	$s_j_e = get_post_meta($post->ID, 's_j_e', true);
	$ss_a_a = get_post_meta($post->ID, 'ss_a_a', true);
	$ss_a_b = get_post_meta($post->ID, 'ss_a_b', true);
	$ss_a_c = get_post_meta($post->ID, 'ss_a_c', true);
	$ss_a_d = get_post_meta($post->ID, 'ss_a_d', true);
	$ss_a_e = get_post_meta($post->ID, 'ss_a_e', true);
	$ss_b_a = get_post_meta($post->ID, 'ss_b_a', true);
	$ss_b_b = get_post_meta($post->ID, 'ss_b_b', true);
	$ss_b_c = get_post_meta($post->ID, 'ss_b_c', true);
	$ss_b_d = get_post_meta($post->ID, 'ss_b_d', true);
	$ss_b_e = get_post_meta($post->ID, 'ss_b_e', true);
	$ss_c_a = get_post_meta($post->ID, 'ss_c_a', true);
	$ss_c_b = get_post_meta($post->ID, 'ss_c_b', true);
	$ss_c_c = get_post_meta($post->ID, 'ss_c_c', true);
	$ss_c_d = get_post_meta($post->ID, 'ss_c_d', true);
	$ss_c_e = get_post_meta($post->ID, 'ss_c_e', true);
	$ss_d_a = get_post_meta($post->ID, 'ss_d_a', true);
	$ss_d_b = get_post_meta($post->ID, 'ss_d_b', true);
	$ss_d_c = get_post_meta($post->ID, 'ss_d_c', true);
	$ss_d_d = get_post_meta($post->ID, 'ss_d_d', true);
	$ss_d_e = get_post_meta($post->ID, 'ss_d_e', true);
?>
<?php if ( get_post_meta($post->ID, 'ss_a_a', true) ) { ?>
<div class="g-row">
	<div class="g-col">
		<div class="deanm">
			<div class="group-title wow fadeInUp" data-wow-delay="0.3s">
				<?php if ( get_post_meta($post->ID, 's_j_t', true) ) { ?>
					<h3><i class="be be-skyatlas"></i> <?php echo $s_j_t; ?> <i class="be be-skyatlas"></i></h3>
				<?php } else { ?>
				<?php } ?>
				<div class="group-des"><?php echo $s_j_e; ?></div>
				<div class="clear"></div>
			</div>
			<div class="deanm-main wow fadeInUp" data-wow-delay="0.3s">
				<div class="deanm deanmove">
					<div class="de-t"><?php echo $ss_a_a; ?></div>
					<div class="clear"></div>
					<div class="de-a"><?php echo $ss_a_b; ?></div>
					<div class="deanquan">
						<div class="de-back">
							<?php if ( get_post_meta($post->ID, 'ss_a_e', true) ) { ?><img src="<?php echo $ss_a_e; ?>"><?php } else { ?><img src="http://wx1.sinaimg.cn/large/0066LGKLly1fgbmwz3draj305u05uabc.jpg" alt="广告也精彩" /><?php } ?>
							<div class="de-b"><?php echo $ss_a_c; ?></div>
						</div>
					</div>
					<div class="de-c"><?php echo $ss_a_d; ?></div>
				</div>

				<div class="deanm deanmove">
					<div class="de-t"><?php echo $ss_b_a; ?></div>
					<div class="clear"></div>
					<div class="de-a"><?php echo $ss_b_b; ?></div>
					<div class="deanquan">
						<div class="de-back">
							<?php if ( get_post_meta($post->ID, 'ss_b_e', true) ) { ?><img src="<?php echo $ss_b_e; ?>"><?php } else { ?><img src="http://wx1.sinaimg.cn/large/0066LGKLly1fgbmwz3draj305u05uabc.jpg" alt="广告也精彩" /><?php } ?>
							<div class="de-b"><?php echo $ss_b_c; ?></div>
						</div>
					</div>
					<div class="de-c"><?php echo $ss_b_d; ?></div>
				</div>

				<div class="deanm deanmove">
					<div class="de-t"><?php echo $ss_c_a; ?></div>
					<div class="clear"></div>
					<div class="de-a"><?php echo $ss_c_b; ?></div>
					<div class="deanquan">
						<div class="de-back">
							<?php if ( get_post_meta($post->ID, 'ss_c_e', true) ) { ?><img src="<?php echo $ss_c_e; ?>"><?php } else { ?><img src="http://wx1.sinaimg.cn/large/0066LGKLly1fgbmwz3draj305u05uabc.jpg" alt="广告也精彩" /><?php } ?>
							<div class="de-b"><?php echo $ss_c_c; ?></div>
						</div>
					</div>
					<div class="de-c"><?php echo $ss_c_d; ?></div>
				</div>

				<div class="deanm deanmove">
					<div class="de-t"><?php echo $ss_d_a; ?></div>
					<div class="clear"></div>
					<div class="de-a"><?php echo $ss_d_b; ?></div>
					<div class="deanquan">
						<div class="de-back">
							<?php if ( get_post_meta($post->ID, 'ss_d_e', true) ) { ?><img src="<?php echo $ss_d_e; ?>"><?php } else { ?><img src="http://wx1.sinaimg.cn/large/0066LGKLly1fgbmwz3draj305u05uabc.jpg" alt="广告也精彩" /><?php } ?>
							<div class="de-b"><?php echo $ss_d_c; ?></div>
						</div>
					</div>
					<div class="de-c"><?php echo $ss_d_d; ?></div>
				</div>

				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>