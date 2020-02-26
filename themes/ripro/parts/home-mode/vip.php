<?php
if (is_site_shop_open()): 
// 模块 参考来自：https://www.doupir.com/ 豆皮儿UI素材 由会员投稿
$home_mode_vip = _cao('home_vip_mod');
?>
<div class="section pt-0 pb-0">
	<div class="home-vip-mod">
		<div class="container">
	      <div class="row">
	      	<div class="col-12 col-sm-6 col-md-4 col-lg-3">
		        <div class="vip-cell vip-text">
		          <h4><i class="fa fa-gift"></i> <?php echo $home_mode_vip['title'];?></h4>
		          <p><?php echo $home_mode_vip['desc'];?></p>
		        </div>
		    </div>
			<?php foreach ( $home_mode_vip['vip_group'] as $item ) : ?>
				<div class="col-12 col-sm-6 col-md-4 col-lg-3">
			        <div class="vip-cell">
				        <?php if ($item['_tehui']) : ?>
				        <span class="tehui"><i class="fa fa-diamond"></i> <?php echo $item['_tehui'];?></span>
				        <?php endif; ?>
						<span class="time"><?php echo $item['_time'];?></span>
						<div class="price" style="color:<?php echo $item['_color'];?>"><span><?php echo _cao('site_money_ua')?></span><?php echo $item['_price'];?></div>
						<p><?php echo $item['_desc'];?></p>
						<?php if (is_user_logged_in()) : ?>
						<a href="<?php echo esc_url(home_url('/user?action=vip'));?>" class="btn-sm primary" style="background:<?php echo $item['_color'];?>"><i class="fa fa-unlink"></i> 前往开通</a>
						<?php else: ?>
						<a class="login-btn btn-sm primary" style="background:<?php echo $item['_color'];?>"><i class="fa fa-user"></i> 登录购买</a>
						<?php endif; ?>
			        </div>
			    </div>
			<?php endforeach; ?>
	      </div>
		</div>
	</div>
</div>
<?php endif; ?>