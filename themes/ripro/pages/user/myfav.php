<?php
global $current_user,$paged;
$user_id     = $current_user->ID;
$PostPay = new PostPay($user_id,0);
?>

<div class="col-xs-12 col-sm-12 col-md-9">
<div class="mypay-list form-box"> 
<div class="row">

    <?php
        $post_pay_ids = get_user_meta($user_id,'follow_post',true) ;
        if (!$post_pay_ids) {
            $post_pay_ids = array(0);
        }
        # 输出列表...
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'paged' => $paged,
            //'showposts' => count($current_post_ids),
            'post__in' => $post_pay_ids,
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
                    <?php cao_entry_media( array( 'layout' => 'rect_300' ) ); ?>
                    <div class="entry-wrapper">
                      <?php cao_entry_header( array( 'category' => true ,'author'=>true ,'comment'=> true) ); ?>
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
