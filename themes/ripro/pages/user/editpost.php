<?php
global $current_user,$paged;
$user_id     = $current_user->ID;
$post_id = !empty($_GET['post_id']) ? $_GET['post_id'] : 0;
$author_id = get_post_field( 'post_author', $post_id ); 

$post = get_post($post_id);

if(!$post || (!current_user_can('publish_posts') && $author_id != $user_id)) { ?>
    <!-- //不存在的文章或普通编辑以下权限用户最多只能编辑自己的文章 -->
    <div class="col-xs-12 col-sm-12 col-md-9">
        <form class="mb-0">
            <div class="form-box">
                <h4 class="form--title">文章不存在或无编辑权限</h4>
            </div>
        </form>
    </div>
<?php exit();} 

$post_categories = wp_get_post_categories($post_id);
$post_cat_id = $post_categories[0];
$all_categories = get_categories();
$cao_status = get_post_meta($post_id,'cao_status',true) ? : 0;
$cao_price = get_post_meta($post_id,'cao_price',true) ? : '';
$cao_vip_rate = get_post_meta($post_id,'cao_vip_rate',true) ? : '';
$cao_pwd = get_post_meta($post_id,'cao_pwd',true) ? : '';
$cao_downurl = get_post_meta($post_id,'cao_downurl',true) ? : '';

?>


<div class="col-xs-12 col-sm-12 col-md-9">
    <form class="mb-0">
    <?php if(current_user_can('publish_posts')): ?>
        <div class="form-box">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <h4 class="form--title">编辑文章</h4>
                </div>
                
                <!-- .col-md-12 end -->
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>标题*</label>
                        <input type="text" class="form-control" name="post_title" placeholder="请输入标题" value="<?php echo $post->post_title;?>" aria-required='true' required>
                    </div>
                </div>
                <!-- .col-md-12 end -->
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="post_excerpt">摘要*</label>
                        <textarea class="form-control" name="post_excerpt" id="post_excerpt" rows="2"><?php echo $post->post_excerpt;?></textarea>
                    </div>
                </div>
                <!-- .col-md-12 end -->
                <!-- .col-md-6 end -->
                <?php $categories = get_categories( array('hide_empty' => 0) );?>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group mb-0">
                        <label for="">文章分类</label>
                        <div class="select--box">
                            <select class="dropdown" name="post_cat">
                                <?php foreach($categories as $category){ ?>
                                <option value="<?php echo $category->term_id; ?>" <?php if($category->term_id == $post_cat_id) echo 'selected'; ?>><?php echo $category->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- .col-md-6 end -->
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="select-status">文章类型</label>
                        <div class="select--box">
                            <select class="dropdown" name="cao_status">
                                <option value="free">普通</option>
                                <option value="fee">付费下载</option>
                                <option value="hide">付费查看内容</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- .col-md-4 end -->
                <div class="hide1 col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <label for="">资源价格（<?php echo _cao('site_money_ua')?>）</label>
                        <input type="number" class="form-control" name="cao_price" value="<?php echo $cao_price;?>" placeholder="整数">
                    </div>
                </div>
                <div class="hide2 col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <label for=""><?php echo _cao('site_vip_name')?>折扣</label>
                        <input type="number" class="form-control" name="cao_vip_rate" value="<?php echo $cao_vip_rate;?>" placeholder="0.N 等于N折">
                    </div>
                </div>
                <div class="hide3 col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <label for="">文件密码</label>
                        <input type="text" class="form-control" name="cao_pwd" value="<?php echo $cao_pwd;?>" placeholder="网盘密码或解压密码">
                    </div>
                </div>
                <!-- .col-md-4 end -->
                <div class="hide4 col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="">下载地址</label>
                        <input type="text" class="form-control" name="cao_downurl" value="<?php echo $cao_downurl;?>" placeholder="文件下载地址 url">
                    </div>
                </div>
                <div class="hide5 col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <div class="well">付费查看内容插入方法：[rihide]要隐藏的付费内容[/rihide]</div>
                    </div>
                </div>


            </div>
            <!-- .row end -->
        </div>
        <div class="form-box">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <h4 class="form--title">内容详情</h4>
                </div>
                <!-- .col-md-12 end -->
                <div class="col-xs-12 col-sm-4 col-md-12">
                    <div id="editor">
                        <?php echo $post->post_content;?>
                    </div>

                </div>
                <!-- .col-md-12 end -->

            </div>
            <!-- .row end -->
        </div>
        <!-- .form-box end -->
        <a href="#" class="go-write_post btn btn--secondary" data-edit_id="<?php echo $post_id?>" data-status="draft" data-nonce="<?php echo $wp_create_nonce; ?>">保存草稿</a>
        <a href="#" class="go-write_post btn btn--primary" data-edit_id="<?php echo $post_id?>" data-status="pending" data-nonce="<?php echo $wp_create_nonce; ?>">提交审核</a>
    <?php else: ?>
        <div class="form-box">
            <div class="col-12 _404">
                <div class="_404-inner">
                    <div class="entry-content">您没有发布权限，请联系管理员开通！</div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </form>
</div>

<script src="<?php echo get_template_directory_uri() ?>/assets/js/plugins/wangEditor.min.js"></script>