<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( wp_is_mobile() ) { ?><?php if ( zm_get_option('m_phone') == '' ) { ?><?php } else { ?><li class="phone-mobile"><a target="_blank" rel="external nofollow" href="tel:<?php echo zm_get_option('m_phone'); ?>"><i class="be be-phone"></i></a></li><?php } ?><?php } ?>
<li class="qqonline">
	<?php if ( wp_is_mobile() ) { ?>
		<a class="qq-mobile" target="_blank" rel="external nofollow" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo zm_get_option('qq_id'); ?>&site=qq&menu=yes"><i class="be be-qq"></i></a>
	<?php } else { ?>
	<div class="online">
		<a  class="ms"href="javascript:void(0)" ><i class="be be-qq"></i></a>
	</div>
		<?php if (zm_get_option('qr_img')) { ?>
			<div class="qqonline-box">
		<?php } else { ?>
			<div class="qqonline-box qq-b">
		<?php } ?>
		<div class="qqonline-main ms">
			<?php if ( zm_get_option('weixing_qr') == '' ) { ?>
			<?php } else { ?>
				<div class="nline-wiexin">
					<h4>微信</h4>
					<img title="微信" alt="微信" src="<?php echo zm_get_option('weixing_qr'); ?>"/>
				</div>
			<?php } ?>
			<div class="nline-qq">
				<h4><?php echo zm_get_option('qq_name'); ?></h4>
				<a target="_blank" rel="external nofollow" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo zm_get_option('qq_id'); ?>&site=qq&menu=yes" title="QQ在线咨询" rel="external nofollow"><i class="be be-qq"></i></a>
			</div>
			<div class="clear"></div>
			<div class="nline-phone">
				<?php if ( zm_get_option('m_phone') == '' ) { ?>
					<?php if ( zm_get_option('l_phone') == '' ) { ?><?php } else { ?><i class="be be-favoriteoutline"></i><?php echo zm_get_option('l_phone'); ?><?php } ?>
				<?php } else { ?>
					<i class="be be-phone"></i><?php echo zm_get_option('m_phone'); ?>
				<?php } ?>
				<?php if ( zm_get_option('t_phone') == '' ) { ?><?php } else { ?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<i class="be be-phone"></i><?php echo zm_get_option('t_phone'); ?>
				<?php } ?>
			</div>
		</div>
		<span class="qq-arrow"><span class="arrow arrow-y"><i class="be be-playarrow"></i></span><span class="arrow arrow-x"><i class="be be-playarrow"></i></span></span>
	</div>
	<?php } ?>
</li>