<?php if (!defined('ABSPATH')) {die;} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = '_caozhuti_profile_options';

//
// Create profile options
//
CSF::createProfileOptions($prefix, array(
    'data_type' => 'unserialize',
));

/**
 * 用户高级信息设置
 */
CSF::createSection($prefix, array(
    'title'  => '用户高级信息',
    'fields' => array(

        array(
            'id'      => 'cao_user_type',
            'type'    => 'select',
            'title'   => '用户权限',
            'options' => array(
                'no'  => _cao('site_no_vip_name'),
                'vip' => _cao('site_vip_name'),
            ),
        ),

        array(
            'id'         => 'cao_vip_end_time',
            'type'       => 'date',
            'title'      => _cao('site_vip_name') . '用户到期时间',
            'desc'      => '如果要设置永久会员，请手动吧到期日期改为：9999-09-09',
            'settings'   => array(
                'dateFormat' => 'yy-mm-dd', //date("Y-m-d");
            ),
            'dependency' => array('cao_user_type', '==', 'vip'),
        ),

        array(
            'id'         => 'cao_balance',
            'type'       => 'text',
            'title'      => '用户余额',
            'default'    => '0.00',
        ),
        array(
            'id'         => 'cao_consumed_balance',
            'type'       => 'text',
            'title'      => '用户已消费余额',
            'attributes' => array(
                'readonly' => 'readonly',
            ),
            'default'    => '0.00',
        ),

        array(
            'id'    => 'cao_banned',
            'type'  => 'switcher',
            'title' => '封禁用户',
        ),
        array(
            'id'         => 'cao_banned_reason',
            'type'       => 'textarea',
            'title'      => '封号原因',
            'default'    => '本站猜测您太嚣张，人品太差，运气不行，恶意攻击，评论，赠予封号！',
            'dependency' => array('cao_banned', '==', 'true'),
        ),
        array(
            'id'         => 'cao_ref_from',
            'type'       => 'text',
            'title'      => '推荐人ID',
            'attributes' => array(
                'readonly' => 'readonly',
            ),
            'default'    => '0',
        ),

    ),
));
