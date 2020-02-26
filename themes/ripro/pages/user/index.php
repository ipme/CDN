<?php 
global $current_user;
$CaoUser = new CaoUser($current_user->ID);
$wp_create_nonce = wp_create_nonce('caoclick-' . $current_user->ID);
?>


<div class="col-xs-12 col-sm-12 col-md-9">
    <form class="mb-0">
        <?php get_template_part( 'pages/user/header-card'); $_uavtype = (get_user_meta($current_user->ID, 'user_avatar_type', true)); ?>

        <div class="form-box">
            <h4 class="form--title">基本信息</h4>
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12" style=" margin-bottom: 30px; ">
                    <!-- 默认 上传 -->
                    <span class="btn avatarinfo">
                        <label for="type-gravatar">
                        <img src="<?php echo _get_user_avatar_url('gravatar')?>" height="50" class="mr-2">
                        <a class="upload"><i class="fa fa-camera"></i><input type="file" name="addPic" id="addPic" accept=".jpg, .gif, .png" resetonclick="true" data-nonce="<?php echo $wp_create_nonce; ?>">
                        </label></a>
                        <input type="radio" id="type-gravatar" name="user_avatar_type" value="gravatar" <?php echo $_uavtype=='gravatar' ? 'checked' : '';?>><label for="type-gravatar">默认</label>
                    </span>
                    <?php if(_is_bind_openid('qq')): ?>
                    <!-- QQ -->
                    <span class="btn avatarinfo">
                        <label for="type-qq">
                        <img src="<?php echo _get_user_avatar_url('qq')?>" height="50" class="mr-2">
                        </label>
                        <input type="radio" id="type-qq" name="user_avatar_type" value="qq" <?php echo $_uavtype=='qq' ? 'checked' : '';?>><label for="type-qq">QQ</label>
                    </span>
                    <?php endif; ?>
                    <?php if (_is_bind_openid('weixin')): ?>
                    <!-- 微信 -->
                    <span class="btn avatarinfo">
                        <label for="type-weixin">
                        <img src="<?php echo _get_user_avatar_url('weixin')?>" height="50" class="mr-2">
                        </label>
                        <input type="radio" id="type-weixin" name="user_avatar_type" value="weixin" <?php echo $_uavtype=='weixin' ? 'checked' : '';?>><label for="type-weixin">微信</label>
                    </span>
                    <?php endif; ?>
                    <?php if (_is_bind_openid('weibo')): ?>
                    <!-- 微博 -->
                    <span class="btn avatarinfo">
                        <label for="type-weibo">
                        <img src="<?php echo _get_user_avatar_url('weibo')?>" height="50" class="mr-2">
                        </label>
                        <input type="radio" id="type-weibo" name="user_avatar_type" value="weibo" <?php echo $_uavtype=='weibo' ? 'checked' : '';?>><label for="type-weibo">微博</label>
                    </span>
                    <?php endif; ?>
                    
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="user_login">ID</label>
                        <input type="text" class="form-control" value="<?php echo $current_user->user_login;?>" disabled="disabled">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                     <div class="form-group">
                        <label for="nickname">昵称</label>
                        <input type="text" class="form-control" name="nickname" id="nickname" value="<?php echo $current_user->nickname;?>">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="email">邮箱</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $current_user->user_email;?>">
                    </div>
                </div>
                <?php if (_cao('is_user_bang_email')): ?>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="edit_email_cap">邮箱验证码</label>
                        <div class="input-group">
                          <input type="text" class="form-control" name="edit_email_cap" id="edit_email_cap" value="">
                          <span class="input-group-btn">
                            <button class="btn edit_email_cap" type="button">发送</button>
                          </span>
                        </div><!-- /input-group -->
                    </div>
                </div>
                <?php endif; ?>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="phone">电话</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="<?php echo get_user_meta($current_user->ID, 'phone',true )?>">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="qq">联系QQ</label>
                        <input type="text" class="form-control" name="qq" id="qq" value="<?php echo get_user_meta($current_user->ID, 'qq',true )?>">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <label for="open-oauth">第三方登录</label>
                            <div class="input-group" id="open-oauth">
                              <span class="input-group-btn open-oauth">
                                <?php _the_open_oauth_btn();?>
                              </span>
                            </div><!-- /input-group -->
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">个人介绍</label>
                <textarea class="form-control" name="description" id="description" rows="2" ><?php echo get_user_meta($current_user->ID, 'description',true )?></textarea>
            </div>
            <button type="button" etap="submit_info" class="button">保存</button>
            <!-- .form-group end -->
        </div>
    </form>
</div>    
