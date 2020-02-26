<?php 
/**
 * template name: 用户中心(/user)
 */
date_default_timezone_set('Asia/Shanghai');
if(!is_user_logged_in() || !is_site_shop_open()){
	header("Location:".home_url());
	exit();
}
get_header();
global $current_user;
$part_action = (isset($_GET['action'])) ? strtolower($_GET['action']) : '' ;

?>

<?php get_template_part( 'pages/user/header');?>


<!-- #user-profile
============================================= -->

<div class="container">
    <section id="user-profile" class="user-profile">
        <div class="row">
            <?php
            if ($part_action != 'addproduct') {
               get_template_part( 'pages/user/nav');
            }?>
            <!-- .col-md-4 -->
            <?php
	            if ($part_action) {
					get_template_part( 'pages/user/'.$part_action);
				}else{
					get_template_part( 'pages/user/index');
				}
			?>
            
        </div>
    </section>
</div>
<!-- #user-profile  end -->



<?php get_footer(); ?>