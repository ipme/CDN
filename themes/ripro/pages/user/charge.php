<?php 
global $current_user;
$CaoUser = new CaoUser($current_user->ID);
//充值中心 OK
?>

<div class="col-xs-12 col-sm-12 col-md-9">

    <form class="mb-0">
        <?php if (_cao('is_userpage_charge_head')) {
            get_template_part( 'pages/user/header-card');
        }?>

        <div class="form-box">
            <div class="row">
                        <div class="col-md-12">
                            <div class="charge">
                                <div class="modules__title">
                                    <h4><i class="<?php echo _cao('site_money_icon'); ?>"></i> 余额充值中心（充值比例：1元=<?php echo _cao('site_change_rate')._cao('site_money_ua'); ?>）</h4>
                                    <a class="btn-order" href="<?php echo esc_url(home_url('/user?action=order'))?>"><span class="label label-success">充值记录</span></a>
                                </div>

                                <div class="modules__content">
                                    <?php if (_cao('is_alipay') || _cao('is_weixinpay') || _cao('is_payjs') || _cao('is_xunhupay') || _cao('is_xunhualipay') || _cao('is_codepay') ) : $cdk_display = 'style="display: none;"';?>
                                    <div id="yuecz">
                                        <p class="subtitle">快速选择充值</p>
                                        <div class="amounts">
                                            <ul>
                                                <li data-price="10">
                                                    <p>10<?php echo _cao('site_money_ua') ?></p>
                                                </li>
                                                <li data-price="20">
                                                    <p>20<?php echo _cao('site_money_ua') ?></p>
                                                </li>
                                                <li data-price="50">
                                                    <p>50<?php echo _cao('site_money_ua') ?></p>
                                                </li>
                                                <li data-price="100">
                                                    <p>100<?php echo _cao('site_money_ua') ?></p>
                                                </li>
                                                <li data-price="500">
                                                    <p>500<?php echo _cao('site_money_ua') ?></p>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- end /.amounts -->
                                        <div class="or"></div>
                                        <p class="subtitle">输入充值<?php echo _cao('site_money_ua') ?>数量</p>
                                        <div class="custom_amount">
                                            <div class="form-group">
                                                <div class="input-group charge-form">
                                                    <input type="number" name="charge_num" id="charge_num" class="form-control" placeholder="输入整数，最低充值<?php echo _cao('min_cahrge_num','1')._cao('site_money_ua');?>起">
                                                    <div id="rmbnum" class="input-group-btn" data-rate="<?php echo _cao('site_change_rate')?>">￥<b>0.00</b></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else:  $cdk_display = '';?>
                                    <?php endif;?>
                                    <!-- 卡密充值 -->
                                    <?php if (_cao('is_cdk_charge','1') ) : ?>
                                    <div id="kamidiv" <?php echo $cdk_display;?>>
                                        <p class="subtitle">卡密充值 <span class="badge">CDK</span></p>
                                        <div class="collapse" id="collapsecdk">
                                          <div class="well">
                                            <div class="form-group mb-10">
                                                <label for="qr_weixin">卡密充值单位 / <?php echo _cao('site_money_ua') ?></label>
                                                <div class="input-group">
                                                  <input type="text" class="form-control" name="cdkcode" value="" placeholder="输入12位卡密">
                                                  <span class="input-group-btn">
                                                    <button class="go-cdk btn btn--danger" type="button" data-nonce="<?php echo wp_create_nonce('caoclick-' . $current_user->ID); ?>">使用卡密</button>
                                                  </span>
                                                </div><!-- /input-group -->
                                            </div>
                                          </div>
                                        </div>
                                        <?php
                                        $cdk_charge_href = _cao('cdk_charge_href');
                                        if ($cdk_charge_href != '') {
                                            echo '<a class="btn btn--secondary btn--block" target="_blank" href="'.$cdk_charge_href.'">前往发卡平台购买卡密</a>';
                                         }
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
        <!-- 付款选项 -->
        <?php if (_cao('is_alipay') || _cao('is_weixinpay') || _cao('is_payjs') || _cao('is_xunhupay') || _cao('is_xunhualipay') || _cao('is_codepay')) : ?>
        <div class="form-box">
            <p class="subtitle">充值方式</p>
            
            <div class="row">
                <div class="col-md-9">
                        <div class="input-group">
                            <div class="row">
                            <?php if (_cao('is_alipay')) : ?>
                            <div style=" margin-right: 20px; ">
                            <span class="flex-center payradio">
                                  <input type="radio" id="pay_ali" name="pay_type" value="1" checked="">
                                  <label class="" for="pay_ali">
                                    <img src="<?php echo get_template_directory_uri() . '/assets/icons/alipay.png';?>" height="35" class="mr-2">
                                  </label>
                            </span>
                            </div>
                            <?php endif; ?>
                            <?php if (_cao('is_weixinpay')) : ?>
                            <div style=" margin-right: 20px; ">
                                <span class="flex-center payradio">
                                      <input type="radio" id="pay_weixin" name="pay_type" value="2">
                                      <label class="" for="pay_weixin">
                                        <img src="<?php echo get_template_directory_uri() . '/assets/icons/weixin.png';?>" height="35" class="mr-2">
                                      </label>
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (_cao('is_payjs')) : ?>
                            <div style=" margin-right: 20px; ">
                                <span class="flex-center payradio">
                                      <input type="radio" id="pay_payjs" name="pay_type" value="4">
                                      <label class="" for="pay_payjs">
                                        <img src="<?php echo get_template_directory_uri() . '/assets/icons/weixin.png';?>" height="35" class="mr-2">
                                      </label>
                                </span>
                            </div>
                            <?php endif; ?>

                            <?php if (_cao('is_xunhupay')) : ?>
                            <div style=" margin-right: 20px; ">
                                <span class="flex-center payradio">
                                      <input type="radio" id="pay_xunhupay" name="pay_type" value="5">
                                      <label class="" for="pay_xunhupay">
                                        <img src="<?php echo get_template_directory_uri() . '/assets/icons/weixin.png';?>" height="35" class="mr-2">
                                      </label>
                                </span>
                            </div>
                            <?php endif; ?>

                            <?php if (_cao('is_xunhualipay')) : ?>
                            <div style=" margin-right: 20px; ">
                                <span class="flex-center payradio">
                                      <input type="radio" id="pay_xunhualipay" name="pay_type" value="6">
                                      <label class="" for="pay_xunhualipay">
                                        <img src="<?php echo get_template_directory_uri() . '/assets/icons/alipay.png';?>" height="35" class="mr-2">
                                      </label>
                                </span>
                            </div>
                            <?php endif; ?>

                            <?php if (_cao('is_codepay')) : $codepay = _cao('codepay'); ?>

                                <?php if ($codepay['pay_mode']=='all' || $codepay['pay_mode']=='weixin') : ?>
                                    <div style=" margin-right: 20px; ">
                                        <span class="flex-center payradio">
                                              <input type="radio" id="pay_codepaywx" name="pay_type" value="8">
                                              <label class="" for="pay_codepaywx">
                                                <img src="<?php echo get_template_directory_uri() . '/assets/icons/weixin.png';?>" height="35" class="mr-2">
                                              </label>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($codepay['pay_mode']=='all' || $codepay['pay_mode']=='alipay') : ?>
                                    <div style=" margin-right: 20px; ">
                                        <span class="flex-center payradio">
                                              <input type="radio" id="pay_codepayali" name="pay_type" value="7">
                                              <label class="" for="pay_codepayali">
                                                <img src="<?php echo get_template_directory_uri() . '/assets/icons/alipay.png';?>" height="35" class="mr-2">
                                              </label>
                                        </span>
                                    </div>
                                <?php endif; ?>


                            <?php endif; ?>


                            <?php if (_cao('is_cdk_charge','1')) : ?>
                            <div style=" margin-right: 20px; ">
                                <span class="flex-center payradio">
                                      <input type="radio" id="kami" name="pay_type" value="3">
                                      <label class="" for="kami">
                                        <img src="<?php echo get_template_directory_uri() . '/assets/icons/kami.png';?>" height="35" class="mr-2">
                                      </label>
                                </span>
                            </div>
                            <?php endif; ?>


                          </div>
                        </div>
                </div>
                 <div class="col-md-3">
                    <div class="input-group pull-right">
                        <button type="button" class="go-charge btn btn--secondary" data-nonce="<?php echo wp_create_nonce('caoclick-' . $current_user->ID); ?>">立即充值</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </form>
</div>