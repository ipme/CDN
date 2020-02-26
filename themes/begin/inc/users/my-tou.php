<?php if ( zm_get_option('tou_url') == '' ) { ?>
<?php } else { ?>
<div class="m-tou">
	<p><a href="<?php echo get_permalink( zm_get_option('tou_url') ); ?>" target="_blank"><i class="be be-edit"></i> <?php _e( '点击跳转到投稿页面', 'begin' ); ?></a></p>
</div>
<?php } ?>