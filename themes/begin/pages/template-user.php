<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
/*
Template Name: 用户中心
*/
?>
<?php if(is_user_logged_in()){?>
<?php get_header(); ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/user-center.css" />
<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/responsive-tabs.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#usertab').easyResponsiveTabs({
		type: 'vertical',
		width: 'auto',
		fit: true,
		closed: 'accordion',
		tabidentify: 'hor_1',
		activate: function(event) {
			var $tab = $(this);
			var $info = $('#nested-tabInfo2');
			var $name = $('span', $info);
			$name.text($tab.text());
			$info.show();
		}
	});
});
</script>

<div id="personal">
	<div id="container">
		<div id="usertab">
			<ul class="resp-tabs-list hor_1">
				<li><i class="be be-businesscard"></i><?php _e( '我的信息', 'begin' ); ?></li>
				<li><i class="be be-personoutline"></i><?php _e( '修改资料', 'begin' ); ?></li>
				<li><i class="be be-speechbubble"></i><?php _e( '我的评论', 'begin' ); ?></li>
				<li><i class="be be-file"></i><?php _e( '我的文章', 'begin' ); ?></li>
				<?php if (function_exists( 'fep_create_database' )) { ?>
				<li><i class="be be-email"></i><?php _e( '站内消息', 'begin' ); ?></li>
				<?php } ?>
				<?php if ( zm_get_option('tou_url') == '' ) { ?>
				<?php } else { ?>
				<li><a href="<?php echo get_permalink( zm_get_option('tou_url') ); ?>"><i class="be be-edit"></i><?php _e( '我要投稿', 'begin' ); ?></a></li>
				<?php } ?>
			</ul>

			<div class="resp-tabs-container hor_1">

				<div>
					<h4><?php _e( '我的信息', 'begin' ); ?></h4>
					<?php get_template_part( 'inc/users/my-inf' ); ?>
					<div class="clear"></div>
				</div>

				<div>
					<h4><?php _e( '个人资料', 'begin' ); ?></h4>
					<?php get_template_part( 'inc/users/my-data' ); ?>
					<div class="clear"></div>
				</div>

				<div>
					<?php
						global $wpdb;
						$author_id = $current_user->ID;
						$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
					?>
					<h4><?php _e( '我的评论', 'begin' ); ?><span class="m-number">（ <?php echo $comment_count;?> ）<span></h4>
					<?php get_template_part( 'inc/users/my-comment' ); ?>
				</div>

				<div>
					<h4><?php _e( '我的文章', 'begin' ); ?><span class="m-number">（ <?php $userinfo=get_userdata(get_current_user_id()); $authorID= $userinfo->ID; echo num_of_author_posts($authorID); ?> ）<span></h4>
					<?php get_template_part( 'inc/users/my-post' ); ?>
				</div>

				<?php if (function_exists( 'fep_create_database' )) { ?>
					<div>
						<h4><?php _e( '站内消息', 'begin' ); ?></h4>
						<?php get_template_part( 'inc/users/front-pm' ); ?>
						<div class="clear"></div>
					</div>
				<?php } ?>

				<div>
					<h4><?php _e( '我要投稿', 'begin' ); ?></h4>
					<?php get_template_part( 'inc/users/my-tou' ); ?>
					<div class="clear"></div>
				</div>

			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>


<?php get_footer(); ?>
<?php }else{
 wp_redirect( home_url() );
 exit;
}?>