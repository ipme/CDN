<?php 
global $current_user;
$CaoUser = new CaoUser($current_user->ID);
$Reflog = new Reflog($current_user->ID);
$get_total_bonus=$Reflog->get_total_bonus(); //累计佣金
$get_ke_bonus=$Reflog->get_ke_bonus(); //可提现
$get_ing_bonus=$Reflog->get_ing_bonus(); //提现中
$get_yi_bonus=$Reflog->get_yi_bonus(); //已提现
if ($CaoUser->vip_status()) {

    $the_ref_float = (_cao('site_vip_ref_float')*100).'%';
}else{
    $the_ref_float = (_cao('site_novip_ref_float')*100).'%';
}

?>


<div class="col-xs-12 col-sm-12 col-md-9">
    <form class="mb-0">
        <div class="card-box">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="author-info mcolorbg2">
                        <small>RMB</small>
                        <p>累计佣金</p>
                        <h3><?php echo $get_total_bonus;?></h3>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="author-info pcolorbg2">
                        <small>RMB</small>
                        <p>可提现</p>
                        <h3><?php echo $get_ke_bonus;?></h3>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="author-info scolorbg2">
                        <small>RMB</small>
                        <p>已提现</p>
                        <h3><?php echo $get_yi_bonus;?></h3>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-box">
            <div class="modules__title">
                <h4 class="mb-0">推广链接：<span id="refurl" class="label label-info" data-clipboard-text="<?php echo cao_get_referral_link($current_user->ID)?>" style="background-color: #34495e;font-weight: 500;cursor: pointer;"><?php echo cao_get_referral_link($current_user->ID)?></span></h4>
            </div>
             
        </div>
    </form>
    <form class="mb-0">
        <div class="form-box">
            <h4 class="form--title">佣金详情    <span class="badge" style=" vertical-align: top; ">提现 <i class="fa fa-angle-down"></i></span></h4>
            <div class="collapse" id="collapsecdk">
              <div class="well">
                <div class="form-group mb-10">
                    <label for="qr_weixin">最低提现申请 / <?php echo _cao('site_min_tixian_num') ?>元起</label>
                    <div class="input-group">
                      <input type="number" class="form-control" name="refmoney" value="" placeholder="请输入提现金额">
                      <span class="input-group-btn">
                        <?php if (!_cao('is_ref_to_rmb')): ?>
                        <button class="go-add_reflog btn btn-danger" type="button" data-nonce="<?php echo wp_create_nonce('caoclick-' . $current_user->ID); ?>" data-max="<?php echo $get_ke_bonus;?>">提现到RMB</button>
                        <?php endif;?>
                        <button class="go-add_reflog2 btn btn-danger" type="button" data-nonce="<?php echo wp_create_nonce('caoclick-' . $current_user->ID); ?>" data-max="<?php echo $get_ke_bonus;?>">提现到余额</button>
                      </span>
                    </div><!-- /input-group -->
                </div>
                <p style=" color: #ff7166; font-size: 13px; padding: 0; margin: 0; ">1，提现到RMB：请上传好您的收款码保存，站长将在第一时间给您转账到您的支付宝或微信钱包。</p>
                <p style=" color: #ff7166; font-size: 13px; padding: 0; margin: 0; ">2，提现到余额：佣金直接按照网站的充值比例给您兑换到可用余额，用于消费。</p>
              </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="get_ref_num">推广人数</label>
                        <input type="text" class="form-control" value="<?php echo $Reflog->get_ref_num();?>" disabled="disabled">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="get_ref_num">佣金比例</label>
                        <input type="text" class="form-control" value="<?php echo $the_ref_float;?>" disabled="disabled">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                     <div class="form-group">
                        <label for="get_total_bonus">累计佣金</label>
                        <input type="text" class="form-control" id="get_total_bonus" value="<?php echo $get_total_bonus;?>" disabled="disabled">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                     <div class="form-group">
                        <label for="get_ke_bonus">可提现</label>
                        <input type="text" class="form-control" id="get_ke_bonus" value="<?php echo $get_ke_bonus;?>" disabled="disabled">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                     <div class="form-group">
                        <label for="get_ing_bonus">提现中</label>
                        <input type="text" class="form-control" id="get_ing_bonus" value="<?php echo $get_ing_bonus;?>" disabled="disabled">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                     <div class="form-group">
                        <label for="get_yi_bonus">已提现</label>
                        <input type="text" class="form-control" id="get_yi_bonus" value="<?php echo $get_yi_bonus;?>" disabled="disabled">
                    </div>
                </div>
               
            </div>
            
            <h4 class="form--title">收款信息</h4>
            
            <div class="form-group">
                <label for="qr_weixin">微信个人收款码</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="qr_weixin" id="qr_weixin" value="<?php echo get_user_meta($current_user->ID, 'qr_weixin',true )?>">
                  <span class="input-group-btn">
                    <button class="btn btn--secondary btn-file" type="button"><input type="file" accept="image/*" onchange="getUrl(this,'file-url','qr_weixin')">上传解析</button>
                  </span>
                </div><!-- /input-group -->
            </div>
            <div class="form-group">
                <label for="qr_alipay">支付宝个人收款码</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="qr_alipay" id="qr_alipay" value="<?php echo get_user_meta($current_user->ID, 'qr_alipay',true )?>">
                  <span class="input-group-btn">
                    <button class="btn btn--secondary btn-file" type="button"><input type="file" accept="image/*" onchange="getUrl(this,'file-url','qr_alipay')">上传解析</button>
                  </span>
                </div><!-- /input-group -->
            </div>
             <a href="javascript:;" etap="submit_qr" class="btn btn--primary">保存收款码</a>
            <!-- .form-group end -->
        </div>

    </form>
    
<!-- .col-md-8 end -->


<script type="text/javascript">

// 二维码
//获取预览图片路径
let getObjectURL = function(file){
    let url = null ; 
    if (window.createObjectURL!=undefined) { // basic
        url = window.createObjectURL(file) ;
    } else if (window.URL!=undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file) ;
    } else if (window.webkitURL!=undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file) ;
    }
    return url ;
}

window.analyticCode = {
    getUrl : function(type,elem,fn){
        let url = null,src = null;
        
        if(type === 'img-url'){
            url = elem.src;
        }else if(type === 'file-url' && elem.files.length > 0){
            url = getObjectURL(elem.files[0]);
        }
        qrcode.decode(url);
        qrcode.callback = function(imgMsg){
            fn(imgMsg,url);
        }
    }
}

function getUrl(e,param,type){
    analyticCode.getUrl(param,e,function(url1,url2){
        $("input[name="+type+"]").val(url1);
    });
}


</script>
