<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('single_weixin_one')) { ?>
<div class="s-weixin-one">
	<div class="weimg-one">
		<img src="<?php echo zm_get_option('weixin_h_img'); ?>" alt="weinxin" />
		<div class="weixin-h"><strong><?php if ( zm_get_option('weixin_h') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_h'); ?><?php } ?></strong></div>
		<div class="weixin-h-w"><?php if ( zm_get_option('weixin_h_w') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_h_w'); ?><?php } ?></div>
		<div class="clear"></div>
	</div>
</div>
<?php } else { ?>
<div class="s-weixin">
	<ul class="weimg1">
		<li>
			<strong><?php if ( zm_get_option('weixin_h') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_h'); ?><?php } ?></strong>
		</li>
		<li><?php if ( zm_get_option('weixin_h_w') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_h_w'); ?><?php } ?></li>
		<li>
			<img src="<?php echo zm_get_option('weixin_h_img'); ?>" alt="weinxin" />
		</li>
	</ul>
	<ul class="weimg2">
		<li>
			<strong><?php if ( zm_get_option('weixin_g') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_g'); ?><?php } ?></strong>
		</li>
		<li><?php if ( zm_get_option('weixin_g_w') == '' ) { ?><?php } else { ?><?php echo zm_get_option('weixin_g_w'); ?><?php } ?></li>
		<li>
			<img src="<?php echo zm_get_option('weixin_g_img'); ?>" alt="weinxin" />
		</li>
	</ul>
	<div class="clear"></div>
</div>
<?php } ?>