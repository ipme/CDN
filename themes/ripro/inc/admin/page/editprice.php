<?php
date_default_timezone_set('Asia/Shanghai');
global $wpdb;

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


<!-- 编辑页 -->
<?php
$CaoCdk = new CaoCdk();
// POST data
$is_edit = (isset($_POST['price']) || isset($_POST['rate']) ) ? true :false;
$price = (isset($_POST['price'])) ? $_POST['price'] : 0;
$rate = (isset($_POST['rate'])) ? $_POST['rate'] : 1;

if ($is_edit) {
	$result = $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = $price WHERE 1=1 AND meta_key = 'cao_price'");
	$result2 = $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = $rate WHERE 1=1 AND meta_key = 'cao_vip_rate'");
    if ($result || $result2) {
        echo '<div id="message" class="updated notice is-dismissible"><p>修改成功</p></div>'; 
    }else{
        echo '<div id="message" class="error notice is-dismissible"><p>修改失败</p></div>'; 
    }
}

?>
<div class="wrap">
    <h1>批量修改资源文章价格</h1>
    <!-- <form id="poststuff"> -->
    <form action="" id="editprice" method="post" name="post">
        <input name="action" type="hidden" value="add"></input>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="price">文章资源价格<p>【如果设置为0则等于免费资源】</p></label></th>
                        <td><input class="small-text" id="price" step="0.1" name="price" type="number" value="1"> 个</input></td>

                    </tr>
                    <tr>
                        <th scope="row"><label for="rate">文章资源会员折扣/小数<p>【0.N 等于N折；1 等于不打折；0 等于免费】</p></label></th>
                        <td><input class="small-text" id="rate" step="0.1" name="rate" type="number" value="1"> </input></td>
                    </tr>
                    
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="批量修改"></p>
    </form>
</div>