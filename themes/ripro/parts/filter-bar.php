<?php 
    if (!_cao('is_filter_bar')) :
    $cat_ID = (is_category()) ? get_query_var('cat') : 0 ;
    $categories = get_terms('category', array('hide_empty' => 0,'parent' => 0));//获取所有主分类
    $get_term_children = get_term_children($cat_ID, 'category');
?>

<div class="filter--content">
    <form class="mb-0" method="get" action="<?php echo home_url(); ?>">
        <input type="hidden" name="s">
        <div class="form-box search-properties mb-0">
            <!-- 一级分类 -->
            <?php if (_cao('is_filter_item_cat','1')) : ?>
            <div class="filter-item">
                <?php
                $content = '<ul class="filter-tag"><span><i class="fa fa-folder-open-o"></i> 分类</span>';
                foreach ($categories as $category) {
                    // 排除二级分类
                    $_oncss = ($category->term_id == $cat_ID) ? 'on' : '' ;
                    $content .= '<li><a href="'.get_category_link($category->term_id).'" class="'.$_oncss.'">'.$category->name.'</a></li>';
                }
                $content .= "</ul>";
                echo $content;
                ?>
            </div>
            <?php endif; ?>
            

            <?php 
            if (is_category()) {
                $child_categories = get_categories( array('hide_empty' => 0,'parent'=>$cat_ID) );//获取所有分类
                if (empty($child_categories)) {
                    $root_cat_ID = get_category_root_id($cat_ID);
                    $child_categories = get_categories( array('hide_empty' => 0,'parent'=>$root_cat_ID) );//获取所有分类
                }
            }
            if (!empty($child_categories) && _cao('is_filter_item_cat2','1')) : ?>
            <!-- 二级分类 -->
            <div class="filter-item">
                <?php
                    $content = '<ul class="filter-tag"><span><i class="fa fa-long-arrow-right"></i> 更多</span>';
                    foreach ($child_categories as $category) {
                        $_oncss = ($category->term_id == $cat_ID) ? 'on' : '' ;
                        $content .= '<li><a href="'.get_category_link($category->term_id).'" class="'.$_oncss.'">'.$category->name.'</a></li>';
                    }
                    $content .= "</ul>";
                    echo $content;
                ?>
            </div>
            <?php endif; ?>

            <!-- 相关标签 -->
            <?php if (_cao('is_filter_item_tags','1')){
                $cat_ID = (get_query_var('cat')) ? get_query_var('cat') : 0 ;
                $this_cat_arg = array( 'categories' => $cat_ID);
                $tags = _get_category_tags($this_cat_arg);
                if(!empty($tags)) {
                    echo '<div class="filter-item">';
                    $content = '<ul class="filter-tag"><span><i class="fa fa-tags"></i> 标签</span>';
                      foreach ($tags as $tag) {
                        $content .= '<li><a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a></li>';
                      }
                    $content .= "</ul>";
                    echo $content;
                    echo '</div>';
                }
            }?>
            <!-- 自定义筛选 -->
            <?php if (_cao('is_custom_post_meta_opt', '0') && _cao('custom_post_meta_opt', '0')) {
                $custom_post_meta_opt = _cao('custom_post_meta_opt', '0');
                foreach ($custom_post_meta_opt as $filter) {
                    $opt_meta_category = (array_key_exists('meta_category',$filter)) ? $filter['meta_category'] : false ;
                    if (!$opt_meta_category || in_array($cat_ID,$opt_meta_category) ) {
                        $_meta_key = $filter['meta_ua'];
                        echo '<div class="filter-item">';
                            $is_on = !empty($_GET[$_meta_key]) ? $_GET[$_meta_key] : '';
                            $content = '<ul class="filter-tag"><span>'.$filter['meta_name'].'</span>';
                            $meta_opt_arr = array('all' => '全部');
                            $_oncssall = ($is_on == 'all') ? 'on' : '' ;
                            $content .= '<li><a href="'.add_query_arg($_meta_key,'all').'" class="'.$_oncssall.'">全部</a></li>';
                            foreach ($filter['meta_opt'] as $opt) {
                                $_oncss = ($is_on == $opt['opt_ua']) ? 'on' : '' ;
                                $content .= '<li><a href="'.add_query_arg($_meta_key,$opt['opt_ua']).'" class="'.$_oncss.'">'.$opt['opt_name'].'</a></li>';
                            }
                            $content .= "</ul>";
                            echo $content;
                        echo '</div>';
                    }
                   
                }
            }?>
            <?php if ( (_cao('is_filter_item_price', '0') || _cao('is_filter_item_order', '0')) && is_site_shop_open() ) : ?>
            <div class="filter-tab">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <?php if (_cao('is_filter_item_price','1')) : 
                            $is_on = !empty($_GET['cao_type']) ? $_GET['cao_type'] : '';
                            $cao_vip_name = _cao('site_vip_name');
                            $content = '<ul class="filter-tag"><span><i class="fa fa-filter"></i> 价格</span>';
                            $caotype_arr = array('0' => '全部','1' => '免费','2' => '付费' ,'3' => $cao_vip_name.'免费','4' => $cao_vip_name.'优惠');
                            foreach ($caotype_arr as $key => $item) {
                                $_oncss = ($is_on == $key) ? 'on' : '' ;
                                $content .= '<li><a href="'.add_query_arg("cao_type",$key).'" class="tab '.$_oncss.'"><i></i><em>'.$item.'</em></a></li>';
                            }
                            $content .= "</ul>";
                            echo $content;
                        endif; ?>
                    </div>
                    <div class="col-12 col-sm-6">
                        <!-- 排序 -->
                        <?php if (_cao('is_filter_item_order','1')) : 
                                $is_on = !empty($_GET['order']) ? $_GET['order'] : 'date';
                                $content = '<ul class="filter-tag" style="width: 100%;"><div class="right">';
                                $order_arr = array('date' => '发布日期','modified' => '修改时间','comment_count' => '评论数量','rand' => '随机','hot' => '热度');
                                foreach ($order_arr as $key => $item) {
                                    $_oncss = ($is_on == $key) ? 'on' : '' ;
                                    $content .= '<li class="rightss"><i class="fa fa-caret-down"></i> <a href="'.add_query_arg("order",$key).'" class="'.$_oncss.'">'.$item.'</a></li>';
                                }
                                $content .= "</div></ul>";
                                echo $content;
                        endif; ?>
                        
                    </div>
                </div>
            </div>
            <?php endif;?>

            <!-- .row end -->
        </div>
        <!-- .form-box end -->
    </form>
</div>
<?php endif;?>