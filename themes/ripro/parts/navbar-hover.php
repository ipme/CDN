<?php
  global $current_user;
  $CaoUser = new CaoUser($current_user->ID);
  $site_money_ua = _cao('site_money_ua');
?>
<div class="header-dropdown header__group header__group_user header__style_user">

  <div class="header__cat">
      <a class="user-pbtn" href="<?php echo esc_url(home_url('/user')) ?>"><?php echo get_avatar($current_user->user_email); ?>
      <?php if(!_cao('is_navbar_ava_name','0')){
        echo '<span>'.$current_user->display_name.'</span>';
      }?>
      </a>
  </div>

  <div class="header__dropdown">
    <div class="header-box">
      <div class="refresh-header-top">
        <div class="header-top">
          <?php echo get_avatar($current_user->user_email); ?>
            <div class="">
              <span>
                <a class="user-names" href="<?php echo esc_url(home_url('/user'))?>"><?php echo $current_user->display_name;?></a>
                  <?php echo ' <i class="wp wp-VIP"> ' .$CaoUser->vip_name().' </i>';?>
                  <?php echo ' <i class="group-name"> '.$CaoUser->vip_end_time().'</i> ';?>
              </span>
              <p id="buy-vip" rel-vipid="3">
                <?php 
                  if ($CaoUser->vip_status()) {
                      echo '尊敬的'.$CaoUser->vip_name().'会员您好，欢迎回来！';
                  }else{
                      echo '加入'._cao('site_vip_name').'，享受折扣下载全站资源，享受VIP特权。';
                  }
                ?>
              </p>
            </div>
            <a href="<?php echo wp_logout_url( home_url() ); ?>" class="logout">退出</a>
          </div>
      </div>

      <div class="header-center">
        <div class="md-l">
          <span class="md-tit">我的钱包</span>
          <span class="jinbi" title="现有余额：<?php echo $CaoUser->get_balance();?>"><i></i>现有余额：<?php echo $CaoUser->get_balance();?> </span>
          <span class="dou" title="消费金额：<?php echo $CaoUser->get_consumed_balance();?>"><i></i>消费金额：<?php echo $CaoUser->get_consumed_balance();?></span>
          <a href="/user?action=charge" class="pay-credit" >充值</a>
        </div>
        <div class="md-r">
        <div class="md-t">
        <span><?php echo _cao('site_vip_name');?>会员</span>
        <?php 
          if ($CaoUser->vip_status()) {
              echo '<p>到期时间：'.$CaoUser->vip_end_time().'</p><a href="/user?action=vip" class="pay-vip">续费</a>';
          }else{
              echo '<p>'._cao('navbar_newhover_text1').'</p><a href="/user?action=vip" class="pay-vip">开通</a>';
          }
        ?>
      </div>
      <div class="md-b">
        <span>永久<?php echo _cao('site_vip_name');?>会员</span>
        <?php if (is_boosvip_status($current_user->ID)) {
            echo '您已开通永久'._cao('site_vip_name').'特权';
        }else{
            echo '<p>'._cao('navbar_newhover_text2').'</p><a href="/user?action=vip" class="pay-vip">升级</a>';
        }?>

        
      </div>
    </div>
  </div>
      <?php if (_cao('navbar_newhover_isbtn','1')) : ?> 
      <div class="header-bottom">
        <ul>
          <li><a href="/user?action=myfav"><i class="ico_1"></i>我的收藏</a></li>
          <li><a href="/user?action=mypost"><i class="ico_2"></i>我的文章</a></li>
          <li><a href="/user?action=password"><i class="ico_3"></i>安全中心</a></li>
          <li><a href="/user?action=mypay"><i class="ico_4"></i>我的订单</a></li>
          <?php if(in_array( 'administrator', $current_user->roles )): ?>
            <li><a target="_blank" href="<?php echo home_url('/wp-admin/') ?>"><i class="ico_5"></i>后台管理</a></li>
          <?php else : ?>
            <li><a href="/user?action=write"><i class="ico_5"></i>我要投稿</a></li>
          <?php endif; ?>
        </ul>
      </div>
    <?php endif;?>
    </div>
  </div>
</div>