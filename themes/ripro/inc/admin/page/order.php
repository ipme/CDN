<?php
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $order_table_name;
$charge_rate  = (int) _cao('site_change_rate'); //充值比例
$no_edit = (!empty($_GET['action']) && $_GET['action'] =='edit') ? false :true;
$remosql = (!empty($_GET['remosql']) && $_GET['remosql'] =='1') ? true :false;
$id = !empty($_GET['id']) ? (int)$_GET['id'] : 0 ;

if ($remosql && current_user_can('administrator')) {
    // 清理无效订单
    $del_order = $wpdb->query("DELETE FROM $order_table_name WHERE status = 0 ");
    echo'<div class="updated settings-error"><p>成功清理'.$del_order.'条无效订单！</p></div>';
    unset($remosql);
    unset($_GET['remosql']);
}

?>

<?php if ($no_edit) : ?>
<?php
// 主页面PHP

$perpage = 20; // 每页数量
$paged=isset($_GET['paged']) ?intval($_GET['paged']) :1;  //当前页
$offset = $perpage*($paged-1); //偏移页
//////// 构造SQL START ////////
$sql = "SELECT * FROM {$order_table_name}";
$where = ' WHERE 1=1';
$where .= ' AND order_type="charge"';
if ( !empty( $_GET['order_type'] ) ) {
    $where .= ' AND order_type="'.esc_sql($_GET['order_type']).'"';
}
if ( isset( $_GET['status'] ) && is_numeric($_GET['status']) ) {
$where .= ' AND status='.esc_sql($_GET['status']);
}
if ( !empty( $_GET['pay_type'] ) ) {
    $where .= ' AND pay_type='.esc_sql($_GET['pay_type']);
}
if ( !empty( $_GET['order_trade_no'] ) ) {
    $where .= ' AND order_trade_no="'.esc_sql($_GET['order_trade_no']).'"';
}
$orderlimti = ' ORDER BY create_time DESC';
$orderlimti .= ' LIMIT '.esc_sql($offset.','.$perpage);
$result = $wpdb->get_results($sql.$where.$orderlimti);
$total   = $wpdb->get_var("SELECT COUNT(id) FROM $order_table_name {$where}");

//////// 构造SQL END ////////
?>

<!-- 主页面 -->
<div class="wrap">
	<h1 class="wp-heading-inline">所有充值订单</h1>
    <a href="<?php echo admin_url('admin.php?page=cao_order_page&remosql=1'); ?>"  onclick="javascript:if(!confirm('确定清理无效订单？')) return false;" class="page-title-action"><span class="layui-badge-dot"></span> 清理无效订单 <span class="layui-badge-dot"></span></a>
    <hr class="wp-header-end">
	
	<form id="order-filter" method="get">
		<!-- 初始化页面input -->
		<input type="hidden" name="page" value="<?php echo $_GET['page']?>">
		<!-- 筛选 -->
		<div class="wp-filter">
		    <div class="filter-items">
		    	<div class="view-switch">
		    		<a class="view-list current"></a>
		    	</div>
		        <div class="actions">
                    <select class="postform" id="order_type" name="order_type">
                        <option selected="selected" value="">充值类型</option>
                        <option value="charge">在线支付</option>
                        <option value="other">后台充值</option>
                    </select>
			        <select class="postform" id="status" name="status">
			            <option selected="selected" value="">支付状态</option>
			            <option value="0">未支付</option>
			            <option value="1">已支付</option>
			        </select>
			        <select id="filter-by-format" name="pay_type">
			            <option selected="selected" value="">支付方式</option>
			            <option value="1">支付宝</option>
                        <option value="2">微信</option>
                        <option value="4">PAYJS</option>
                        <option value="5">讯虎（微信）</option>
			            <option value="6">讯虎（支付宝）</option>
			        </select>
		        <input class="button" id="post-query-submit" name="filter_action" type="submit" value="筛选"></input>
				</div>
		    </div>
		    <div class="search-form">
		        <span class="">共<?php echo $total?>个项目 </span>
		        <input class="search" id="media-search-input" name="order_trade_no" placeholder="搜索订单号,回车确定…" type="search" value=""/>
		    </div>
		    <br class="clear">
		</div>
		<!-- 筛选END -->

		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<th class="column-primary">订单号</th>
					<th>用户ID</th>	
					<th>类型</th>
					<th>订单金额</th>
					<th>充值数量</th>
					<th>支付方式</th>
					<th>下单时间</th>
					<th>支付时间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody id="the-list">

		<?php

			if($result) {
				
				foreach($result as $item){
					echo '<tr id="order-info">';
					echo '<td class="has-row-actions column-primary">'.$item->order_trade_no.'<button type="button" class="toggle-row"><span class="screen-reader-text">显示详情</span></button></td>';

					echo '<td data-colname="用户ID">'.@get_user_by('id',$item->user_id)->user_login.'</td>';

					echo '<td data-colname="类型">余额充值</td>';
					
					echo '<td data-colname="订单金额">￥'.$item->order_price.'</td>';
					
					echo '<td data-colname="充值数量"><span class="badge">'.sprintf('%0.2f', $item->order_price * $charge_rate)._cao('site_money_ua').'</span></td>';
					
                    if ($item->order_type == 'other') {
                        echo '<td data-colname="支付方式"><span class="badge badge-radius badge-warning"><i class="fa fa-wordpress"></i> 后台充值</span></td>';
                    }else{
                        if ($item->pay_type == 1) {
                            echo '<td data-colname="支付方式"><span class="badge badge-radius badge-blue"><i class="fa fa-paypal"></i> 支付宝</span></td>';
                        }else if($item->pay_type == 2){
                            echo '<td data-colname="支付方式"><span class="badge badge-radius badge-primary"><i class="fa fa-weixin"></i> 微信</span></td>';
                        }else if($item->pay_type == 4){
                            echo '<td data-colname="支付方式"><span class="badge badge-radius badge-primary"><i class="fa fa-weixin"></i> PAYJS</span></td>';
                        }else if($item->pay_type == 5){
                            echo '<td data-colname="支付方式"><span class="badge badge-radius badge-primary"><i class="fa fa-weixin"></i> 讯虎微信</span></td>';
                        }else if($item->pay_type == 6){
                            echo '<td data-colname="支付方式"><span class="badge badge-radius badge-blue"><i class="fa fa-paypal"></i> 讯虎支付宝</span></td>';
                        }else if($item->pay_type == 7){
                            echo '<td data-colname="支付方式"><span class="badge badge-radius badge-blue">码支付支付宝</span></td>';
                        }else if($item->pay_type == 8){
                            echo '<td data-colname="支付方式"><span class="badge badge-radius badge-primary">码支付微信</span></td>';
                        }else{
                            echo '<td data-colname="支付方式">其他</td>';
                        }
                    }

					echo '<td data-colname="下单时间">'.date('Y-m-d H:i:s',$item->create_time).'</td>';

					if ($item->pay_time) {
						echo '<td data-colname="支付时间">'.date('Y-m-d H:i:s',$item->pay_time).'</td>';
					}else{
						echo '<td data-colname="支付时间">——</td>';
					}

					if ($item->status == 1) {
						echo '<td data-colname="状态"><span class="badge badge-radius badge-primary">已付款</span></td>';
					}else{
						echo '<td data-colname="状态"><span class="badge badge-radius">未付款</span></td>';
					}
					//编辑操作
					$edit_url = add_query_arg(array('page' => $_GET['page'],'action' => 'edit','id' => $item->id, ), admin_url('admin.php'));
					echo '<td data-colname="编辑"><a href="'.$edit_url.'">详情</a></td>';


					echo "</tr>";
				}
			}
			else{
				echo '<tr><td colspan="12" align="center"><strong>没有数据</strong></td></tr>';
			}
		?>
		</tbody>
		</table>
	</form>
    <?php echo cao_admin_pagenavi($total,$perpage);?>
    <script>
            jQuery(document).ready(function($){

            });
	</script>
</div>

<?php else: ?>
<!-- 编辑页 -->
<?php


$info=$wpdb->get_row("SELECT * FROM $order_table_name where id=".$id);
if(!$info->id)
{
	echo '<div id="message" class="updated notice is-dismissible"><p>ID参数无效，请返回重试</p></div>';
	exit;
}
// POST data
$is_updata = (!empty($_POST['action']) && $_POST['action'] =='updata') ? true :false;
$is_delete = (!empty($_POST['action']) && $_POST['action'] =='delete') ? true :false;
$status = (!empty($_POST['status'])) ? (int)$_POST['status'] : 0;

if ($is_updata) {
	echo '<div id="message" class="error notice is-dismissible"><p>支付订单为一次性订单，不可修改变更，如需充值，请手动充值即可</p></div>';
}


?>
<div class="wrap">
    <h1>查看详情</h1>
    <!-- <form id="poststuff"> -->
    <form name="post" action="" method="post" id="poststuff">
        <input name="action" type="hidden" value="updata">
        	<input type="hidden" name="order_trade_no" value="<?php echo $info->order_trade_no ?>">
                    <div class="metabox-holder columns-2" id="post-body">
                        <div class="edit-form-section edit-comment-section" id="post-body-content">
                            <div class="stuffbox" id="namediv">
                                <div class="inside">
                                    <h2 class="edit-comment-author">详情</h2>
                                    <fieldset>
                                        <table class="form-table editcomment">
                                            <tbody>
                                                <tr>
                                                	<td class="first"><label for="user_id">用户ID</label></td>
                                                    <td><input id="user_id" name="user_id" size="30" type="text" value="<?php echo $info->user_id ?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                	<td class="first"><label for="order_trade_no">本地订单号</label></td>
                                                    <td><input id="order_trade_no" name="order_trade_no" size="30" type="text" value="<?php echo $info->order_trade_no ?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                	<td class="first"><label for="order_price">订单价格/￥</label></td>
                                                    <td><input id="order_price" name="order_price" size="30" type="text" value="<?php echo $info->order_price ?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                	<td class="first"><label for="order_type">类型</label></td>
                                                    <td><input id="order_type" name="order_type" size="30" type="text" value="余额充值" disabled/></td>
                                                </tr>
                                                <tr>
                                                	<td class="first"><label for="pay_type">支付方式</label></td>
                                                	<?php 
                                                        if ($info->order_type == 'other') {
                                                           $pay_type = '后台充值' ;
                                                        }else{
                                                            $pay_type = ($info->pay_type ==1) ? '支付宝' : '微信' ;
                                                        }
                                                    ?>
                                                    <td><input id="pay_type" name="pay_type" size="30" type="text" value="<?php echo $pay_type ?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                	<td class="first"><label for="create_time">下单时间</label></td>
                                                    <td><input id="create_time" name="create_time" size="30" type="text" value="<?php echo date('Y-m-d H:i:s',$info->create_time)?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                	<td class="first"><label for="pay_time">支付时间</label></td>
                                                    <td><input id="pay_time" name="pay_time" size="30" type="text" value="<?php echo date('Y-m-d H:i:s',$info->pay_time)?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                	<td class="first"><label for="pay_trade_no">支付单号</label></td>
                                                    <td><input id="pay_trade_no" name="pay_trade_no" size="30" type="text" value="<?php echo $info->pay_trade_no ?>" disabled/></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <!-- /post-body-content -->
                        <div class="postbox-container" id="postbox-container-1">
                            <div class="stuffbox" id="submitdiv">
                                <h2>
                                    支付状态
                                </h2>
                                <div class="inside">
                                    <div class="submitbox" id="submitcomment">
                                        <div id="minor-publishing">
                                            <div id="misc-publishing-actions">
                                                <fieldset class="misc-pub-section misc-pub-comment-status" id="comment-status-radio">
                                                    <label><input checked="checked" name="status" type="radio" value="0">未支付</input></label>
                                                    <br>
                                                        <label><input name="status" type="radio" value="1">已支付</input></label>
                                                    <br>
                                                </fieldset>
                                            </div>
                                            <!-- misc actions -->
                                            <div class="clear">
                                            </div>
                                        </div>
                                        <div id="major-publishing-actions">
                                            <div id="delete-action">
                                                <a class="submitdelete deletion" onclick="javascript:alert('为保证订单数据正常，不建议删除');">删除</a>
                                            </div>
                                            <div id="publishing-action">
                                                <input class="button button-primary button-large" id="save" name="save" type="submit" value="更新"/>
                                            </div>
                                            <div class="clear">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /submitdiv -->
                        </div>
                    </div>
                    <!-- /post-body -->
                </input>
            </input>
        </input>
    </form>
</div>
<?php endif; ?>