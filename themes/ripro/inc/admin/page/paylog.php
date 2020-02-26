<?php
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $paylog_table_name;
$charge_rate  = (int) _cao('site_change_rate'); //充值比例
$no_edit = (!empty($_GET['action']) && $_GET['action'] =='edit') ? false :true;
$id = !empty($_GET['id']) ? (int)$_GET['id'] : 0 ;

?>

<?php if ($no_edit) : ?>
<?php
// 主页面PHP
$perpage = 20; // 每页数量
$paged=isset($_GET['paged']) ?intval($_GET['paged']) :1;  //当前页
$offset = $perpage*($paged-1); //偏移页
//////// 构造SQL START ////////
$sql = "SELECT * FROM {$paylog_table_name}";
$where = ' WHERE 1=1';
if ( isset( $_GET['status'] ) && is_numeric($_GET['status']) ) {
$where .= ' AND status='.esc_sql($_GET['status']);
}
if ( !empty( $_GET['pay_type'] ) ) {
$where .= ' AND pay_type='.esc_sql($_GET['pay_type']);
}
if ( !empty( $_GET['post_id'] ) ) {
$where .= ' AND post_id="'.esc_sql($_GET['post_id']).'"';
}
$orderlimti = ' ORDER BY create_time DESC';
$orderlimti .= ' LIMIT '.esc_sql($offset.','.$perpage);
$result = $wpdb->get_results($sql.$where.$orderlimti);
$total   = $wpdb->get_var("SELECT COUNT(id) FROM $paylog_table_name {$where}");
//////// 构造SQL END ////////
?>

<!-- 主页面 -->
<div class="wrap">
	<h1 class="wp-heading-inline">所有资源订单</h1>
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
			        <select class="postform" id="status" name="status">
			            <option selected="selected" value="">购买状态</option>
			            <option value="0">未购买</option>
			            <option value="1">已购买</option>
			        </select>
			        <select id="filter-by-format" name="pay_type">
			            <option selected="selected" value="">购买方式</option>
			            <option value="1">余额支付</option>
			            <option value="2">其他</option>
			        </select>
		        <input class="button" id="post-query-submit" name="filter_action" type="submit" value="筛选"></input>
				</div>
		    </div>
		    <div class="search-form">
		        <span class="">共<?php echo $total?>个项目 </span>
		        <input class="search" id="media-search-input" name="post_id" placeholder="根据文章ID搜索,回车确定…" type="search" value=""/>
		    </div>
		    <br class="clear">
		</div>
		<!-- 筛选END -->

		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<th class="column-primary">资源名称</th>
					<th>用户ID</th>	
                    <th>售价</th>
					<th>折扣</th>
					<th>购买价格</th>
					<th>支付方式</th>
					<th>购买时间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody id="the-list">

		<?php

			if($result) {
				
				foreach($result as $item){
					echo '<tr id="order-info">';
					echo '<td class="has-row-actions column-primary"><a target="_blank" href='.get_permalink($item->post_id).'>'.get_the_title($item->post_id).'</a><button type="button" class="toggle-row"><span class="screen-reader-text">显示详情</span></button></td>';
                    $user_loginName = ($item->user_id > 0) ? get_user_by('id',$item->user_id)->user_login : '游客' ;
					echo '<td data-colname="用户ID">'.$user_loginName.'</td>';
						
					echo '<td data-colname="售价"><span class="badge">'.$item->order_price._cao('site_money_ua').'</span></td>';
                    echo '<td data-colname="折扣"><span class="badge badge-danger">'.$item->order_sale.'</span></td>';
					echo '<td data-colname="购买价格"><span class="badge badge-warning">'.$item->order_price._cao('site_money_ua').'</span></td>';
					// 1支付宝官方；2微信官方 ；3 其他  ；4 PAYJS  ；5 讯虎微信  ；6 讯虎支付宝 ；7 吗支付支付宝  ；8 吗支付微信
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
                    
					//echo '<td data-colname="购买方式"><i class="'._cao('site_money_icon').'"></i> 余额支付</td>';

					echo '<td data-colname="购买时间">'.date('Y-m-d H:i:s',$item->create_time).'</td>';

					if ($item->status == 1) {
						echo '<td data-colname="状态"><span class="badge badge-radius badge-primary">已购买</span></td>';
					}else{
						echo '<td data-colname="状态"><span class="badge badge-radius">未购买</span></td>';
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


$info=$wpdb->get_row("SELECT * FROM $paylog_table_name where id=".$id);
if(!$info->id)
{
	echo '<div id="message" class="updated notice is-dismissible"><p>ID参数无效，请返回重试</p></div>';
	exit;
}
// POST data
$is_updata = (!empty($_POST['action']) && $_POST['action'] =='updata') ? true :false;
$is_delete = (!empty($_POST['action']) && $_POST['action'] =='delete') ? true :false;
$status = (!empty($_POST['status'])) ? (int)$_POST['status'] : 0;
$order_trade_no = (!empty($_POST['order_trade_no'])) ? $_POST['order_trade_no'] : 0;

if ($is_updata) {
    $updatesql = $wpdb->update(
        $paylog_table_name,
        array('pay_time' => time(), 'status' => $status),
        array('order_trade_no' => $order_trade_no), array('%s', '%d'),
        array('%s')
    );
    if ($updatesql) {
        echo '<div id="message" class="updated notice is-dismissible"><p>更新成功</p></div>'; 
    }else{
        echo '<div id="message" class="error notice is-dismissible"><p>更新失败</p></div>'; 
    }
	
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
                                                    <td class="first"><label for="post_id">资源名称</label></td>
                                                    <td><?php echo '<a target="_blank" href='.get_permalink($info->post_id).'>'.get_the_title($info->post_id).'</a>';?></td>
                                                </tr>

                                                <tr>
                                                    <td class="first"><label for="user_id">用户ID</label></td>
                                                    <td><input id="user_id" name="user_id" size="30" type="text" value="<?php echo get_user_by('id',$info->user_id)->user_login ?>" disabled/></td>
                                                </tr>
                                               
                                                <tr>
                                                	<td class="first"><label for="order_price">售价</label></td>
                                                    <td><input id="order_price" name="order_price" size="30" type="text" value="<?php echo $info->order_price ?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                    <td class="first"><label for="order_sale">折扣</label></td>
                                                    <td><input id="order_sale" name="order_sale" size="30" type="text" value="<?php echo $info->order_sale ?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                    <td class="first"><label for="order_amount">购买价格</label></td>
                                                    <td><input id="order_amount" name="order_amount" size="30" type="text" value="<?php echo $info->order_amount._cao('site_money_ua') ?>" disabled/></td>
                                                </tr>

                                                <tr>
                                                	<td class="first"><label for="pay_type">购买方式</label></td>
                                                    <td><input id="pay_type" name="pay_type" size="30" type="text" value="余额支付" disabled/></td>
                                                </tr>
                                                
                                                <tr>
                                                	<td class="first"><label for="create_time">购买时间</label></td>
                                                    <td><input id="create_time" name="create_time" size="30" type="text" value="<?php echo date('Y-m-d H:i:s',$info->create_time)?>" disabled/></td>
                                                </tr>
                                                 
                                                <tr>
                                                    <td class="first"><label for="order_trade_no">本地订单号</label></td>
                                                    <td><input id="order_trade_no" name="order_trade_no" size="30" type="text" value="<?php echo $info->order_trade_no ?>" disabled/></td>
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
                                <h2>购买状态</h2>
                                <div class="inside">
                                    <div class="submitbox" id="submitcomment">
                                        <div id="minor-publishing">
                                            <div id="misc-publishing-actions">
                                                <fieldset class="misc-pub-section misc-pub-comment-status" id="comment-status-radio">
                                                    <label><input <?php echo $checked = ($info->status ==0) ? 'checked="checked"' : '' ;?> name="status" type="radio" value="0">未购买</input></label>
                                                    <br>
                                                        <label><input <?php echo $checked = ($info->status ==1) ? 'checked="checked"' : '' ;?> name="status" type="radio" value="1">已购买</input></label>
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