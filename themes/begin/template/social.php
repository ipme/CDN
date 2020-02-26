<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="clear"></div>
<div id="social">
	<div class="social-main">
		<span class="like">
			<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" title="<?php _e( '点赞', 'begin' ); ?>" class="dingzan<?php if(isset($_COOKIE['zm_like_'.$post->ID])) echo ' done';?>"><i class="be be-thumbs-up-o"></i><?php _e( '赞', 'begin' ); ?> <i class="count">
				<?php if( get_post_meta($post->ID,'zm_like',true) ){
					echo get_post_meta($post->ID,'zm_like',true);
				} else {
					echo '0';
				}?></i>
			</a>
		</span>
		<div class="share-sd">
			<span class="share-s"><a href="javascript:void(0)" id="share-s" title="<?php _e( '分享', 'begin' ); ?>"><i class="be be-share"></i><?php _e( '分享', 'begin' ); ?></a></span>
			<div id="share"><div class="beginshare"></div></div>
		</div>
		<div class="clear"></div>

		<div class="shang-p">
			<div class="shang-empty"><span></span></div>
			<?php if ( zm_get_option('alipay_name') == '' ) { ?>
				<span class="shang-not"><a title="<?php echo zm_get_option('alipay_t'); ?>"><?php echo zm_get_option('alipay_name'); ?></a></span>
			<?php } else { ?>
				<span id="shang" class="fadeInDown animated" data-wow-delay="0.2s">
					<span class="shang-main">
						<?php if ( zm_get_option('alipay_h') == '' ) { ?><?php } else { ?><h4><i class="be be-favorite" aria-hidden="true"></i> <?php echo zm_get_option('alipay_h'); ?></h4><?php } ?>
						<?php if ( zm_get_option('qr_a') == '' ) { ?>
						<?php } else { ?>
							<span class="shang-img">
								<img src="<?php echo zm_get_option('qr_a'); ?>" alt="alipay"/>
								<?php if ( zm_get_option('alipay_z') == '' ) { ?><?php } else { ?><h4 class="shang-qr"><?php echo zm_get_option('alipay_z'); ?></h4><?php } ?>
							</span>
						<?php } ?>

						<?php if ( zm_get_option('qr_b') == '' ) { ?>
						<?php } else { ?>
							<span class="shang-img">
								<img src="<?php echo zm_get_option('qr_b'); ?>" alt="weixin"/>
								<?php if ( zm_get_option('alipay_w') == '' ) { ?><?php } else { ?><h4 class="shang-qr"><?php echo zm_get_option('alipay_w'); ?></h4><?php } ?>
							</span>
						<?php } ?>
						<span class="clear"></span>
					</span>
				</span>
				<span class="shang-s"><a title="<?php echo zm_get_option('alipay_t'); ?>"><?php echo zm_get_option('alipay_name'); ?></a></span>
			<?php } ?>
		</div>
	</div>
		<span class="clear"></span>
</div>