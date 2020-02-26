<?php 
global $current_user;
$CaoUser = new CaoUser($current_user->ID);
$Reflog = new Reflog($current_user->ID);
?>
<div class="card-box">
    <div class="row">
        <div class="col-md-4 col-sm-4">
            <div class="author-info mcolorbg4">
                <small><?php echo _cao('site_money_ua');?></small>
                <p>当前余额</p>
                <h3><?php echo $CaoUser->get_balance();?></h3>
            </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="author-info pcolorbg">
                <small><?php echo _cao('site_money_ua');?></small>
                <p>已消费</p>
                <h3><?php echo $CaoUser->get_consumed_balance();?></h3>
            </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="author-info scolorbg">
                <small>RMB</small>
                <p>佣金</p>
                <h3><?php echo $Reflog->get_yi_bonus();?></h3>
            </div>
        </div>
    </div>
</div>