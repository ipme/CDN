<?php
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $balance_log_table_name;
?>

<?php
// 主页面PHP

$perpage = 20; // 每页数量
$paged=isset($_GET['paged']) ?intval($_GET['paged']) :1;  //当前页
$offset = $perpage*($paged-1); //偏移页
//////// 构造SQL START ////////
$sql = "SELECT * FROM {$balance_log_table_name}";
$where = ' WHERE 1=1';

// charge,post,cdk,other  类型：充值 资源 卡密 其他
if ( !empty( $_GET['type'] ) ) {
    $where .= ' AND type="'.esc_sql($_GET['type']).'"';
}
if ( !empty( $_GET['user_id'] ) ) {
    $where .= ' AND user_id='.esc_sql($_GET['user_id']);
}

$orderlimti = ' ORDER BY time DESC';
$orderlimti .= ' LIMIT '.esc_sql($offset.','.$perpage);
$result = $wpdb->get_results($sql.$where.$orderlimti);
$total   = $wpdb->get_var("SELECT COUNT(id) FROM $balance_log_table_name {$where}");

//////// 构造SQL END ////////
?>

<!-- 主页面 -->
<div class="wrap">
	<h1 class="wp-heading-inline">用户余额明细查询</h1>
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
                    <select class="postform" id="type" name="type">
                        <option selected="selected" value="">类型</option>
                        <option value="charge">在线充值</option>
                        <option value="post">资源购买</option>
                        <option value="cdk">卡密充值</option>
                        <option value="other">其他</option>
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
					<th>旧余额</th>
					<th>操作金额</th>
                    <th>新余额</th>
					<th>类型</th>
					<th>时间</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody id="the-list">

		<?php

			if($result) {
				
				foreach($result as $item){
					echo '<tr id="order-info">';
					echo '<td class="has-row-actions column-primary">'.get_user_by('id',$item->user_id)->user_login.'<button type="button" class="toggle-row"><span class="screen-reader-text">显示详情</span></button></td>';

					echo '<td data-colname="旧余额"><span class="badge">'.$item->old.'</span></td>';
                    echo '<td data-colname="操作金额"><span class="badge badge-danger">'.$item->apply.'</span></td>';
                    echo '<td data-colname="新余额"><span class="badge badge-warning">'.$item->new.'</span></td>';
					// 类型charge,post,cdk,other
                        // <option value="charge">在线充值</option>
                        // <option value="post">资源购买</option>
                        // <option value="cdk">卡密充值</option>
                        // <option value="other">其他</option>
                    switch ($item->type) {
                        case 'charge':
                            $t_type='在线充值';
                            break;
                        case 'post':
                            $t_type='资源购买';
                            break;
                        case 'cdk':
                            $t_type='卡密充值';
                            break;
                        case 'other':
                            $t_type='其他';
                            break;
                    }
                    echo '<td data-colname="支付方式">'.$t_type.'</td>';
					echo '<td data-colname="时间">'.date('Y-m-d H:i:s',$item->time).'</td>';

                    echo '<td data-colname="状态">'.$item->note.'</td>';

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
