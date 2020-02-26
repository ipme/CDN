<?php
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $coupon_table_name;

$no_add = (!empty($_GET['action']) && $_GET['action'] =='add') ? false :true;
$is_delete = (!empty($_GET['action']) && $_GET['action'] =='delete') ? true :false;
$id = !empty($_GET['id']) ? (int)$_GET['id'] : 0 ;

if ($is_delete) {
    # 删除操作...
    $deletesql = $wpdb->query("DELETE FROM $coupon_table_name WHERE id = $id ");
    if ($deletesql) {
        echo '<div id="message" class="updated notice is-dismissible"><p>删除成功</p></div>'; 
    }
}


?>

<?php if ($no_add) : ?>
<?php
// 主页面PHP
$perpage = 20; // 每页数量
$paged=isset($_GET['paged']) ?intval($_GET['paged']) :1;  //当前页
$offset = $perpage*($paged-1); //偏移页
//////// 构造SQL START ////////
$sql = "SELECT * FROM {$coupon_table_name}";
$where = ' WHERE 1=1';
if ( isset( $_GET['status'] ) && is_numeric($_GET['status']) ) {
    // 当前时间
    $this_time= time();
    if ($_GET['status'] == 1) {
        $where .= ' AND status=1 OR end_time<'.$this_time;
    }else{
        $where .= ' AND status=0 AND end_time>'.$this_time;
    }
}

if ( !empty( $_GET['code'] ) ) {
    $where .= ' AND code="'.esc_sql($_GET['code']).'"';
}

$orderlimti = ' ORDER BY create_time DESC';
$orderlimti .= ' LIMIT '.esc_sql($offset.','.$perpage);
$result = $wpdb->get_results($sql.$where.$orderlimti);
$total   = $wpdb->get_var("SELECT COUNT(id) FROM $coupon_table_name {$where}");

//////// 构造SQL END ////////
?>

<!-- 主页面 -->
<div class="wrap">
	<h1 class="wp-heading-inline">所有资源订单</h1>
    <?php $add_url = add_query_arg(array('page' => $_GET['page'],'action' => 'add'), admin_url('admin.php'));?>
    <a href="<?php echo $add_url ?>" class="page-title-action">添加卡密-CDK</a>
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
			            <option selected="selected" value="">卡密状态</option>
			            <option value="0">正常</option>
			            <option value="1">失效</option>
			        </select>
		        <input class="button" id="post-query-submit" name="filter_action" type="submit" value="筛选"></input>
				</div>
		    </div>
		    <div class="search-form">
		        <span class="">共<?php echo $total?>个项目 </span>
		        <input class="search" id="media-search-input" name="code" placeholder="输入卡密搜索,回车确定…" type="search" value=""/>
		    </div>
		    <br class="clear">
		</div>
		<!-- 筛选END -->

		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<th class="column-primary">CDK-CODE</th>
					<th>类型</th>	
                    <th>创建时间</th>
					<th>到期时间</th>
					<th>卡密金额</th>
					<th>卡密状态</th>
                    <th>使用时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody id="the-list">

		<?php

			if($result) {
				// 当前时间
                $the_time = time();
				foreach($result as $item){
					echo '<tr id="order-info">';
					echo '<td class="has-row-actions column-primary"><span class="badge badge-radius">'.$item->code.'</span><button type="button" class="toggle-row"><span class="screen-reader-text">显示详情</span></button></td>';

					echo '<td data-colname="类型">充值卡</td>';
                    echo '<td data-colname="创建时间">'.date('Y-m-d H:i:s',$item->create_time).'</td>';
                    echo '<td data-colname="到期时间">'.date('Y-m-d H:i:s',$item->end_time).'</td>';
                    echo '<td data-colname="卡密金额"><span class="badge badge-warning">'.$item->sale_money._cao('site_money_ua').'</span></td>';

					if ($item->end_time<$the_time) {
						echo '<td data-colname="卡密状态"><span class="badge badge-radius badge-danger">已到期</span></td>';
					} elseif ($item->status == 1){
						echo '<td data-colname="卡密状态"><span class="badge badge-radius badge-danger">已使用</span></td>';
					}else{
                        echo '<td data-colname="卡密状态"><span class="badge badge-radius badge-primary">未使用</span></td>';
                    }

                    if ($item->apply_time) {
                        echo '<td data-colname="使用时间">'.date('Y-m-d H:i:s',$item->apply_time).'</td>';
                    }else{
                        echo '<td data-colname="使用时间">——</td>';
                    }
					//编辑操作
					$edit_url = add_query_arg(array('page' => $_GET['page'],'action' => 'delete','id' => $item->id, ), admin_url('admin.php'));
					echo '<td data-colname="编辑/删除">
                        <a href="'.$edit_url.'" onclick="javascript:if(!confirm(\'确定删除？\')) return false;">删除</a>
                    </td>';

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
$CaoCdk = new CaoCdk();
// POST data
$is_add = (!empty($_POST['action']) && $_POST['action'] =='add') ? true :false;
$num = (!empty($_POST['num'])) ? $_POST['num'] : 0;
$day = (!empty($_POST['day'])) ? $_POST['day'] : 0;
$sale_money = (!empty($_POST['sale_money'])) ? $_POST['sale_money'] : 0;

if ($is_add) {
    if ($CaoCdk->addCdk($sale_money, $day, $num)) {
        echo '<div id="message" class="updated notice is-dismissible"><p>添加成功</p></div>'; 
    }else{
        echo '<div id="message" class="error notice is-dismissible"><p>添加失败</p></div>'; 
    }
}

?>
<div class="wrap">
    <h1>添加卡密</h1>
    <!-- <form id="poststuff"> -->
    <form action="" id="poststuff" method="post" name="post">
        <input name="action" type="hidden" value="add"></input>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="num">生成卡密数量</label></th>
                        <td><input class="small-text" id="num" min="1" name="num" step="1" type="number" value="1"> 个</input></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="day">卡密有效期/天</label></th>
                        <td><input class="small-text" id="day" min="1" name="day" step="1" type="number" value="1"> 天</input></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sale_money">卡密金额</label></th>
                        <td><input id="sale_money" min="1" name="sale_money" step="1" type="number" value="1"> <?php echo _cao('site_money_ua')?></input></td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="立即添加"></p>
    </form>
</div>
<?php endif; ?>