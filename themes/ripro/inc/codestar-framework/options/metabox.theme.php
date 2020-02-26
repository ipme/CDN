<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.


if (!_cao('close_site_shop','0')) {
    $prefix_post_opts = '_cao_post_options';
    CSF::createMetabox($prefix_post_opts, array(
        'title'     => '文章资源设置',
        'post_type' => 'post',
        'data_type' => 'unserialize',
        'priority'  => 'high',
    ));

    CSF::createSection($prefix_post_opts, array(
        'fields' => array(
            array(
                'id'      => 'post_style',
                'type'    => 'radio',
                'title'   => '文章页布局风格',
                'inline'  => true,
                'options' => array(
                    'sidebar'    => '常规侧边栏',
                    'no_sidebar' => '全宽',
                ),
                'default' => 'sidebar',
                'desc'    => '全宽模式将不显示下载小工具，适合查看付费内容哦',
            ),

            array(
                'id'      => 'cao_price',
                'type'    => 'text',
                'title'   => '资源价格：*' . _cao('site_money_ua'),
                'default' => '1',
                'desc'    => '【如果设置为0则等于免费资源】',
                // 'dependency' => array('cao_status', '==', 'true'),
            ),

            array(
                'id'      => 'cao_vip_rate',
                'type'    => 'slider',
                'title'   => _cao('site_vip_name') . '折扣：*',
                'default' => '1',
                'max'     => '1',
                'min'     => '0',
                'step'    => '0.1',
                'desc'    => '【0.N 等于N折；1 等于不打折；0 等于免费】',
                // 'dependency' => array('cao_status', '==', 'true'),
            ),


            array(
                'id'    => 'cao_is_boosvip',
                'type'  => 'switcher',
                'title' => '仅限永久'._cao('site_vip_name').'免费',
                'label' => '开启后此资源永久'._cao('site_vip_name').'免费购买,其他会员按折扣或者原价购买',
            ),

            array(
                'id'    => 'cao_status',
                'type'  => 'switcher',
                'title' => '启用付费下载资源',
                'label' => '开启后可设置下载专有属性，优先级是付费内容优先展示',
            ),

            array(
                'id'         => 'cao_downurl',
                'type'       => 'upload',
                'title'      => '资源下载地址：',
                'desc'       => '可直接粘贴：支持https:,thunder:,magnet:,ed2k 开头地址，如果是本地上传，则自动隐藏真实下载地址',
                'dependency' => array('cao_status', '==', 'true'),
            ),
            array(
                'id'         => 'cao_downurl_bak',
                'type'       => 'upload',
                'title'      => '备用资源地址：',
                'desc'       => '该地址为备用资源地址，方便站长多个网盘备用留存，地址失效有一个留存，注意此地址不在前端展示',
                'dependency' => array('cao_status', '==', 'true'),
            ),
            array(
                'id'         => 'cao_diy_btn',
                'type'       => 'text',
                'title'      => '自定义按钮(NEW)',
                'subtitle'   => '为空则不显示，用 | 隔开',
                'desc'       => '格式： 下载免费版|https://www.baidu.com/',
                'dependency' => array('cao_status', '==', 'true'),
            ),
            array(
                'id'         => 'cao_pwd',
                'type'       => 'text',
                'title'      => '文件密码',
                'desc'       => '不填写则无需密码',
                'dependency' => array('cao_status', '==', 'true'),
            ),
            array(
                'id'         => 'cao_demourl',
                'type'       => 'text',
                'title'      => '演示地址',
                'subtitle'   => '为空则不显示',
                'dependency' => array('cao_status', '==', 'true'),
            ),
            array(
                'id'         => 'cao_info',
                'type'       => 'repeater',
                'title'      => '资源其他信息',
                'dependency' => array('cao_status', '==', 'true'),
                'fields'     => array(
                    array(
                        'id'      => 'title',
                        'type'    => 'text',
                        'title'   => '标题',
                        'default' => '标题',
                    ),
                    array(
                        'id'      => 'desc',
                        'type'    => 'text',
                        'title'   => '描述内容',
                        'default' => '这里是描述内容',
                    ),
                ),
            ),

            array(
                'id'      => 'cao_paynum',
                'type'    => 'text',
                'title'   => '已售数量',
                'default' => '0',
                // 'dependency' => array('cao_status', '==', 'true'),
            ),

        ),
    ));
}


// [自定义文章自动高级玩家专属 感谢会员昵称 股东·烧饼 QQ：6337766 的赞助和监督]
if (_cao('is_custom_post_meta_opt', '0') && _cao('custom_post_meta_opt', '0')) {
    //获取玩家配置
    $prefix_post_opts = '_custom_post_opts';
    CSF::createMetabox($prefix_post_opts, array(
        'title'     => '高级自定义文章属性',
        'post_type' => 'post',
        'data_type' => 'unserialize',
        'context'   => 'side',
    ));

    $custom_post_meta_opt = _cao('custom_post_meta_opt', '0');
    $fields_item          = array();
    foreach ($custom_post_meta_opt as $k => $v) {
        $opt = array('all' => '默认');
        foreach ($v['meta_opt'] as $value) {
            $_key       = $value['opt_ua'];
            $opt[$_key] = $value['opt_name'];
        }
        $item = array(
            'id'      => $v['meta_ua'],
            'type'    => 'select',
            'title'   => $v['meta_name'],
            'options' => $opt,
            'default' => 'option-2',
        );
        array_push($fields_item, $item);
    }
    CSF::createSection($prefix_post_opts, array(
        'fields' => $fields_item,
    ));
}

// prefix_post_thumb_mod
$prefix_post_video = '_cao_post_video_url';
CSF::createMetabox($prefix_post_video, array(
    'title'     => '文章内建视频',
    'post_type' => 'post',
    'data_type' => 'unserialize',
    'priority'  => 'default',
    'context'   => 'side',
));

CSF::createSection($prefix_post_video, array(
    'fields' => array(
        array(
            'id'         => 'video_url',
            'type'       => 'upload',
            'title'      => '视频地址',
            'desc'       => '可粘贴Mp4格式文件地址或本地上传',
        ),
        array(
            'id'         => 'video_poster_url',
            'type'       => 'upload',
            'title'      => '视频封面图',
            'desc'       => '不设置则自动获取文章缩略图',
        ),
        array(
            'id'    => 'is_video_danmu',
            'type'  => 'switcher',
            'title' => '是否启用弹幕功能',
            'label' => '自动根据标题/摘要/最新评论获取骚弹幕',
            'default' => true,
        ),
    ),
));



// prefix_page_opts
$prefix_page_opts = '_cao_page_options';

CSF::createMetabox($prefix_page_opts, array(
    'title'     => '页面布局',
    'post_type' => 'page',
    'data_type' => 'unserialize',
    'priority'  => 'high',
));

CSF::createSection($prefix_page_opts, array(
    'fields' => array(
        array(
            'id'      => 'post_style',
            'type'    => 'image_select',
            'title'   => '文章页布局风格',
            'options' => array(
                'sidebar'    => get_template_directory_uri() . '/assets/images/option/sidebar.jpg',
                'no_sidebar' => get_template_directory_uri() . '/assets/images/option/no-sidebar.jpg',
            ),
            'default' => 'sidebar',
        ),

    ),
));

if (!_cao('del_ripro_seo','0')) {
   $prefix_post_opts_seo = 'seo-postmeta-box';
    CSF::createMetabox($prefix_post_opts_seo, array(
        'title'     => '自定义文章SEO信息',
        'post_type' => 'post',
        'data_type' => 'unserialize',
    ));
    CSF::createSection($prefix_post_opts_seo, array(
        'fields' => array(

            array(
                'id'      => 'post_titie_s',
                'type'    => 'switcher',
                'title'   => '自定义SEO标题',
                'label'   => '不设置则自动根据WP规则显示',
                'default' => false,
            ),
            array(
                'id'         => 'post_titie',
                'type'       => 'text',
                'title'      => 'SEO标题',
                'subtitle'   => '',
                'dependency' => array('post_titie_s', '==', 'true'),
            ),
            array(
                'id'      => 'post_description_s',
                'type'    => 'switcher',
                'title'   => '自定义SEO关描述',
                'label'   => '不设置则自动根据分类，标签抓取',
                'default' => false,
            ),
            array(
                'id'         => 'description',
                'type'       => 'textarea',
                'title'      => '描述内容',
                'subtitle'   => '字数控制到80-150最佳',
                'dependency' => array('post_description_s', '==', 'true'),
            ),

            array(
                'id'      => 'post_keywords_s',
                'type'    => 'switcher',
                'title'   => '自定义SEO关键词',
                'label'   => '不设置则自动根据分类，标签抓取',
                'default' => false,
            ),
            array(
                'id'         => 'keywords',
                'type'       => 'textarea',
                'title'      => '关键词',
                'subtitle'   => '关键词用英文逗号,隔开',
                'dependency' => array('post_keywords_s', '==', 'true'),
            ),

        ),
    ));
 
}
