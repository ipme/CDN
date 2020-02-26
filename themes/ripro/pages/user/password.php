<?php 
global $current_user;
?>


<div class="col-xs-12 col-sm-12 col-md-9">
    <form class="mb-0">
        
        <!-- .form-box end -->
        <div class="form-box">
            <h4 class="form--title">修改密码</h4>
            <div class="form-group">
                <label for="password">原密码</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <!-- .form-group end -->
            <div class="form-group">
                <label for="new_password">新密码</label>
                <input type="password" class="form-control" name="new_password" id="new_password">
            </div>
            <!-- .form-group end -->
            <div class="form-group">
                <label for="re_password">重复新密码</label>
                <input type="password" class="form-control" name="re_password" id="re_password">
            </div>
        </div>
        <button type="button" class="go-repassword button">保存</button>
    </form>
</div>
<!-- .col-md-8 end -->



