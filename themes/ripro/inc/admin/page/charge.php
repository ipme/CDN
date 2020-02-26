<?php
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $order_table_name;

// POST data
$is_charge = (!empty($_POST['action']) && $_POST['action'] =='is_charge') ? true :false;
$uid = (!empty($_POST['uid'])) ? $_POST['uid'] : 0;
$num = (!empty($_POST['num'])) ? $_POST['num'] : 0;

echo '<div id="message" class="error notice is-dismissible"><p>当前不建议使用后台充值，影响订单记录，请给用户卡密让自行充值，保证余额变动有明细</p></div>';

?>
<div class="wrap">
    <h1>手动充值<?php echo _cao('site_money_ua')?></h1>
    <!-- <form id="poststuff"> -->
    <form action="" id="poststuff" method="post" name="post">
        <input name="action" type="hidden" value="is_charge"></input>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="uid">用户ID</label></th>
                        <td><input class="small-text" id="uid" min="0" name="uid" step="1" type="number" value="0"> USER ID</input></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="num">充值数量</label></th>
                        <td><input id="num" name="num" step="1" type="number" value="1"> <?php echo _cao('site_money_ua')?>（正数为增加，负数-为减去）</input></td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" disabled="true" value="立即充值"></p>
    </form>
</div>