<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( get_post_meta($post->ID, 'phone', true) ) : ?>
	<?php $postauthor = get_post_meta($post->ID, 'postauthor', true); ?>
	<?php $authorurl = get_post_meta($post->ID, 'authorurl', true); ?>
	<?php $authoremail = get_post_meta($post->ID, 'authoremail', true); ?>
	<?php $phone = get_post_meta($post->ID, 'phone', true); ?>
	<?php $authorqq = get_post_meta($post->ID, 'authorqq', true); ?>
	<?php $remarks = get_post_meta($post->ID, 'remarks', true); ?>
	<span class="tougao-info-main">
		<ul class="tougao-info">
			<li class="tougao-info-column">昵称：<?php echo $postauthor; ?></li>
			<li>邮件：<?php echo $authoremail; ?></li>
			<li>电话：<?php echo $phone; ?></li>
			<li>QQ：<?php echo $authorqq; ?></li>
			<li>网址：</strong><?php echo $authorurl; ?></li>
		</ul>
		<?php if ( get_post_meta($post->ID, 'remarks', true) ) : ?><p class="tougao-beizi">备注：<br /><?php echo $remarks; ?></p><?php endif; ?>
		<div class="clear"></div>
	</span>
<?php endif; ?>