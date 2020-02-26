<?php
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $ref_log_table_name;

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
$sql = "SELECT * FROM {$ref_log_table_name}";
$where = ' WHERE 1=1';
if ( isset( $_GET['status'] ) && is_numeric($_GET['status']) ) {
    $where .= ' AND status='.esc_sql($_GET['status']);
}
if ( !empty( $_GET['user_id'] ) ) {
    $where .= ' AND user_id='.esc_sql($_GET['user_id']);
}
$orderlimti = ' ORDER BY create_time DESC';
$orderlimti .= ' LIMIT '.esc_sql($offset.','.$perpage);
$result = $wpdb->get_results($sql.$where.$orderlimti);
$total   = $wpdb->get_var("SELECT COUNT(id) FROM $ref_log_table_name {$where}");
//////// 构造SQL END ////////
?>

<!-- 主页面 -->
<div class="wrap">
	<h1 class="wp-heading-inline">所有提现申请</h1>
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
			            <option selected="selected" value="">提现状态</option>
			            <option value="0">未打款</option>
                        <option value="1">已打款</option>
			            <option value="-1">冻结提现</option>
			        </select>
		        <input class="button" id="post-query-submit" name="filter_action" type="submit" value="筛选"></input>
				</div>
		    </div>
		    <div class="search-form">
		        <span class="">共<?php echo $total?>个项目 </span>
		        <input class="search" id="media-search-input" name="user_id" placeholder="根据用户ID搜索,回车确定…" type="search" value=""/>
		    </div>
		    <br class="clear">
		</div>
		<!-- 筛选END -->

		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<th class="column-primary">用户ID</th>
					<th>申请时间</th>
					<th>提现金额</th>
					<th>提现状态</th>
					<th>审核时间</th>
					<th>备注</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody id="the-list">

		<?php

			if($result) {
				
				foreach($result as $item){
					echo '<tr id="order-info">';
					echo '<td class="has-row-actions column-primary">'.get_user_by('id',$item->user_id)->user_login.'<button type="button" class="toggle-row"><span class="screen-reader-text">显示详情</span></button></td>';

                    echo '<td data-colname="申请时间">'.date('Y-m-d H:i:s',$item->create_time).'</td>';
					echo '<td data-colname="提现金额"><span class="badge">'.$item->money.' ￥</span></td>';

                    if ($item->status == 0) {
                        echo '<td data-colname="提现状态"><span class="badge badge-radius">未打款</span></td>';
                    }elseif($item->status == 1){
                        echo '<td data-colname="提现状态"><span class="badge badge-radius badge-primary">已打款</span></td>';
                    }else{
                        echo '<td data-colname="提现状态"><span class="badge badge-radius badge-danger">失效</span></td>';
                    }
                    $up_time = ($item->up_time>0) ? date('Y-m-d H:i:s',$item->up_time) : '——' ;
                    echo '<td data-colname="审核时间">'.$up_time.'</td>';
					echo '<td data-colname="备注">'.$item->note.'</td>';

					//编辑操作
					$edit_url = add_query_arg(array('page' => $_GET['page'],'action' => 'edit','id' => $item->id, ), admin_url('admin.php'));
					echo '<td data-colname="编辑/审核"><a href="'.$edit_url.'">编辑/审核</a></td>';


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
</div>

<?php else: ?>
<!-- 编辑页 -->
<?php


$info=$wpdb->get_row("SELECT * FROM $ref_log_table_name where id=".$id);
if(!$info->id)
{
	echo '<div id="message" class="updated notice is-dismissible"><p>ID参数无效，请返回重试</p></div>';
	exit;
}
// POST data
$is_updata = (!empty($_POST['action']) && $_POST['action'] =='updata') ? true :false;
$status = (!empty($_POST['status'])) ? (int)$_POST['status'] : 0;
$user_id = (!empty($_POST['user_id'])) ? $_POST['user_id'] : 0;
$id = (!empty($_POST['id'])) ? $_POST['id'] : 0;

if ($is_updata) {
    $Reflog = new Reflog($user_id);
    if ($Reflog->updatelog($id,$status)) {
        echo '<div id="message" class="updated notice is-dismissible"><p>更新成功</p></div>'; 
    }else{
        echo '<div id="message" class="error notice is-dismissible"><p>更新失败</p></div>'; 
    }
	
}


?>
<div class="wrap">
    <h1>提现审核/详情</h1>
    <!-- <form id="poststuff"> -->
    <form name="post" action="" method="post" id="poststuff">
        <input name="action" type="hidden" value="updata">
            <input type="hidden" name="id" value="<?php echo $info->id ?>">
        	<input type="hidden" name="user_id" value="<?php echo $info->user_id ?>">
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
                                                    <td><input id="user_id" name="user_id" size="30" type="text" value="<?php echo get_user_by('id',$info->user_id)->user_login ?>" disabled/></td>
                                                </tr>
                                               <tr>
                                                    <td class="first"><label for="create_time">申请时间</label></td>
                                                    <td><input id="create_time" name="create_time" size="30" type="text" value="<?php echo date('Y-m-d H:i:s',$info->create_time)?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                	<td class="first"><label for="money">提现金额/￥</label></td>
                                                    <td><input id="money" name="money" size="30" type="text" value="<?php echo $info->money ?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                    <?php $up_time = ($info->up_time>0) ? date('Y-m-d H:i:s',$info->up_time) : '——' ; ?>
                                                    <td class="first"><label for="up_time">审核时间</label></td>
                                                    <td><input id="up_time" name="up_time" size="30" type="text" value="<?php echo $up_time?>" disabled/></td>
                                                </tr>
                                                <tr>
                                                    <td class="first"><label for="note">提现说明</label></td>
                                                    <td><input id="note" name="note" size="30" type="text" value="<?php echo $info->note ?>" disabled/></td>
                                                </tr>

                                                <tr style=" background-color: #f5f5f5; ">
                                                    <td class="first"><label for="note">收款码</label></td>
                                                    <td>
                                                        <?php
                                                            // $info->user_id qr_weixin qr_alipay
                                                            $qr_weixin = get_user_meta($info->user_id, 'qr_weixin', true);
                                                            $qr_alipay = get_user_meta($info->user_id, 'qr_alipay', true);
                                                            $qr_weixin_img = ($qr_weixin) ? $qr_weixin : get_template_directory_uri() . '/assets/images/icons/qr.jpg' ;

                                                            $qr_alipay_img = ($qr_alipay) ? $qr_alipay : get_template_directory_uri() . '/assets/images/icons/qr.jpg' ;
                                                        ?>
                                                        <div class="layui-row layui-col-space15">
                                                            <div class="layui-col-sm6 layui-col-md6">
                                                                <div class="layui-card">
                                                                    <div class="layui-card-header">支付宝收款码</div>
                                                                    <div class="layui-card-body layuiadmin-card-list">
                                                                        <img src="http://qr.liantu.com/api.php?text=<?php echo $qr_alipay_img?>"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="layui-col-sm6 layui-col-md6">
                                                                <div class="layui-card">
                                                                    <div class="layui-card-header">微信收款码</div>
                                                                    <div class="layui-card-body layuiadmin-card-list">
                                                                        <img src="http://qr.liantu.com/api.php?text=<?php echo $qr_weixin_img?>"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </td>
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
                                <h2>审核/提现状态</h2>
                                <div class="inside">
                                    <div class="submitbox" id="submitcomment">
                                        <div id="minor-publishing">
                                            <div id="misc-publishing-actions">
                                                <fieldset class="misc-pub-section misc-pub-comment-status" id="comment-status-radio">
                                                    <label><input <?php echo $checked = ($info->status ==0) ? 'checked="checked"' : '' ;?> name="status" type="radio" value="0">未打款</input></label>
                                                    <br>
                                                    <label><input <?php echo $checked = ($info->status ==1) ? 'checked="checked"' : '' ;?> name="status" type="radio" value="1">已打款</input></label>
                                                    <br>
                                                    <label><input <?php echo $checked = ($info->status == -1) ? 'checked="checked"' : '' ;?> name="status" type="radio" value="-1">失效</input></label>
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