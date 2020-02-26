<?php
global $current_user,$paged;
$user_id     = $current_user->ID;
$post_status = !empty($_GET['post_status']) ? $_GET['post_status'] : '';
$post_status = in_array($post_status, array('publish', 'draft', 'pending')) ? $post_status : '';

?>
<div class="col-xs-12 col-sm-12 col-md-9">
<div class="mypay-list form-box"> 
    <div class="mypost-status-nav">
        <a href="<?php echo esc_url(home_url('/user?action=mypost&post_status=publish')) ?>">已发布</a>
        <a href="<?php echo esc_url(home_url('/user?action=mypost&post_status=pending')) ?>">待审核</a>
        <a href="<?php echo esc_url(home_url('/user?action=mypost&post_status=draft')) ?>">草稿</a>
    </div>

<div class="row">

    <?php
        # 输出列表...
        $args = array(
            'post_type' => 'post',
            'post_status' => $post_status,
            'posts_per_page' => 5,
            'paged' => $paged,
            'author' => $user_id,
            'has_password' => false,
            'ignore_sticky_posts' => 1,
            'orderby' => 'date', // modified - 如果按最新编辑时间排序
            'order' => 'DESC'
        );
        query_posts($args);

        if (have_posts()):

            while (have_posts()): the_post();
               ?>
                <div class="col-12">
                  <article id="post-<?php the_ID(); ?>" <?php post_class( 'post post-list' ); ?>>
                    <a href="<?php echo esc_url(home_url('/user?action=editpost&post_id='.get_the_ID())) ?>" class="right-edit">编辑</a>
                    <?php cao_entry_media( array( 'layout' => 'rect_300' ) ); ?>
                    <div class="entry-wrapper">
                      <?php cao_entry_header( array( 'category' => true ,'comment'=> true) ); ?>
                      <div class="entry-excerpt u-text-format"><?php echo _get_excerpt(80); ?></div>
                      <?php get_template_part( 'parts/entry-footer' ); ?>
                    </div>
                  </article>
                </div>
               <?php
            endwhile;
        
        _paging();
        
        else:
            get_template_part( 'parts/template-parts/content','none' );

        endif;
        wp_reset_query();
    ?>

</div>
</div>
</div>
