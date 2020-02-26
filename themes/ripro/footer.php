</div><!-- end sitecoent --> 

	<?php
	$mode_banner = _cao('mode_banner');
	if (is_array($mode_banner) && isset($mode_banner['bgimg']) && _cao('is_footer_banner') ) : ?>

	<div class="module parallax">
		<img class="jarallax-img lazyload" data-srcset="<?php echo $mode_banner['bgimg']; ?>" data-sizes="auto" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="">
		<div class="container">
			<h4 class="entry-title">
				<?php echo wp_kses( $mode_banner['text'], array(
					'br' => array(),
				) ); ?>
			</h4>
			<?php if ( $mode_banner['primary_text'] != '' ) : ?>
				<a<?php echo _target_blank(); ?> class="button" href="<?php echo esc_url( $mode_banner['primary_link'] ); ?>"><?php echo esc_html( $mode_banner['primary_text'] ); ?></a>
			<?php endif; ?>
			<?php if ( $mode_banner['secondary_text'] != '' ) : ?>
				<a<?php echo _target_blank(); ?> class="button transparent" href="<?php echo esc_url( $mode_banner['secondary_link'] ); ?>"><?php echo esc_html( $mode_banner['secondary_text'] ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
	

	<footer class="site-footer">
		<div class="container">
			
			<?php if (_cao( 'is_diy_footer','true' ) ){
				get_template_part( 'parts/diy-footer' );
			}?>
			<?php if ( _cao( 'cao_copyright_text', '' ) != '' ) : ?>
			  <div class="site-info">
			    <?php echo _cao( 'cao_copyright_text', '' ); ?>
			    <?php if(_cao('cao_ipc_info')) : ?>
			    <a href="http://beian.miit.gov.cn" target="_blank" class="text"><?php echo _cao('cao_ipc_info')?><br></a>
			    <?php endif; ?>
			  </div>
			<?php endif; ?>
		</div>
	</footer>
	
<div class="rollbar">
	<?php if (_cao('site_kefu_qq')) : ?>
    <div class="rollbar-item tap-qq" etap="tap-qq"><a target="_blank" title="QQ咨询" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo _cao('site_kefu_qq');?>&site=qq&menu=yes"><i class="fa fa-qq"></i></a></div>
    <?php endif; ?>

	<div class="rollbar-item" etap="to_full" title="全屏页面"><i class="fa fa-arrows-alt"></i></div>
	<?php if (_cao('is_ripro_dark_btn')) : ?>
    <div class="rollbar-item tap-dark" etap="tap-dark" title="切换模式"><i class="mdi mdi-brightness-4"></i></div>
    <?php endif; ?>
	<div class="rollbar-item" etap="to_top" title="返回顶部"><i class="fa fa-angle-up"></i></div>
</div>

<div class="dimmer"></div>

<?php if (!is_user_logged_in() && is_site_shop_open()) : ?>
    <?php get_template_part( 'parts/popup-signup' ); ?>
<?php endif; ?>

<?php get_template_part( 'parts/off-canvas' ); ?>

<?php if (_cao('is_console_footer','true')) : ?>
<script>
    console.log("version：<?php echo _the_theme_name().'_v'._the_theme_version();?>");
    console.log("SQL 请求数：<?php echo get_num_queries();?>");
    console.log("页面生成耗时： <?php echo timer_stop(0,5);?>");
</script>

<?php endif; ?>

<?php if (_cao('web_js')) : ?>
<?php echo _cao('web_js');?>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>