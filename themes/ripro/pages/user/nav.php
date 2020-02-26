<?php 
global $current_user;
$CaoUser = new CaoUser($current_user->ID);

function this_user_nav_link($icon,$link='',$name=''){
	$action = (!empty($_GET['action'])) ? strtolower($_GET['action']) : 'index' ;
	$href= esc_url(add_query_arg( array( 'action' => $link ), home_url('/user') ));
	$is_active = ($action == $link) ? 'active' : '' ;
	$a_html = '<a href="'.$href.'" class="'.$is_active.'"><i class="fa fa-'.$icon.'"></i> '.$name.'</a></li>';
	return $a_html;
}
?>

<div class="col-xs-12 col-sm-12 col-md-3">
	<div class="author-card sidebar-card">
        <div class="author-infos">

            <div class="author_avatar">
                <?php echo get_avatar($current_user->user_email); ?>
            </div>
            <div class="author">
                <h4><?php echo $current_user->display_name;?></h4>
                <?php 
	                if ($CaoUser->vip_status()) {
	                	echo '<p><span class="label label-warning"><i class="fa fa-diamond"></i> '.$CaoUser->vip_name().'用户</span></p>';
	                	echo '<p>特权到期时间：'.$CaoUser->vip_end_time().'</p>';
	                }else{
	                	echo '<p><span class="label label-default"><i class="fa fa-user"></i> '.$CaoUser->vip_name().'用户</span><span style=" font-size: 12px; margin-left: 10px; color: red; ">已到期</span></p>';
	                	echo '<p>特权到期时间：'.$CaoUser->vip_end_time().'</p>';
	                }
                ?>
            </div>
        </div>
    </div>
	<div class="edit--profile-area">
	    <ul class="edit--profile-links list-unstyled mb-0">
	    	<li><?php echo this_user_nav_link('user','index','我的信息')?></li>
            <li><?php echo this_user_nav_link('diamond','vip','我的会员')?></li>
	    	<li><?php echo this_user_nav_link('credit-card','charge','充值中心')?></li>
            <!-- <li><?php //echo this_user_nav_link('list-alt','order','充值记录')?></li> -->
	    	<li><?php echo this_user_nav_link('cloud-download','mypay','已购资源')?></li>
	    	<?php if (_cao('is_nav_myfav')): ?>
		    	<li><?php echo this_user_nav_link('star','myfav','我的收藏')?></li>
	    	<?php endif; ?>
	    	<?php if (_cao('is_nav_write')): ?>
		    	<li><?php echo this_user_nav_link('file-text','mypost','我的文章')?></li>
		    	<li><?php echo this_user_nav_link('pencil','write','发布资源')?></li>
	    	<?php endif; ?>
	    	<?php if (_cao('is_nav_ref')): ?>
            	<li><?php echo this_user_nav_link('paper-plane','ref','推广佣金')?></li>
            <?php endif; ?>
	    	<li><?php echo this_user_nav_link('key','password','修改密码')?></li>
	    	<li><a href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa fa-sign-out"></i> 退出登录</a></li>
	    </ul>
	</div>
</div>

