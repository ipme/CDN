<?php

date_default_timezone_set('Asia/Shanghai');
global $current_user,$order_table_name,$paylog_table_name;
$user_id = $current_user->ID;
?>


<?php

$the_url = home_url('/user?action=order');
$total_count = $wpdb->get_var("SELECT COUNT(id) FROM $order_table_name WHERE user_id = $user_id"); //总数
$perpage = 20;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$offset = $perpage*($paged-1);
$results = $wpdb->get_results("SELECT * FROM $order_table_name WHERE user_id = $user_id ORDER BY create_time DESC limit $offset,$perpage");

?>

<div class="col-xs-12 col-sm-12 col-md-9">
	<!-- 筛选 -->
	<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <form class="mb-0 ">
                <div class="form_title_area">
                   <h6 class="mb-0">最近20条订单充值记录</h6>
                   <small>当前共<?php echo $total_count ?>条记录</small>
                   <!-- 列表 -->
					<div class="row pt-20">
					    <div class="col-md-12">
					        <div class="table-responsive bgcolor-fff">
					            <table class="table table-hover ">
					                <thead>
					                    <tr>
					                        <th>日期</th>
					                        <th>订单号</th>
					                        <th>金额</th>
					                        <th>类型</th>
					                        <th>支付方式</th>
					                        <th>支付时间</th>
					                        <th>订单状态</th>
					                    </tr>
					                </thead>
					                <tbody>
					                	<?php 
					                		if(isset($results)) :
												foreach($results as $item){
													$class = ($item->status == 1) ? 'success' : '' ;
													echo '<tr class="'.$class.'" date-id="'.$item->id.'">';
								                        echo '<td class="date">'.date('Y-m-d H:i:s',$item->create_time).'</td>';
								                        echo '<td class="trade_no">'.$item->order_trade_no.'</td>';
								                        echo '<td class="price">'.$item->order_price.'</td>';
								                        if ($item->order_type == 'charge') {
								                        	echo '<td class="price">余额充值</td>';
								                        }else{
								                        	echo '<td class="price">其他</td>';
								                        }
								                        if ($item->pay_type == 1 || $item->pay_type == 6) {
								                        	echo '<td class="type"><span class="label label-primary">支付宝</span></td>';
								                        }elseif ($item->pay_type == 2 || $item->pay_type == 4 || $item->pay_type == 5){
								                        	echo '<td class="type"><span class="label label-success">微信</span></td>';
								                        }else{
								                        	echo '<td class="type"><span class="label label-success">其他</span></td>';
								                        }
								                        if ($item->pay_time>0) {
								                        	echo '<td class="pay_date">'.date('Y-m-d H:i:s',$item->pay_time).'</td>';
								                        }else{
								                        	echo '<td class="pay_date">###</td>';
								                        }
								                        
								                        if ($item->status == 1) {
								                        	echo '<td class="status"><span class="label label-primary">支付成功</span></td>';
								                        }else{
								                        	echo '<td class="status"><span class="label label-danger">未支付</span></td>';
								                        }
								                        
								                    echo '</tr>';
												}
											else:
												echo '<tr><td colspan="6" align="center"><strong>没有订单</strong></td></tr>';
											endif;
										?>
					                </tbody>
					            </table>
					        </div>
					    </div>
					</div>
                </div>
            </form>
        </div>
    </div>
    
</div>
