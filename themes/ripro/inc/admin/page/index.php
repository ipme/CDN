<?php
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $order_table_name,$paylog_table_name,$coupon_table_name,$balance_log_table_name;



//////// 构造SQL START ////////
//php获取今日开始时间戳和结束时间戳
$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
//php获取本月起始时间戳和结束时间戳
$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
$endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));


// 总订单 
$order_total_count = $wpdb->get_var("SELECT COUNT(id) FROM $order_table_name");

$order_total_sum = $wpdb->get_var("SELECT SUM(order_price) FROM $order_table_name'");
$order_total_sum_ok = $wpdb->get_var("SELECT SUM(order_price) FROM $order_table_name WHERE status =1");

// 本月
$order_month_count = $wpdb->get_var("SELECT COUNT(id) FROM $order_table_name WHERE create_time > $beginThismonth AND create_time < $endThismonth");
$order_month_sum = $wpdb->get_var("SELECT SUM(order_price) FROM $order_table_name WHERE create_time > $beginThismonth AND create_time < $endThismonth");
$order_month_sum_ok = $wpdb->get_var("SELECT SUM(order_price) FROM $order_table_name WHERE status =1 AND create_time > $beginThismonth AND create_time < $endThismonth");

// 今天

$order_today_count = $wpdb->get_var("SELECT COUNT(id) FROM $order_table_name WHERE create_time > $beginToday AND create_time < $endToday");
$order_today_count = ($order_today_count) ? $order_today_count : 0 ;

$order_today_sum = $wpdb->get_var("SELECT SUM(order_price) FROM $order_table_name WHERE create_time > $beginToday AND create_time < $endToday");
$order_today_sum = ($order_today_sum) ? $order_today_sum : '0.00' ;
$order_today_sum_ok = $wpdb->get_var("SELECT SUM(order_price) FROM $order_table_name WHERE status =1 AND create_time > $beginToday AND create_time < $endToday");
$order_today_sum_ok = ($order_today_sum_ok) ? $order_today_sum_ok : '0.00' ;

//////// 构造SQL END ////////

?>

<!-- 主页面 -->
<div class="wrap">

	<h1 class="wp-heading-inline">统计/总览</h1>
    <hr class="wp-header-end">
    <br/>
	<div class="layui-row layui-col-space15">  
		<div class="layui-col-md8">
			<div class="layui-card">
		        <div class="layui-card-header">收入统计</div>
		        <div class="layui-card-body" style=" background-color: #dadada; ">
					<div class="layui-row layui-col-space15">
						<div class="layui-col-sm6 layui-col-md4">
					      <div class="layui-card">
					        <div class="layui-card-header">全部订单<span class="layuiadmin-badge"><?php echo sprintf('%0.2f', $order_total_count); ?> 条</span></div>
					        <div class="layui-card-body layuiadmin-card-list">
					          <p class="layuiadmin-big-font"><small style=" font-size: 15px; ">已付款：</small>￥ <?php echo sprintf('%0.2f', $order_total_sum_ok); ?></p>
					          <p>订单总额 <span class="layuiadmin-span-color">￥ <?php echo $order_total_sum ?></span></p>
					        </div>
					      </div>
					    </div>
					    
					    <div class="layui-col-sm6 layui-col-md4">
					      <div class="layui-card">
					        <div class="layui-card-header">本月订单<span class="layuiadmin-badge"><?php echo $order_month_count ?> 条</span></div>
					        <div class="layui-card-body layuiadmin-card-list">
					        	<p class="layuiadmin-big-font"><small style=" font-size: 15px; ">已付款：</small>￥ <?php echo sprintf('%0.2f', $order_month_sum_ok); ?></p>
					          <p>订单总额 <span class="layuiadmin-span-color">￥ <?php echo sprintf('%0.2f', $order_month_sum); ?></span></p>
					        </div>
					      </div>
					    </div>

					    <div class="layui-col-sm6 layui-col-md4">
					      <div class="layui-card">
					        <div class="layui-card-header">今日订单<span class="layuiadmin-badge"><?php echo $order_today_count ?> 条</span></div>
					        <div class="layui-card-body layuiadmin-card-list">
					        	<p class="layuiadmin-big-font"><small style=" font-size: 15px; ">已付款：</small>￥ <?php echo sprintf('%0.2f', $order_today_sum_ok); ?></p>
					          <p>订单总额 <span class="layuiadmin-span-color">￥ <?php echo sprintf('%0.2f', $order_today_sum); ?></span></p>
					        </div>
					      </div>
					    </div>


					</div>
				</div>
			</div>
			<?php
			//////// 构造SQL START ////////
			$sql = "SELECT * FROM {$balance_log_table_name}";
			$sql .= ' WHERE 1=1';
			$sql .= ' ORDER BY time DESC';
			$sql .= ' LIMIT 10';
			$result = $wpdb->get_results($sql);
			//////// 构造SQL END ////////
			?>
			<div class="layui-card">
		        <div class="layui-card-header">最新动态</div>
		        <div class="layui-card-body">
					
					<dl class="layuiadmin-card-status">
					<?php 
					if($result) {
						foreach($result as $item){
							$userss = get_user_by('id',$item->user_id);
							$user_loginname = ($userss->user_login) ? $userss->user_login : '游客' ;
					?>
			            <dd>
			            	<div class="layui-status-img"><a href="javascript:;"><img src="<?php echo _the_theme_avatar() ?>"></a></div>
			                <div>
			                  <p><?php echo $user_loginname ?> ： <?php echo $item->note?></p>
			                  <span><?php echo date('Y-m-d H:i:s',$item->time)?></span>
			                </div>
			            </dd>

             		<?php 
						}
					}
					?>

            		</dl>

				</div>
			</div>


		</div>
		<div class="layui-col-md4">
			<div class="layui-card">
		        <div class="layui-card-header">便捷导航</div>
		        <div class="layui-card-body">
		          <div class="layuiadmin-card-link">
		            <a href="<?php echo admin_url('/admin.php?page=cao_order_page') ?>">充值记录</a>
		            <a href="<?php echo admin_url('/admin.php?page=cao_paylog_page') ?>">资源订单</a>
		            <a href="<?php echo admin_url('/admin.php?page=cao_cdk_page') ?>">卡密记录</a>
		            <a href="<?php echo admin_url('/admin.php?page=cao_cdk_page&action=add') ?>">添加卡密</a>
		            <a href="<?php echo admin_url('/users.php') ?>">用户管理</a>
		            <a href="<?php echo admin_url('/admin.php?page=cao_ref_page') ?>">提现管理</a>
		            <a href="<?php echo admin_url('/admin.php?page=cao_balance_page') ?>">余额明细</a>
		          </div>        
		        </div>
		    </div>
		</div>
		<div class="layui-col-md4">
			<div class="layui-card">
		        <div class="layui-card-header">其他数据</div>
		        <div class="layui-card-body">
		          <div class="layui-carousel layadmin-carousel layadmin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%;">
                  <div carousel-item="">
                    <ul class="layui-row layui-col-space10 layui-this">
                      <li class="layui-col-xs6">
                        <a lay-href="app/content/comment.html" class="layadmin-backlog-body">
                          <h3>文章总数</h3>
                          <p><cite><?php $count_posts = wp_count_posts(); echo $published_posts =$count_posts->publish;?></cite></p>
                        </a>
                      </li>
                      <li class="layui-col-xs6">
                        <a lay-href="app/content/comment.html" class="layadmin-backlog-body">
                          <h3>资源文章数</h3>
                          <?php $sqls = $wpdb->get_var($wpdb->prepare("SELECT COUNT(post_id) FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s", 'cao_status',1));
                          ?>
                          <p><cite><?php echo $sqls ? $sqls : '0' ?></cite></p>
                        </a>
                      </li>

                      <li class="layui-col-xs6">
                        <a lay-href="app/forum/list.html" class="layadmin-backlog-body">
                          <h3>用户总数</h3>
                          <p><cite><?php $users = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users"); echo $users; ?></cite></p>
                        </a>
                      </li>
                      <li class="layui-col-xs6">
                        <a lay-href="template/goodslist.html" class="layadmin-backlog-body">
                          <h3><?php echo _cao('site_vip_name')?>会员总数</h3>
                          <?php // 查询meta
    						$user_vip = $wpdb->get_var($wpdb->prepare("SELECT COUNT(user_id) FROM $wpdb->usermeta WHERE meta_key=%s AND meta_value=%s", 'cao_user_type', 'vip'));
    						?>
                          <p><cite><?php echo $user_vip ? $user_vip : '0'; ?></cite></p>
                        </a>
                      </li>
                      <li class="layui-col-xs6">
                        <a lay-href="template/goodslist.html" class="layadmin-backlog-body">
                          <h3>总余额池 / <?php echo _cao('site_money_ua')?></h3>
                          <?php // 查询meta
    						$sqls = $wpdb->get_var($wpdb->prepare("SELECT SUM(meta_value) FROM $wpdb->usermeta WHERE meta_key=%s", 'cao_balance'));
    						?>
                          <p><cite><?php echo $sqls ? sprintf('%0.2f', $sqls) : '0' ?></cite></p>
                        </a>
                      </li>
                      <li class="layui-col-xs6">
                        <a lay-href="template/goodslist.html" class="layadmin-backlog-body">
                          <h3>累计佣金池 / ￥</h3>
                          <?php // 查询meta
    						$sqls = $wpdb->get_var($wpdb->prepare("SELECT SUM(meta_value) FROM $wpdb->usermeta WHERE meta_key=%s", 'cao_total_bonus'));
    						?>
                          <p><cite><?php echo $sqls ? sprintf('%0.2f', $sqls) : '0' ?></cite></p>
                        </a>
                      </li>
                      

                    </ul>
                    
                  </div>
		    </div>
		</div>


	</div>
	


    <script>
            jQuery(document).ready(function($){

            });
	</script>
</div>
