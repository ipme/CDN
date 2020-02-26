<?php
// 开通会员
global $current_user;
$CaoUser = new CaoUser($current_user->ID);
?>

<div class="col-xs-12 col-sm-12 col-md-9">
	
	<form class="mb-0">
		<?php if (_cao('is_userpage_vip_head')) {
			get_template_part( 'pages/user/header-card');
		}?>
        
        <div class="form-box">
            <div class="row">
                <div class="col-md-12">
                    <div class="charge">
                        <div class="modules__title">
                            <h4><i class="fa fa-diamond"></i> <?php echo $CaoUser->vip_name().'用户 · 特权到期时间：'.$CaoUser->vip_end_time() ?> · 选择套餐购买或续费</h4>
                        </div>
                        <div class="pt-30">
                            <div class="payvip-box">
					            <div class="row">
					            	<?php
					            	$vip_pay_setting = _cao('vip-pay-setting');
					            	foreach ($vip_pay_setting as $key => $item) {
					            		echo '<div class="col-md-4 col-sm-4">';
					            		echo '<div class="vip-info" data-id="'.$key.'" style="background:'.$item['color'].';">';
					            		echo '<span class="vipc"><i class="fa fa-diamond"></i> '._cao('site_vip_name').'</span>';
					            		if ($item['daynum'] == 9999) {
					            			echo '<small style="color:'.$item['color'].';">终身永久</small>';
					            		}else{
					            			echo '<small style="color:'.$item['color'].';">'.$item['daynum'].'天</small>';
					            		}
					            		
					            		echo '<p>购买价格</p>';
					            		echo '<h3>'.$item['price']._cao('site_money_ua').'</h3>';
					            		echo '</div></div>';
					            	}
					                ?>
					            </div>
					        </div>
                            

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
	            	<input type="hidden" name="pay_id" value="">
	            	<button type="button" class="go-payvip btn btn--primary" disabled="true" data-nonce="<?php echo wp_create_nonce('caoclick-' . $current_user->ID); ?>"><i class="<?php echo _cao('site_money_icon'); ?>"></i> <?php echo _cao('site_money_ua')?>支付</button>
            	</div>

            </div>

        </div>
    </form>
</div>