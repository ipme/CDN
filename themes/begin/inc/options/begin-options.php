<?php

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = wp_get_theme();
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

function optionsframework_options() {

	$blogpath =  get_stylesheet_directory_uri() . '/img';

	// 将所有分类（categories）加入数组
	$options_categories = array();
	$options_categories_obj = get_categories(array('hide_empty' => 0));
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// 所有分类ID
	$categories = get_categories(array('hide_empty' => 0)); 
	foreach ($categories as $cat) {
	$cats_id .= '<li>'.$cat->cat_name.' [ '.$cat->cat_ID.' ]</li>';
	}

	// 所有视频分类ID
	$categories = get_categories(array('taxonomy' => 'videos', 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
	$catv_id .= '<li>'.$cat->cat_name.' [ '.$cat->cat_ID.' ]</li>';
	}

	// 所有图片分类ID
	$categories = get_categories(array('taxonomy' => 'gallery', 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
	$catp_id .= '<li>'.$cat->cat_name.' [ '.$cat->cat_ID.' ]</li>';
	}

	// 所有产品分类ID
	$categories = get_categories(array('taxonomy' => 'products', 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
	$catpr_id .= '<li>'.$cat->cat_name.' [ '.$cat->cat_ID.' ]</li>';
	}

	// 所有商品分类ID
	$categories = get_categories(array('taxonomy' => 'taobao', 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
	$catt_id .= '<li>'.$cat->cat_name.' [ '.$cat->cat_ID.' ]</li>';
	}

	// 所有公告分类ID
	$categories = get_categories(array('taxonomy' => 'notice', 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
	$catb_id .= '<li>'.$cat->cat_name.' [ '.$cat->cat_ID.' ]</li>';
	}

	// 所有商品分类ID
	$categories = get_categories(array('taxonomy' => 'product_cat', 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
	$catr_id .= '<li>'.$cat->cat_name.' [ '.$cat->cat_ID.' ]</li>';
	}

	// 所有下载分类ID
	$categories = get_categories(array('taxonomy' => 'download_category', 'hide_empty' => 0)); 
	foreach ($categories as $cat) {
	$eddcat_id .= '<li>'.$cat->cat_name.' [ '.$cat->cat_ID.' ]</li>';
	}

	// 所有专题页面ID
	$options_pages_obj = get_pages( array( 'meta_key' => 'special' ) );
	foreach ($options_pages_obj as $page) {
	$special_id .= '<li>'.$page->post_title.' [ '.$page->ID.' ]</li>';
	}

	// 将所有标签（tags）加入数组
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}

	// 将所有页面（pages）加入数组
	$options_pages = array();
	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
	$options_pages[''] = '选择页面:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// 位置
	$test_array = array(
		'' => '中',
		't' => '上',
		'b' => '下',
		'l' => '左',
		'r' => '右'
	);

	//数字
	$grid_ico_cms_n = array(
		'2' => '两栏',
		'4' => '四栏',
		'' => '六栏',
		'8' => '八栏'
	);

	//数字
	$grid_ico_group_n = array(
		'2' => '两栏',
		'4' => '四栏',
		'' => '六栏',
		'8' => '八栏'
	);

	// 收藏
	$favorite_array = array(
		'favorite_t' => '显示在正文上面',
		'favorite_b' => '显示在正文下面'
	);

	$options = array();


	// 首页设置

	$options[] = array(
		'name' => '首页设置',
		'type' => 'heading'
	);

    $options[] = array(
		'name' => '首页布局选择',
		'id' => 'layout',
		'class' => 'be_ico',
		'std' => 'blog',
		'type' => 'radio',
		'options' => array(
			'blog' => '博客布局',
			'img' => '图片布局',
			'grid' => '分类图片',
			'cms' => '杂志布局',
			'group' => '公司主页',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '首页幻灯',
		'desc' => '显示（注：至少添加两张幻灯片）',
		'id' => 'slider',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义排序，需给幻灯中的文章添加排序编号',
		'id' => 'show_order',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动裁剪图片',
		'id' => 'show_img_crop',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'slider_n',
		'std' => '2',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '博客布局排除的分类文章',
		'desc' => '输入排除的分类ID，多个分类用半角逗号","隔开',
		'id' => 'not_cat_n',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示博客布局文章排序按钮',
		'id' => 'order_by',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '首页推荐文章',
		'desc' => '显示',
		'id' => 'cms_top',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：1',
		'id' => 'cms_top_s',
		'class' => 'mini hidden',
		'std' => '1',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇',
		'id' => 'cms_top_n',
		'class' => 'mini hidden',
		'std' => '4',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '全部分类链接',
		'desc' => '显示',
		'id' => 'cat_all',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入排除的分类ID，比如：1,2',
		'id' => 'cat_all_e',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '首页分类封面',
		'desc' => '显示',
		'id' => 'h_cat_cover',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用标签',
		'id' => 'cat_tag_cover',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类或标签ID，多个ID用英文半角逗号","隔开',
		'id' => 'cat_cover_id',
		'class' => 'hidden',
		'std' => '1,2',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '移动端首页显示的页面',
		'desc' => '输入链接地址，不使用请留空',
		'id' => 'mobile_home_url',
		'class' => 'be_ico',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '首页多条件筛选',
		'id' => 'filter_general',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片布局显示摘要',
		'id' => 'hide_box',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '首页新窗口或标签打开链接',
		'id' => 'blank',
		'std' => '0',
		'type' => 'checkbox'
	);

	// 基本设置

	$options[] = array(
		'name' => '基本设置',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '管理站点',
		'desc' => '显示',
		'id' => 'profile',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '登录按钮',
		'id' => 'login',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '登录按钮链接到默认登录页面',
		'id' => 'user_l',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '找回密码',
		'id' => 'reset_pass',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '注册直接输入密码（用于默认登录）',
		'id' => 'go_reg',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '注册后自动登录并跳转到首页（第三方注册登录不要启用）',
		'id' => 'reg_home',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '顶部欢迎语',
		'desc' => '',
		'id' => 'wel_come',
		'class' => 'hidden',
		'std' => '欢迎光临！',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '注册按钮',
		'desc' => '输入注册页面地址，留空则显示欢迎语',
		'id' => 'reg_url',
		'class' => 'hidden',
		'placeholder' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '用户中心',
		'desc' => '选择用户中心页面',
		'id' => 'user_url',
		'type' => 'select',
		'class' => 'mini be_ico',
		'options' => $options_pages
	);

	if (function_exists( 'fep_get_plugin_caps' )) {
		$options[] = array(
			'name' => '站内信息',
			'desc' => '选择站内信页面',
			'id' => 'front_end_pm',
			'type' => 'select',
			'class' => 'mini',
			'options' => $options_pages
		);
	}

	$options[] = array(
		'name' => '用户投稿',
		'desc' => '选择投稿页面',
		'id' => 'tou_url',
		'type' => 'select',
		'class' => 'mini',
		'options' => $options_pages
	);

	$options[] = array(
		'name' => '用户中心背景图片',
		'desc' => '上传背景图片',
		'id' => 'personal_img',
        "std" => "https://s2.ax1x.com/2019/05/31/Vl0QET.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '',
		'desc' => '非管理员不显示站点管理',
		'id' => 'no_admin',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '修改默认登录链接',
		'desc' => '启用，提示：要记住修改后的链接',
		'id' => 'login_link',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'desc' => '前缀',
		'id' => 'pass_h',
		'std' => 'my',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '后缀',
		'id' => 'word_q',
		'std' => 'the',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '跳转网址',
		'id' => 'go_link',
		'std' => '链接地址',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '登录地址：http://域名/wp-login.php?my=the',
		'id' => 'login_s'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '导航菜单固定模式',
		'id' => 'menu_m',
		'class' => 'be_ico',
		'std' => 'menu_d',
		'type' => 'radio',
		'options' => array(
			'menu_d' => '正常模式',
			'menu_n' => '永不固定',
			'menu_g' => '保持固定',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '通用头部模式',
		'desc' => '启用',
		'id' => 'header_normal',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '通长背景',
		'id' => 'menu_full',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '头部自定义内容',
		'id' => 'header_contact',
		'class' => 'hidden',
		'std' => '<div class="contact-main contact-l"><i class="be be-phone"></i>13088888888</div><div class="contact-main contact-r"><i class="be be-display"></i>联系我们</div>',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '菜单图文',
		'desc' => '显示',
		'id' => 'menu_post',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字',
		'id' => 'menu_post_t',
		'class' => 'hidden',
		'std' => '推荐文章',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '小图标',
		'id' => 'menu_post_ico',
		'class' => 'hidden',
		'std' => 'be be-star',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '移动端导航菜单',
		'desc' => '单独的移动端菜单（有特殊需要时启用）',
		'id' => 'm_nav',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端页脚菜单',
		'id' => 'footer_menu',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '菜单项可见性',
		'id' => 'menu_visibility',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '隐藏顶部站点管理及菜单',
		'id' => 'top_nav_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '在移动端显示登录按钮',
		'id' => 'mobile_login',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端导航按钮链接到页面',
		'id' => 'nav_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '选择页面',
		'id' => 'nav_url',
		'type' => 'select',
		'class' => 'mini',
		'options' => $options_pages
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '默认搜索设置',
		'desc' => '启用',
		'id' => 'wp_s',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '只搜索标题',
		'id' => 'search_title',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '搜索选项',
		'id' => 'search_option',
		'class' => 'hidden',
		'std' => 'search_default',
		'type' => 'radio',
		'options' => array(
			'search_default' => '默认',
			'search_url' => '修改搜索URL',
			'search_cat' => '分类搜索',
		)
	);

	$options[] = array(
		'name' => '搜索结果布局',
		'id' => 'search_the',
		'class' => 'hidden',
		'std' => 'search_list',
		'type' => 'radio',
		'options' => array(
			'search_list' => '标题布局',
			'search_img' => '图片布局',
			'search_normal' => '标准布局',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '排除的分类',
		'id' => 'not_search_cat',
		'class' => 'hidden',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '百度站内搜索',
		'id' => 'baidu_s',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '360站内搜索',
		'id' => '360_s',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '搜狗站内搜索',
		'id' => 'sogou_s',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '搜索推荐',
		'id' => 'search_nav',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '二级菜单显示描述',
		'id' => 'menu_des',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '文章列表截断字数',
		'desc' => '自动截断字数，默认值：100',
		'id' => 'words_n',
		'std' => '100',
		'class' => 'mini be_ico',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '摘要截断字数，默认值：90',
		'id' => 'word_n',
		'std' => '90',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

    $options[] = array(
		'name' => '缩略图方式',
		'id' => 'img_way',
		'class' => 'be_ico',
		'std' => 'd_img',
		'type' => 'radio',
		'options' => array(
			'd_img' => '默认缩略图',
			'o_img' => '阿里云OSS ',
			'q_img' => '七牛缩略图',
			'upyun' => '又拍云',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '缩略图自动裁剪设置',
		'id' => 'c_img',
		'class' => 'be_ico'
	);

	$options[] = array(
		'desc' => '缩略裁剪位置',
		'id' => 'img_crop',
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'crop_top',
		'std' => '',
		'type' => 'radio',
		'options' => $test_array
	);

	$options[] = array(
		'desc' => '标准缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'img_w',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'img_h',
		'std' => '210',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '杂志分类模块缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 560',
		'id' => 'img_k_w',
		'std' => '560',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 230',
		'id' => 'img_k_h',
		'std' => '230',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '图片布局缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'grid_w',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'grid_h',
		'std' => '210',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '图片缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'img_i_w',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'img_i_h',
		'std' => '210',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '视频缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'img_v_w',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'img_v_h',
		'std' => '210',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '商品缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 400',
		'id' => 'img_t_w',
		'std' => '400',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 400',
		'id' => 'img_t_h',
		'std' => '400',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '首页幻灯',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 800',
		'id' => 'img_h_w',
		'std' => '800',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 300',
		'id' => 'img_h_h',
		'std' => '300',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '幻灯小工具',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 350',
		'id' => 'img_s_w',
		'std' => '350',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 260',
		'id' => 'img_s_h',
		'std' => '260',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '专题封面',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 400',
		'id' => 'img_sp_w',
		'std' => '400',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 300',
		'id' => 'img_sp_h',
		'std' => '300',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '分类封面',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 400',
		'id' => 'img_co_w',
		'std' => '400',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 300',
		'id' => 'img_co_h',
		'std' => '300',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '分类图片',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 1200',
		'id' => 'img_des_w',
		'std' => '1200',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 250',
		'id' => 'img_des_h',
		'std' => '250',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '分类宽图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 900',
		'id' => 'img_full_w',
		'std' => '900',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 350',
		'id' => 'img_full_h',
		'std' => '350',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '网址缩略图',
		'id' => 'img_c',
	);

	$options[] = array(
		'desc' => '宽 默认 280',
		'id' => 'sites_w',
		'std' => '280',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '高 默认 210',
		'id' => 'sites_h',
		'std' => '210',
		'type' => 'text'
	);


	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '限制文章列表缩略图',
		'desc' => '缩略图最大宽度，默认值：200',
		'id' => 'thumbnail_width',
		'class' => 'be_ico',
        'std' => '',
		'type' => 'text'
    );

    $options[] = array(
		'name' => '',
		'desc' => '调整信息位置，默认距左：240',
		'id' => 'meta_left',
        'std' => '',
		'type' => 'text'
    );

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '随机缩略图',
		'desc' => '默认 5 张图片',
		'id' => 'rand_img_n',
		'std' => '5',
		'class' => 'mini be_ico',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动裁剪',
		'id' => 'clipping_rand_img',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章中无图，不显示随机缩略图',
		'id' => 'no_rand_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '标准随机缩略图链接，多张图片中间用半角逗号","隔开',
		'desc' => '',
		'id' => 'random_image_url',
		'std' => 'https://s2.ax1x.com/2019/05/31/Vl0fVf.jpg,https://s2.ax1x.com/2019/05/31/Vl04IS.jpg,https://s2.ax1x.com/2019/05/31/Vl0RqP.jpg,https://s2.ax1x.com/2019/05/31/Vl0ha8.jpg,https://s2.ax1x.com/2019/05/31/Vl02rt.jpg',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '分类模块随机缩略图链接，多张图片中间用半角逗号","隔开',
		'desc' => '',
		'id' => 'random_long_url',
		'std' => 'https://s2.ax1x.com/2019/10/11/uq0dXt.jpg,https://s2.ax1x.com/2019/10/11/uq0U1A.jpg,https://s2.ax1x.com/2019/10/11/uq0a6I.jpg,https://s2.ax1x.com/2019/10/11/uq0Npd.jpg,https://s2.ax1x.com/2019/10/11/uq0YfH.jpg',
		'type' => 'textarea'
	);


	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '特色图片',
		'desc' => '启用特色图片，如不使用该功能请不要开启',
		'id' => 'wp_thumbnails',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '特色图片自动裁剪',
		'id' => 'clipping_thumbnails',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '外链图片自动本地化（酌情开启）',
		'id' => 'save_image',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止WP自动裁剪图片',
		'id' => 'disable_img_sizes',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '手动缩略图自动裁剪',
		'id' => 'manual_thumbnail',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '专题自动裁剪缩略图',
		'id' => 'special_thumbnail',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网址自动裁剪缩略图',
		'id' => 'shot_thumbnail',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '图片延迟加载',
		'desc' => '缩略图延迟加载',
		'id' => 'lazy_s',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文图片延迟加载',
		'id' => 'lazy_e',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '留言头像延迟加载',
		'id' => 'lazy_c',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '公告',
		'desc' => '显示，并代替首页面包屑导航',
		'id' => 'bulletin',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '必须输入一个公告分类ID',
		'id' => 'bulletin_id',
		'class' => 'hidden',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '公告分类ID对照',
		'desc' => '<ul>'.$catb_id.'</ul>',
		'class' => 'bulletin_id hidden',
		'id' => 'catids',
		'type' => 'info'
	);

	$options[] = array(
		'name' => '',
		'desc' => '公告滚动篇数',
		'id' => 'bulletin_n',
		'std' => '2',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义阅读全文按钮文字',
		'id' => 'more_w',
		'std' => '阅读全文',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义直达链接按钮文字',
		'id' => 'direct_w',
		'std' => '直达链接',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '按钮默认隐藏',
		'id' => 'more_hide',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'Ajax加载文章',
		'id' => 'infinite_post',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '文章信息设置',
		'desc' => '详细设置',
		'id' => 'post_meta_inf',
		'class' => 'be_ico be_ico_m',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章信息显示在标题下面',
		'id' => 'meta_b',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文显示作者信息',
		'id' => 'meta_author_single',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章列表显示作者信息',
		'id' => 'meta_author',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网格模块不显示作者信息',
		'id' => 'author_hide',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '朗读文章',
		'id' => 'tts_play',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '打印按钮',
		'id' => 'print_on',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章字数',
		'id' => 'word_count',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '阅读时间',
		'id' => 'reading_time',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端隐藏字数和阅读时间',
		'id' => 'word_time',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '使用标准日期格式',
		'id' => 'meta_time',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);


	$options[] = array(
		'name' => '',
		'desc' => '显示时间',
		'id' => 'meta_time_second',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示百度收录与否',
		'id' => 'baidu_record',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '评论相关设置',
		'desc' => '详细设置',
		'id' => 'comment_related',
		'class' => 'be_ico be_ico_m',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'Ajax评论',
		'id' => 'comment_ajax',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论Ajax翻页',
		'id' => 'infinite_comment',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论@回复',
		'id' => 'at',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => 'QQ快速评论',
		'id' => 'qq_info',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '回复邮件通知',
		'id' => 'mail_notify',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '解锁提交评论',
		'id' => 'qt',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论只填写昵称',
		'id' => 'no_email',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '删除评论网址表单',
		'id' => 'no_comment_url',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '默认隐藏评论表单',
		'id' => 'not_comment_form',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论检查中文',
		'id' => 'refused_spam',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论等级',
		'id' => 'vip',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论楼层',
		'id' => 'comment_floor',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论贴图',
		'id' => 'embed_img',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论表情',
		'id' => 'emoji_show',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止评论HTML',
		'id' => 'comment_html',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '删除评论按钮',
		'id' => 'del_comment',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止评论超链接',
		'id' => 'comment_url',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '关闭评论',
		'id' => 'close_comments',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止冒充管理员留言',
		'id' => 'check_admin',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '管理员名称',
		'id' => 'admin_name',
		'class' => 'hidden',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '管理员邮箱',
		'id' => 'admin_email',
		'class' => 'hidden',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '历史上的今天',
		'id' => 'begin_today',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片Lightbox查看',
		'id' => 'lightbox_on',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '隐藏缩略图上分类名称',
		'id' => 'no_thumbnail_cat',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);


	$options[] = array(
		'name' => '',
		'desc' => '可视化编辑器',
		'id' => 'visual_editor',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '段首空格',
		'id' => 'p_first',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文展开全文按钮',
		'id' => 'all_more',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '编辑器增加中文字体',
		'id' => 'custum_font',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '在线视频支持',
		'id' => 'smart_ideo',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '侧边栏跟随滚动',
		'id' => 'sidebar_sticky',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '动画特效',
		'id' => 'wow_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动代码高亮显示',
		'id' => 'gcp_code',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '手动代码高亮显示',
		'id' => 'highlight',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '四级标题作为文章索引目录',
		'id' => 'index_c',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁用xmlrpc',
		'id' => 'xmlrpc_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁用文章修订',
		'id' => 'revisions_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁用oEmbed',
		'id' => 'embed_no',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁用 REST API，连接小程序需取消',
		'id' => 'disable_api',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '不显示分类链接中的"category"',
		'id' => 'no_category',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类页面添加"/"斜杠',
		'id' => 'category_x',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);
/*
	$options[] = array(
		'name' => '',
		'desc' => '修改文章分页链接',
		'id' => 'link_pages',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);
*/
	$options[] = array(
		'name' => '',
		'desc' => '页面添加.html后缀',
		'id' => 'page_html',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动将文章标题作为图片ALT标签内容',
		'id' => 'image_alt',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '替换作者默认链接',
		'id' => 'my_author',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示左右上下篇按钮',
		'id' => 'meta_nav_lr',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '支持中文注册登录',
		'id' => 'cn_user',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示用户登录注册时间',
		'id' => 'last_login',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '复制提示',
		'id' => 'copy_tips',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁止复制及右键',
		'id' => 'copyright_pro',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章分页显示全部按钮',
		'id' => 'link_pages_all',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章外链跳转',
		'id' => 'link_to',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '评论者链接跳转',
		'id' => 'comment_to',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '外链接添加 _blank和nofollow',
		'id' => 'link_external',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动为文章中的关键词添加链接',
		'id' => 'tag_c',
		'class' => '',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '小工具条件判断',
		'id' => 'widget_logic',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '头部小工具',
		'id' => 'header_widget',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文底部小工具',
		'id' => 'single_e',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '页脚小工具',
		'id' => 'footer_w',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端不显示页脚小工具',
		'id' => 'mobile_footer_w',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示分类推荐文章',
		'id' => 'cat_top',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类归档不显示子分类文章',
		'id' => 'no_child',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '归档页显示同级分类链接',
		'id' => 'child_cat',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类排序',
		'id' => 'cat_order',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类图标',
		'id' => 'cat_icon',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类封面',
		'desc' => '启用',
		'id' => 'cat_cover',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动裁剪缩略图',
		'id' => 'cat_cover_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类图片',
		'desc' => '显示（分类填写图像描述才能显示）',
		'id' => 'cat_des',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示分类描述',
		'id' => 'cat_des_p',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自动裁剪图片',
		'id' => 'cat_des_img',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '默认图片',
		'desc' => '上传默认图片',
		'id' => 'cat_des_img_d',
		'class' => 'hidden',
		"std" => "https://s2.ax1x.com/2019/08/17/mnVXB4.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '选择不同分类布局',
		'desc' => '图片布局（输入分类ID，多个ID用","隔开，以下相同）',
		'id' => 'cat_layout_img',
		'class' => 'be_ico',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片布局，有侧边栏',
		'id' => 'cat_layout_img_s',
		'class' => 'be_ico',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片布局（可单独设置缩略图大小）',
		'id' => 'cat_layout_grid',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片布局，有播放图标',
		'id' => 'cat_layout_play',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '通长缩略图',
		'id' => 'cat_layout_full',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标题列表',
		'id' => 'cat_layout_list',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '格子标题',
		'id' => 'cat_layout_title',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '时间轴',
		'id' => 'cat_layout_line',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '瀑布流',
		'id' => 'cat_layout_fall',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '子分类',
		'id' => 'cat_layout_child',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '子分类图片',
		'id' => 'cat_layout_child_img',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片分类归档使用瀑布流',
		'id' => 'gallery_fall',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '正文底部版权信息',
		'desc' => '显示',
		'id' => 'copyright',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示作者头像',
		'id' => 'copyright_avatar',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义版权信息第一行',
		'id' => 'copyright_statement',
		'class' => 'hidden',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义版权信息第二行',
		'id' => 'copyright_indicate',
		'class' => 'hidden',
		'std' => '',
		'type' => 'textarea'
	);


	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '正文相关文章图片',
		'id' => 'related_img',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'related_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '正文底部商品',
		'desc' => '显示',
		'id' => 'single_tao',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'single_tao_n',
		'std' => '4',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '图片、视频、商品文章归档',
		'desc' => '篇数，注：要求大于WP阅读设置页面博客页面至多篇数',
		'id' => 'taxonomy_cat_n',
		'std' => '16',
		'class' => 'mini be_ico',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示该类型所有分类链接',
		'id' => 'type_cat',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '图片、视频、商品、Edd下载分类、Woo产品分类页面模板',
		'desc' => '篇数',
		'id' => 'custom_cat_n',
		'std' => '12',
		'class' => 'mini be_ico',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '最新文章图标',
		'desc' => '显示',
		'id' => 'news_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '默认一周（168小时）内发表的文章显示，最短24小时',
		'id' => 'new_n',
		'std' => '168',
		'class' => 'mini be_ico',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '文章更新页面模板',
		'desc' => '留空则显示全部文章',
		'id' => 'up_t',
		'class' => 'be_ico'
	);

	$options[] = array(
		'name' => '',
		'desc' => '年份',
		'id' => 'year_n',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '月份',
		'id' => 'mon_n',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类ID',
		'id' => 'cat_up_n',
		'std' => '',
		'type' => 'text'
	);


	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '首页页脚链接',
		'desc' => '显示',
		'id' => 'footer_link',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '可以输入链接分类ID，显示特定的链接在首页，留空则显示全部链接',
		'id' => 'link_f_cat',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '以图片形式显示链接并显示链接分类名称',
		'id' => 'footer_img',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端不显示',
		'id' => 'footer_link_no',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '友情链接页面',
		'desc' => '可以输入链接分类ID，只显示特定的链接，留空显示全部链接',
		'id' => 'link_cat',
		'class' => 'be_ico',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '选择友情链接页面',
		'id' => 'link_url',
		'type' => 'select',
		'class' => 'mini',
		'options' => $options_pages
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示链接分类名称',
		'id' => 'linkcat_h2',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '以图片形式显示链接',
		'id' => 'link_all_img',
		'std' => '0',
		'type' => 'checkbox'
	);

	// CMS设置
	$options[] = array(
		'name' => '杂志布局',
		'type' => 'heading'
	);

    $options[] = array(
		'name' => '幻灯显示模式',
		'id' => 'slider_l',
		'class' => 'be_ico',
		'std' => 'slider_n',
		'type' => 'radio',
		'options' => array(
			'slider_n' => '标准',
			'slider_w' => '通栏',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '专题',
		'desc' => '显示',
		'id' => 'cms_special',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入专题页面ID',
		'id' => 'cms_special_id',
		'std' => '',
		'class' => 'hidden',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '专题页面ID',
		'desc' => '<ul>'.$special_id.'</ul>',
		'id' => 'specialid',
		'class' => 'special_id hidden',
		'type' => 'info'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '最新文章',
		'desc' => '显示',
		'id' => 'news',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：2',
		'id' => 'news_s',
		'class' => 'mini hidden',
		'std' => '2',
		'type' => 'text'
	);

    $options[] = array(
		'name' => '',
		'id' => 'news_model',
		'std' => 'news_grid',
		'class' => 'hidden',
		'type' => 'radio',
		'options' => array(
			'news_grid' => '网格模式',
			'news_normal' => '标准模式',
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '最新文章篇数',
		'id' => 'news_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入排除的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'not_news_n',
		'std' => '',
		'class' => 'hidden',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '图文模块',
		'desc' => '显示（位于最新文章模块中）',
		'id' => 'post_img',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'post_img_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '多条件筛选',
		'id' => 'cms_filter_h',
		'std' => '0',
		'type' => 'checkbox'
	);
	$options[] = array(
		'name' => '',
		'desc' => '排序：3',
		'id' => 'cms_filter_s',
		'class' => 'mini hidden',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '杂志单栏小工具',
		'id' => 'cms_widget_one',
		'std' => '0',
		'type' => 'checkbox'
	);
	$options[] = array(
		'name' => '',
		'desc' => '排序：3',
		'id' => 'cms_widget_one_s',
		'class' => 'mini hidden',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '图片模块',
		'desc' => '显示',
		'id' => 'picture_box',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：4',
		'id' => 'picture_s',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'picture_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用图片日志',
		'id' => 'picture',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);


	$options[] = array(
		'name' => '',
		'desc' => '输入图片日志分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'picture_id',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '图片分类ID对照',
		'desc' => '<ul>'.$catp_id.'</ul>',
		'id' => 'catids',
		'class' => 'pi-catid hidden',
		'type' => 'info'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用分类文章',
		'id' => 'picture_post',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'img_id',
		'class' => 'hidden',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '杂志两栏小工具',
		'id' => 'cms_widget_two',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：5',
		'id' => 'cms_widget_two_s',
		'std' => '5',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '主体单栏分类列表(5篇文章)',
		'desc' => '显示',
		'id' => 'cat_one_5',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'cat_one_5_s',
		'std' => '6',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '选择主体单栏分类列表分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_one_5_id',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '主体单栏分类列表(无缩略图)',
		'desc' => '显示',
		'id' => 'cat_one_on_img',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'cat_one_on_img_s',
		'std' => '6',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_one_on_img_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '选择分类列表分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_one_on_img_id',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '主体单栏分类列表(10篇文章)',
		'desc' => '显示',
		'id' => 'cat_one_10',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：7',
		'id' => 'cat_one_10_s',
		'std' => '7',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '选择主体单栏分类列表分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_one_10_id',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '视频模块',
		'desc' => '显示',
		'id' => 'video_box',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：8',
		'id' => 'video_s',
		'std' => '8',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'video_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用视频日志',
		'id' => 'video',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '选择视频日志分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'video_id',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '视频分类ID对照',
		'desc' => '<ul>'.$catv_id.'</ul>',
		'id' => 'catids',
		'class' => 'vs-catid hidden',
		'type' => 'info'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用分类文章',
		'id' => 'video_post',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'video_post_id',
		'class' => 'hidden',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '主体两栏分类列表',
		'desc' => '显示',
		'id' => 'cat_small',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：9',
		'id' => 'cat_small_s',
		'std' => '9',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '选择主体两栏分类列表分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_small_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '主体两栏分类列表篇数',
		'id' => 'cat_small_n',
		'std' => '4',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '不显示第一篇摘要',
		'id' => 'cat_small_z',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => 'Tab组合分类',
		'desc' => '显示',
		'id' => 'tab_h',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：10',
		'id' => 'tab_h_s',
		'std' => '10',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'tabt_n',
		'std' => '8',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => 'Tab“推荐文章”设置',
		'desc' => '自定义文字',
		'id' => 'tab_a',
		'std' => '推荐文章',
		'class' => 'hidden',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'tabt_id',
		'class' => 'hidden',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => 'Tab“专题文章”设置',
		'desc' => '自定义文字',
		'id' => 'tab_b',
		'class' => 'hidden',
		'std' => '专题文章',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'tabz_n',
		'class' => 'hidden',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => 'Tab“分类文章”设置',
		'desc' => '自定义文字',
		'id' => 'tab_c',
		'class' => 'hidden',
		'std' => '分类文章',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'tabp_n',
		'class' => 'hidden',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => 'Tab“分类文章”设置',
		'desc' => '自定义文字（留空则不显示）',
		'id' => 'tab_d',
		'class' => 'hidden',
		'std' => '分类文章',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'tabp_n',
		'class' => 'hidden',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '杂志侧边栏',
		'desc' => '显示',
		'id' => 'cms_no_s',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '杂志侧边栏跟随滚动',
		'id' => 'cms_slider_sticky',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '产品日志',
		'desc' => '显示',
		'id' => 'products_on',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：11',
		'id' => 'products_on_s',
		'std' => '11',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '选择产品分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'products_id',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '产品分类ID对照',
		'desc' => '<ul>'.$catpr_id.'</ul>',
		'id' => 'catids',
		'class' => 'pro-catid hidden',
		'type' => 'info'
	);

	$options[] = array(
		'name' => '',
		'desc' => '产品显示个数',
		'id' => 'products_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '特色模块',
		'desc' => '显示',
		'id' => 'grid_ico_cms',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：12',
		'id' => 'grid_ico_cms_s',
		'std' => '12',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图标无背景色',
		'id' => 'cms_ico_b',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'grid_ico_cms_n',
		'class' => 'hidden',
		'std' => '',
		'type' => 'radio',
		'options' => $grid_ico_cms_n
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '工具模块',
		'desc' => '显示',
		'id' => 'cms_tool',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：12',
		'id' => 'cms_tool_s',
		'std' => '12',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '杂志三栏小工具',
		'desc' => '显示',
		'id' => 'cms_widget_three',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：12',
		'id' => 'cat_square_s',
		'std' => '12',
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类图片',
		'desc' => '显示',
		'id' => 'cat_square',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：12',
		'id' => 'cat_square_s',
		'std' => '12',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_square_id',
		'std' => '2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_square_n',
		'std' => '6',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类网格',
		'desc' => '显示',
		'id' => 'cat_grid',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：13',
		'id' => 'cat_grid_s',
		'std' => '13',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_grid_id',
		'std' => '2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_grid_n',
		'std' => '6',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '图片滚动模块',
		'desc' => '显示',
		'id' => 'flexisel',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：14',
		'id' => 'flexisel_s',
		'std' => '14',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '自定义栏目名称',
		'desc' => '通过为文章添加自定义栏目，调用指定文章',
		'id' => 'key_n',
		'std' => 'views',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '调用图片日志',
		'id' => 'gallery_post',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '图片日志分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'gallery_id',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '图片分类ID对照',
		'desc' => '<ul>'.$catp_id.'</ul>',
		'id' => 'catids',
		'class' => 'tpv-catid hidden',
		'type' => 'info'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'flexisel_n',
		'std' => '8',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '显示底部两栏分类列表',
		'desc' => '显示',
		'id' => 'cat_big',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：15',
		'id' => 'cat_big_s',
		'std' => '15',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '三栏',
		'id' => 'cat_big_three',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_big_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_big_n',
		'std' => '4',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '不显示第一篇摘要',
		'id' => 'cat_big_z',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '淘客',
		'desc' => '显示',
		'id' => 'tao_h',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：16',
		'id' => 'tao_h_s',
		'std' => '16',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '选择淘客分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'tao_h_id',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '淘客分类ID对照',
		'desc' => '<ul>'.$catt_id.'</ul>',
		'id' => 'catids',
		'class' => 'taoc-catid hidden',
		'type' => 'info'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'tao_h_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '随机显示淘客商品',
		'id' => 'rand_tao',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	if (function_exists( 'is_shop' )) {
		$options[] = array(
			'name' => 'WOO产品',
			'desc' => '显示，需要安装商城插件 WooCommerce 并发表产品',
			'id' => 'product_h',
			'std' => '0',
			'class' => 'be_ico',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => '',
			'desc' => '排序：17',
			'id' => 'product_h_s',
			'std' => '17',
			'class' => 'mini hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '选择产品分类',
			'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
			'id' => 'product_h_id',
			'std' => '',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '产品分类ID对照',
			'desc' => '<ul>'.$catr_id.'</ul>',
			'id' => 'catids',
			'class' => 'wooc-catid hidden',
			'type' => 'info'
		);

		$options[] = array(
			'name' => '',
			'desc' => '产品商品显示数量',
			'id' => 'product_h_n',
			'std' => '4',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'id' => 'clear'
		);
	}

	if (function_exists( 'edd_get_actions' )) {
		$options[] = array(
			'name' => 'EDD下载',
			'desc' => '显示，需要安装付费下载插件 Easy Digital Downloads 并发表下载',
			'id' => 'cms_edd',
			'class' => 'be_ico',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => '',
			'desc' => '排序：18',
			'id' => 'cms_edd_s',
			'std' => '18',
			'class' => 'mini hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '下载分类',
			'desc' => '下载分类自定义文字',
			'id' => 'dow_tab_a',
			'std' => '下载分类',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '输入下载分类ID，多个分类ID用半角逗号","隔开',
			'id' => 'cms_edd_a_id',
			'std' => '',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '下载分类文字说明',
			'id' => 'dow_tab_a_s',
			'std' => '',
			'class' => 'hidden',
			'type' => 'textarea'
		);

		$options[] = array(
			'name' => '下载分类',
			'desc' => '下载分类自定义文字',
			'id' => 'dow_tab_b',
			'std' => '下载分类',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '输入下载分类ID，多个分类ID用半角逗号","隔开',
			'id' => 'cms_edd_b_id',
			'std' => '',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '下载分类文字说明',
			'id' => 'dow_tab_b_s',
			'std' => '',
			'class' => 'hidden',
			'type' => 'textarea'
		);

		$options[] = array(
			'name' => '下载分类',
			'desc' => '下载分类自定义文字',
			'id' => 'dow_tab_c',
			'std' => '下载分类',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '输入下载分类ID，多个分类ID用半角逗号","隔开',
			'id' => 'cms_edd_c_id',
			'std' => '',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '下载分类文字说明',
			'id' => 'dow_tab_c_s',
			'std' => '',
			'class' => 'hidden',
			'type' => 'textarea'
		);

		$options[] = array(
			'name' => '',
			'desc' => '下载分类文章显示数量',
			'id' => 'cms_edd_n',
			'std' => '4',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '下载分类ID对照',
			'desc' => '<ul>'.$eddcat_id.'</ul>',
			'id' => 'catids',
			'class' => 'eddc-catid hidden',
			'type' => 'info'
		);

		$options[] = array(
			'id' => 'clear'
		);
	}
	$options[] = array(
		'name' => '显示底部两栏无缩略图分类列表',
		'desc' => '显示',
		'id' => 'cat_big_not',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：19',
		'id' => 'cat_big_not_s',
		'std' => '19',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '三栏',
		'id' => 'cat_big_not_three',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'cat_big_not_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'cat_big_not_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分类列表不显示子分类',
		'id' => 'no_cat_child',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示文章列表日期',
		'id' => 'list_date',
		'std' => '1',
		'type' => 'checkbox'
	);

// 公司主页

	$options[] = array(
		'name' => '公司主页',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '幻灯',
		'desc' => '显示（注：至少添加两张幻灯片）',
		'id' => 'group_slider',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_slider_n',
		'std' => '3',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '链接到目标',
		'id' => 'group_slider_url',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示文字',
		'id' => 'group_slider_t',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端不显示文字',
		'id' => 'm_t_no',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端显示部分图片',
		'id' => 'tr_rslides_img',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);


	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '公司主页公告',
		'desc' => '显示',
		'id' => 'group_bulletin',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：1',
		'id' => 'group_bulletin_s',
		'std' => '1',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_0',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '必须输入一个公告分类ID',
		'id' => 'group_bulletin_id',
		'class' => 'hidden',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '公告分类ID对照',
		'desc' => '<ul>'.$catb_id.'</ul>',
		'class' => 'notice_id hidden',
		'id' => 'catids',
		'type' => 'info'
	);

	$options[] = array(
		'name' => '',
		'desc' => '公告滚动篇数',
		'id' => 'group_bulletin_n',
		'std' => '2',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '关于我们',
		'desc' => '显示',
		'id' => 'group_contact',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：1',
		'id' => 'group_contact_s',
		'std' => '1',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_1',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'group_contact_t',
		'std' => '关于我们',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '选择页面',
		'id' => 'contact_p',
		'type' => 'select',
		'class' => 'mini hidden',
		'options' => $options_pages
	);

	$options[] = array(
		'name' => '',
		'desc' => '"详细查看"按钮文字',
		'id' => 'group_more_z',
		'std' => '详细查看',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义"详细查看"链接地址',
		'id' => 'group_more_url',
		'class' => 'hidden',
		'placeholder' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '"联系方式"按钮文字',
		'id' => 'group_contact_z',
		'std' => '联系方式',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入"联系方式"链接地址',
		'id' => 'group_contact_url',
		'class' => 'hidden',
		'placeholder' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端截断文字',
		'id' => 'tr_contact',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '服务项目',
		'desc' => '显示',
		'id' => 'dean',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：2',
		'id' => 'dean_s',
		'std' => '2',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_2',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'dean_t',
		'std' => '服务项目',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'dean_des',
		'std' => '服务项目模块',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '调用内容方法',
		'id' => 'dean_d',
		'class' => 'hidden',
		'desc' => '编辑页面或者文章，在下面表单中输入相关内容',
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '工具',
		'desc' => '显示',
		'id' => 'group_tool',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：2',
		'id' => 'tool_s',
		'std' => '2',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_20',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'tool_t',
		'std' => '工具',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'tool_des',
		'std' => '实用工具',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '产品日志模块',
		'desc' => '显示',
		'id' => 'group_products',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：3',
		'id' => 'group_products_s',
		'std' => '3',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_3',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'group_products_t',
		'std' => '主要产品',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'group_products_des',
		'std' => '产品日志模块',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '选择产品日志分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_products_id',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '产品分类ID对照',
		'desc' => '<ul>'.$catpr_id.'</ul>',
		'id' => 'catids',
		'class' => 'gpr-catid hidden',
		'type' => 'info'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_products_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入更多按钮链接地址，留空则不显示',
		'id' => 'group_products_url',
		'placeholder' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '服务宗旨',
		'desc' => '显示',
		'id' => 'service',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：4',
		'id' => 'service_s',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_4',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'service_des',
		'std' => '服务宗旨模块',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '标题',
		'desc' => '自定义标题文字',
		'id' => 'service_t',
		'std' => '服务宗旨',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入左侧模块文章或页面ID，多个文章用英文半角逗号","隔开',
		'id' => 'service_l_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入右侧模块文章或页面ID，多个文章用英文半角逗号","隔开',
		'id' => 'service_r_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入中间模块文章或页面ID',
		'id' => 'service_c_id',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入中间模块图片地址',
		'id' => 'service_c_img',
		'std' => 'https://s2.ax1x.com/2019/05/31/VlBYFS.png',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '背景图片',
		'desc' => '上传背景图片',
		'id' => 'service_bg_img',
		'class' => 'hidden',
		 "std" => "https://s2.ax1x.com/2019/05/31/VlBWl9.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	if (function_exists( 'is_shop' )) {
		$options[] = array(
			'name' => 'WOO产品',
			'desc' => '显示，需要安装商城插件 WooCommerce 并发表产品',
			'id' => 'g_product',
			'class' => 'be_ico',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => '',
			'desc' => '排序：5',
			'id' => 'g_product_s',
			'std' => '5',
			'class' => 'mini hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '白色背景',
			'id' => 'bg_5',
			'std' => '1',
			'class' => 'hidden',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => '',
			'desc' => '自定义标题文字',
			'id' => 'g_product_t',
			'std' => 'WOO产品',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '文字说明',
			'id' => 'g_product_des',
			'std' => 'WOO产品模块',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '选择产品分类',
			'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
			'id' => 'g_product_id',
			'std' => '',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '产品分类ID对照',
			'desc' => '<ul>'.$catr_id.'</ul>',
			'id' => 'catids',
			'class' => 'grwoo-catid hidden',
			'type' => 'info'
		);

		$options[] = array(
			'name' => '',
			'desc' => '产品商品显示数量',
			'id' => 'g_product_n',
			'std' => '4',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '输入更多按钮链接地址，留空则不显示',
			'id' => 'g_product_url',
			'placeholder' => '',
			'class' => 'hidden',
			'type' => 'text'
		);

		$options[] = array(
			'id' => 'clear'
		);
	}

	$options[] = array(
		'name' => '特色',
		'desc' => '显示',
		'id' => 'group_ico',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'group_ico_s',
		'std' => '6',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_19',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'group_ico_t',
		'std' => '特色',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'group_ico_des',
		'std' => '特色模块',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图标无背景色',
		'id' => 'group_ico_b',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'grid_ico_group_n',
		'class' => 'hidden',
		'std' => '',
		'type' => 'radio',
		'options' => $grid_ico_group_n
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '描述',
		'desc' => '显示',
		'id' => 'group_post',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'group_post_s',
		'std' => '6',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_21',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入文章或页面ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_post_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '简介',
		'desc' => '显示',
		'id' => 'group_features',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：6',
		'id' => 'group_features_s',
		'std' => '6',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_6',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'features_t',
		'std' => '本站简介',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'features_des',
		'std' => '公司简介模块',
		'class' => 'hidden',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'features_id',
		'class' => 'hidden',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'features_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入更多按钮链接地址，留空则不显示',
		'id' => 'features_url',
		'placeholder' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类左图',
		'desc' => '显示',
		'id' => 'group_wd_l',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：7',
		'id' => 'group_wd_l_s',
		'std' => '7',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_7',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_wd_l_id',
		'std' => '2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_wd_l_id_n',
		'std' => '5',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类右图',
		'desc' => '显示',
		'id' => 'group_wd_r',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：8',
		'id' => 'group_wd_r_s',
		'std' => '8',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_8',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_wd_r_id',
		'std' => '2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_wd_r_id_n',
		'std' => '5',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '说明',
		'desc' => '显示',
		'id' => 'group_explain',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：9',
		'id' => 'group_explain_s',
		'std' => '9',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_9',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义标题文字',
		'id' => 'group_explain_t',
		'std' => '公司说明',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '选择简介页面',
		'id' => 'explain_p',
		'type' => 'select',
		'class' => 'mini hidden',
		'options' => $options_pages
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '一栏小工具',
		'desc' => '显示',
		'id' => 'group_widget_one',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：10',
		'id' => 'group_widget_one_s',
		'std' => '10',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_10',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '最新文章',
		'desc' => '显示',
		'id' => 'group_new',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：11',
		'id' => 'group_new_s',
		'std' => '11',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_11',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '标题',
		'desc' => '自定义标题文字',
		'id' => 'group_new_t',
		'std' => '最新文章',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'group_new_des',
		'std' => '这里是本站最新发表的文章',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_new_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入排除的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'not_group_new',
		'std' => '',
		'class' => 'hidden',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	if (function_exists( 'edd_get_actions' )) {
		$options[] = array(
			'name' => 'EDD下载',
			'desc' => '显示',
			'id' => 'group_edd',
			'class' => 'be_ico',
			'std' => '0',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => '',
			'desc' => '排序：12',
			'id' => 'group_edd_s',
			'std' => '12',
			'class' => 'mini hidden',
			'type' => 'text'
		);

		$options[] = array(
			'name' => '',
			'desc' => '白色背景',
			'id' => 'bg_12',
			'std' => '1',
			'class' => 'hidden',
			'type' => 'checkbox'
		);

		$options[] = array(
			'name' => '',
			'desc' => '请到杂志布局EDD下载模块中设置',
			'id' => 'group_edd_o',
			'class' => 'hidden'
		);

		$options[] = array(
			'id' => 'clear'
		);
	}
	$options[] = array(
		'name' => '三栏小工具',
		'desc' => '显示',
		'id' => 'group_widget_three',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：13',
		'id' => 'group_widget_three_s',
		'std' => '13',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_13',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '新闻资讯A',
		'desc' => '显示',
		'id' => 'group_cat_a',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：14',
		'id' => 'group_cat_a_s',
		'std' => '14',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_14',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_cat_a_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '第一篇调用分类推荐文章',
		'id' => 'group_cat_a_top',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_cat_a_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '两栏小工具',
		'desc' => '显示',
		'id' => 'group_widget_two',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：15',
		'id' => 'group_widget_two_s',
		'std' => '15',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_15',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '新闻资讯B',
		'desc' => '显示',
		'id' => 'group_cat_b',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：16',
		'id' => 'group_cat_b_s',
		'std' => '16',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_16',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_cat_b_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '第一篇调用分类置顶文章',
		'id' => 'group_cat_b_top',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_cat_b_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '产品案例',
		'desc' => '显示',
		'id' => 'group_tab',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：17',
		'id' => 'group_tab_s',
		'std' => '17',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_17',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '案例',
		'desc' => '自定义标题文字',
		'id' => 'anli_t',
		'std' => '客户案例',
		'class' => 'hidden',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'anli_id',
		'type' => 'select',
		'class' => 'hidden',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '产品',
		'desc' => '自定义标题文字',
		'id' => 'cp_t',
		'std' => '产品中心',
		'class' => 'hidden',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'cp_id',
		'type' => 'select',
		'class' => 'hidden',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '设备',
		'desc' => '自定义标题文字（留空则不显示）',
		'id' => 'sb_t',
		'std' => '生产设备',
		'class' => 'hidden',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'sb_id',
		'type' => 'select',
		'class' => 'hidden',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '其它',
		'desc' => '自定义标题文字（留空则不显示）',
		'id' => 'by_t',
		'std' => '其它展示',
		'class' => 'hidden',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'by_id',
		'type' => 'select',
		'class' => 'hidden',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_tab_n',
		'std' => '8',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '新闻资讯C',
		'desc' => '显示',
		'id' => 'group_cat_c',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：18',
		'id' => 'group_cat_c_s',
		'std' => '18',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '白色背景',
		'id' => 'bg_18',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_cat_c_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '第一篇显示缩略图',
		'id' => 'group_cat_c_img',
		'std' => '1',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'group_cat_c_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '热门推荐',
		'desc' => '显示',
		'id' => 'group_carousel',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '标题',
		'desc' => '自定义标题文字',
		'id' => 'group_carousel_t',
		'std' => '热门推荐',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文字说明',
		'id' => 'carousel_des',
		'std' => '文字说明文字说明文字说明文字说明文字说明文字说明',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '排序：19',
		'id' => 'group_carousel_s',
		'std' => '19',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'group_carousel_id',
		'type' => 'select',
		'class' => 'hidden',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '',
		'desc' => '调用图片日志',
		'id' => 'group_gallery',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '图片日志分类',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'group_gallery_id',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '图片分类ID对照',
		'desc' => '<ul>'.$catp_id.'</ul>',
		'id' => 'catids',
		'class' => 'grim-catid hidden',
		'type' => 'info'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'carousel_n',
		'std' => '8',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '背景图片',
		'desc' => '上传背景图片',
		'id' => 'carousel_bg_img',
		'class' => 'hidden',
		 "std" => "https://s2.ax1x.com/2019/05/31/VlBRSJ.jpg",
		'type' => 'upload'
	);

	// 首页分类图片布局

	$options[] = array(
		'name' => '分类图片',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '首页分类图片布局最新文章',
		'desc' => '显示',
		'id' => 'grid_cat_new',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_cat_news_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类滚动模块',
		'desc' => '显示',
		'id' => 'grid_carousel',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	if ( $options_categories ) {
	$options[] = array(
		'name' => '',
		'desc' => '选择一个分类',
		'id' => 'grid_carousel_id',
		'class' => 'hidden',
		'type' => 'select',
		'options' => $options_categories);
	}

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_carousel_n',
		'std' => '8',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类模块A',
		'desc' => '显示',
		'id' => 'grid_cat_a',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'grid_cat_a_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_cat_a_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示同级分类链接',
		'id' => 'grid_cat_a_child',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '杂志单栏小工具',
		'id' => 'grid_widget_one',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类模块B',
		'desc' => '显示',
		'id' => 'grid_cat_b',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'grid_cat_b_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_cat_b_n',
		'std' => '5',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '杂志两栏小工具',
		'id' => 'grid_widget_two',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '分类模块C',
		'desc' => '显示',
		'id' => 'grid_cat_c',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'grid_cat_c_id',
		'std' => '1,2',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '篇数',
		'id' => 'grid_cat_c_n',
		'std' => '4',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	// 网站标志

	$options[] = array(
		'name' => '网站标志',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '站点LOGO',
		'desc' => '勾选并上传logo',
		'id' => 'logos',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '上传Logo',
		'desc' => '透明png图片最佳，比例 220×50px',
		'id' => 'logo',
		"std" => "$blogpath/logo.png",
		'class' => 'hidden',
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '站点名称前标志',
		'desc' => '勾选并上传标志',
		'id' => 'logo_small',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '上传标志',
		'desc' => '透明png图片最佳，比例 50×50px',
		'id' => 'logo_small_b',
		"std" => "$blogpath/logo-s.png",
		'class' => 'hidden',
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '站点名称扫光动画',
		'id' => 'logo_css',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义Favicon',
		'desc' => '上传favicon.ico，并通过FTP上传到网站根目录',
		'id' => 'favicon',
		'class' => 'be_ico',
		"std" => "$blogpath/favicon.ico",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义IOS屏幕图标',
		'desc' => '上传苹果移动设备添加到主屏幕图标',
		'id' => 'apple_icon',
		'class' => 'be_ico',
		"std" => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	// 辅助功能

	$options[] = array(
		'name' => '辅助功能',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '阿里图标库',
		'desc' => '输入图标库在线链接',
		'id' => 'iconfont_url',
		'class' => 'be_ico',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '编辑器切换',
		'desc' => '使用经典编辑器',
		'id' => 'start_classic_editor',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '前端禁止加载块编辑器style和script',
		'id' => 'disable_block_styles',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => 'Gravatar 头像设置',
		'id' => 'gravatar_url',
		'class' => 'be_ico',
		'std' => 'dn',
		'type' => 'radio',
		'options' => array(
			'no' => '默认',
			'dn' => '公共库获取',
			'cn' => '官方cn获取',
			'ssl' => '官方ssl获取'
		)
	);

	$options[] = array(
		'name' => '',
		'desc' => '公共库链接 (https://fdn.geekzu.org/avatar/)',
		'id' => 'dn_avatar_url',
		'class' => '',
		'std' => 'https://fdn.geekzu.org/avatar/',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '后台禁止头像',
		'id' => 'ban_avatars',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '头像本地缓存',
		'desc' => '启用（设置wp-content/uploads/avatar目录权限755或者777）',
		'id' => 'cache_avatar',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '随机头像，未设置头像显示随机图片，多张图片链接用半角逗号","隔开',
		'desc' => '',
		'id' => 'random_avatar_url',
		'class' => 'hidden',
		'std' => 'https://s2.ax1x.com/2019/06/08/VDa45F.jpg,https://s2.ax1x.com/2019/06/08/VDafET.jpg,https://s2.ax1x.com/2019/06/08/VDaIC4.jpg,https://s2.ax1x.com/2019/06/08/VDaRbV.jpg,https://s2.ax1x.com/2019/06/08/VDa2D0.jpg,https://s2.ax1x.com/2019/06/08/VDahUU.jpg',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '上传本地头像',
		'desc' => '允许上传本地头像',
		'id' => 'local_avatars',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '允许所有角色上传头像',
		'id' => 'all_avatars',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '多条件筛选',
		'desc' => '启用',
		'id' => 'filters',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'filters_a',
		'class' => 'fia-catid hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '筛选A文字',
		'id' => 'filters_a_t',
		'class' => 'hidden',
		'std' => '风格',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'filters_b',
		'class' => 'fia-catid hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '筛选B文字',
		'id' => 'filters_b_t',
		'class' => 'hidden',
		'std' => '价格',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'filters_c',
		'class' => 'fia-catid hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '筛选C文字',
		'id' => 'filters_c_t',
		'class' => 'hidden',
		'std' => '功能',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'filters_d',
		'class' => 'fia-catid hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '筛选D文字',
		'id' => 'filters_d_t',
		'class' => 'hidden',
		'std' => '大小',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '',
		'id' => 'filters_e',
		'class' => 'fia-catid hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '筛选E文字',
		'id' => 'filters_e_t',
		'class' => 'hidden',
		'std' => '地域',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '标题文字',
		'id' => 'filter_t',
		'class' => 'hidden',
		'std' => '条 件 筛 选',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入显示筛选的分类ID，多个分类用英文半角逗号","隔开',
		'id' => 'filter_id',
		'class' => 'hidden',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '筛选条件默认隐藏',
		'id' => 'filters_hidden',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '筛选结果使用图片布局',
		'id' => 'filters_img',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '商品分类显示筛选',
		'id' => 'filters_tao',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '前台投稿',
		'desc' => '启用，新建页面并添加短代码 [fep_submission_form]',
		'id' => 'front_tougao',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '<a href="' . home_url() . '/wp-admin/admin.php?page=front_settings">进入投稿设置</a>',
		'id' => 'setup_tougao',
		'class' => 'hidden'
	);

	$options[] = array(
		'name' => '',
		'desc' => '设置→投稿设置',
		'id' => 'front_settings',
		'class' => 'hidden'
	);

	$options[] = array(
		'name' => '分类排除',
		'desc' => '输入排除的分类ID，多个分类用半角逗号","隔开',
		'id' => 'not_front_cat',
		'std' => '',
		'class' => 'hidden',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '信息提交模板',
		'desc' => '提交后发表',
		'id' => 'publish_form',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '分类排除',
		'desc' => '输入排除的分类ID，多个分类用半角逗号","隔开',
		'id' => 'form_front_cat',
		'std' => '',
		'class' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '文章浏览统计',
		'desc' => '启用（先禁用WP-PostViews插件）',
		'id' => 'post_views',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '随机计数',
		'id' => 'rand_views',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '默认 1-15 随机数',
		'id' => 'rand_mt',
		'class' => 'mini',
		'std' => '15',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '链接点击统计（短链接）',
		'id' => 'links_click',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '启用邀请码注册',
		'id' => 'invitation_code',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '启用前台英文',
		'id' => 'languages_en',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '跟随按钮设置',
		'desc' => '详细设置',
		'id' => 'follow_button',
		'class' => 'be_ico be_ico_m',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '返回首页按钮',
		'id' => 'scroll_z',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '返回顶部按钮',
		'id' => 'scroll_h',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '转到底部按钮',
		'id' => 'scroll_b',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '护眼模式',
		'id' => 'read_eye',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '夜间模式',
		'id' => 'read_night',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '跟随搜索按钮',
		'id' => 'scroll_s',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '跟随评论按钮',
		'id' => 'scroll_c',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示简繁体转换按钮',
		'id' => 'gb2',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '移动端不显示右侧小按钮',
		'id' => 'mobile_scroll',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示WordPress设置选项字段',
		'id' => 'all_settings',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁用错误处理程序',
		'id' => 'disable_error_handler',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '禁用网站健康检测',
		'id' => 'remove_sitehealth',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '头部添加“referrer”标签（仅外链新浪微相册时用）',
		'id' => 'no_referrer',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '批量删除特色图片（启用前请做好备份，用后关掉）',
		'id' => 'delete_thumbnail',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '只有临时使用文章快速编辑和定时发布时使用，防止文章选项勾选丢失',
		'id' => 'meta_delete',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '是否使用自定义分类文章',
		'desc' => '公告',
		'id' => 'no_bulletin',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '图片',
		'id' => 'no_gallery',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '视频',
		'id' => 'no_videos',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '商品',
		'id' => 'no_tao',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '网址',
		'id' => 'no_favorites',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '产品',
		'id' => 'no_products',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '什么角色显示后台自定义分类法',
		'desc' => '默认作者及投稿者以下不显示',
		'id' => 'no_type',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '修改角色，管理员10，编辑7，作者2，投稿者1',
		'id' => 'user_level',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '选择角色显示文章选项面板',
		'desc' => '管理员10，编辑7，作者2，投稿者1',
		'id' => 'boxes_level',
		'class' => 'be_ico',
		'std' => '3',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '新浪微博关注按钮',
		'desc' => '显示',
		'id' => 'weibo_t',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入新浪微博ID',
		'id' => 'weibo_id',
		'std' => '1882973105',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '关注微信公众号获取验证码',
		'desc' => '微信公众号名称',
		'id' => 'wechat_fans',
		'std' => '公众号名称',
		'class' => 'be_ico',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '微信公众号二维码图片',
		'desc' => '上传微信公众号二维码图片',
		'id' => 'wechat_qr',
		'class' => '',
		'std' => "https://s2.ax1x.com/2019/05/31/VlBLSH.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '生成当前页面二维码',
		'desc' => '启用',
		'id' => 'qr_img',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '上传二维码中间小Logo图片',
		'desc' => '',
		'id' => 'qr_icon',
		'class' => 'hidden',
        "std" => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => 'QQ在线',
		'desc' => '显示',
		'id' => 'qq_online',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义文字',
		'id' => 'qq_name',
		'std' => '在线咨询',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入QQ号码',
		'id' => 'qq_id',
		'std' => '8888',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入手机号',
		'id' => 'm_phone',
		'std' => '13688888888',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入电话号',
		'id' => 't_phone',
		'std' => '024-66666666',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '默认文字',
		'id' => 'l_phone',
		'std' => '选择一种方式联系我们！',
		'class' => 'hidden',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信二维码',
		'id' => 'weixing_qr',
        "std" => "$blogpath/favicon.png",
		'class' => 'hidden',
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '正文末尾微信二维码',
		'desc' => '显示',
		'id' => 'single_weixin',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);
	$options[] = array(
		'name' => '',
		'desc' => '只显示一个微信二维码',
		'id' => 'single_weixin_one',
		'std' => '0',
		'class' => 'hidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信文字',
		'id' => 'weixin_h',
		'std' => '我的微信',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信说明文字',
		'id' => 'weixin_h_w',
		'std' => '这是我的微信扫一扫',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传微信二维码图片（＜240px）',
		'id' => 'weixin_h_img',
		'class' => 'hidden',
		'std' => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信公众号文字',
		'id' => 'weixin_g',
		'std' => '我的微信公众号',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '微信公众号说明文字',
		'id' => 'weixin_g_w',
		'std' => '我的微信公众号扫一扫',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传微信公众号二维码图片（＜240px）',
		'id' => 'weixin_g_img',
		'class' => 'hidden',
		'std' => "$blogpath/favicon.png",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '点赞分享',
		'desc' => '显示',
		'id' => 'zm_like',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '打赏（赞助）按钮设置',
		'desc' => '自定义按钮文字，留空则不显示弹窗',
		'id' => 'alipay_name',
		'class' => 'be_ico',
		'std' => '赏',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义提示文字',
		'id' => 'alipay_t',
		'std' => '赞助本站',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义弹窗标题文字，留空则不显示',
		'id' => 'alipay_h',
		'std' => '您可以选择一种方式赞助本站',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传支付宝二维码图片（＜240px）',
		'id' => 'qr_a',
        "std" => "https://s2.ax1x.com/2019/05/31/VlBLSH.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义支付宝二维码图片文字说明，留空则不显示',
		'id' => 'alipay_z',
		'std' => '支付宝扫一扫赞助',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传微信钱包二维码图片（＜250px）',
		'id' => 'qr_b',
        "std" => "https://s2.ax1x.com/2019/05/31/VlBLSH.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '',
		'desc' => '自定义微信钱包二维码图片文字说明，留空则不显示',
		'id' => 'alipay_w',
		'std' => '微信钱包扫描赞助',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义404页面标题',
		'desc' => '',
		'id' => '404_t',
		'class' => 'be_ico',
		'std' => '亲，你迷路了！',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '自定义404页面内容',
		'desc' => '',
		'id' => '404_c',
		'class' => 'be_ico',
		'std' => '亲，该网页可能搬家了！',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '后台登录美化',
		'desc' => '启用后台登录美化',
		'id' => 'custom_login',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '上传背景图片',
		'id' => 'login_img',
        "std" => "https://s2.ax1x.com/2019/05/31/VlBX6A.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '注册页面背景图片',
		'desc' => '上传背景图片',
		'id' => 'reg_img',
		'class' => 'be_ico',
        "std" => "https://s2.ax1x.com/2019/05/31/VlBxmt.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'name' => '下载页面背景图片',
		'desc' => '上传背景图片',
		'id' => 'down_header_img',
		'class' => 'be_ico',
		"std" => "https://s2.ax1x.com/2019/05/31/VlBYFS.png",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '作者存档头部图片',
		'desc' => '上传背景图片',
		'id' => 'header_author_img',
		'class' => 'be_ico',
		"std" => "https://s2.ax1x.com/2019/05/31/VlDCtS.jpg",
		'type' => 'upload'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '评论提示文字',
		'desc' => '',
		'id' => 'comment_hint',
		'class' => 'be_ico',
		'std' => '赠人玫瑰，手留余香...',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '下载页面版权说明',
		'desc' => '',
		'id' => 'down_explain',
		'class' => 'be_ico',
		'std' => '本站大部分下载资源收集于网络，只做学习和交流使用，版权归原作者所有。若您需要使用非免费的软件或服务，请购买正版授权并合法使用。本站发布的内容若侵犯到您的权益，请联系站长删除，我们将及时处理。',
		'type' => 'textarea'
	);

	// SEO设置

	$options[] = array(
		'name' => 'SEO',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '站点SEO',
		'desc' => '启用主题自带SEO功能，如使用其它SEO插件，请取消勾选',
		'id' => 'wp_title',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示OG协议标签',
		'id' => 'og_title',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '首页描述（Description）',
		'desc' => '',
		'id' => 'description',
		'class' => 'hidden',
		'std' => '一般不超过200个字符',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '首页关键词（KeyWords）',
		'desc' => '',
		'id' => 'keyword',
		'class' => 'hidden',
		'std' => '一般不超过100个字符',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '自定义网站首页title',
		'desc' => '留空则不显示自定义title',
		'id' => 'home_title',
		'class' => 'hidden',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '自定义网站首页副标题',
		'desc' => '留空则不显示副标题',
		'id' => 'home_info',
		'class' => 'hidden',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '',
		'desc' => '首页显示站点副标题',
		'id' => 'blog_info',
		'class' => 'hidden',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '文章title无网站名称',
		'id' => 'blog_name',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '修改站点分隔符',
		'id' => 'connector',
		'std' => '|',
		'class' => 'mini hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '分隔符无空格',
		'id' => 'blank_connector',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '',
		'desc' => '更新文章时生成站点地图xml，查看 <a href="' . home_url() . '/sitemap.xml" target="_blank">sitemap.xml</a>',
		'id' => 'sitemap_xml',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '关键词',
		'desc' => '启用',
		'id' => 'keyword_link',
		'class' => 'be_ico',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '<a href="' . home_url() . '/wp-admin/options-general.php?page=keywordlink">添加关键词</a>',
		'id' => 'keyword_link_settings',
		'class' => 'hidden'
	);

	$options[] = array(
		'name' => '',
		'desc' => '设置→关键词',
		'id' => 'front_settings',
		'class' => 'hidden'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '百度熊掌号',
		'desc' => '启用',
		'id' => 'baidu_xzh',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '熊掌号ID',
		'id' => 'xzh_id',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '准入密钥',
		'id' => 'xzh_token',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '显示关注按钮',
		'id' => 'xzh_gz',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '将文章主动推送到百度',
		'desc' => '启用',
		'id' => 'baidu_submit',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '输入百度主动推送token值',
		'id' => 'token_p',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义分类法固定链接',
		'desc' => '启用并选择',
		'id' => 'begin_types_link',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'id' => 'begin_types',
		'std' => 'link_id',
		'type' => 'radio',
		'options' => array(
			'link_id' => '文章ID.html',
			'link_name' => '文章名称.html',
		)
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义图片固定链接前缀',
		'desc' => '“图片”固定链接前缀',
		'id' => 'img_url',
		'class' => 'be_ico',
		'std' => 'picture',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“图片分类”固定链接前缀',
		'id' => 'img_cat_url',
		'std' => 'gallery',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义视频固定链接前缀',
		'desc' => '“视频”固定链接前缀',
		'id' => 'video_url',
		'class' => 'be_ico',
		'std' => 'video',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“视频分类”固定链接前缀',
		'id' => 'video_cat_url',
		'std' => 'videos',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义商品固定链接前缀',
		'desc' => '“商品”固定链接前缀',
		'id' => 'sp_url',
		'class' => 'be_ico',
		'std' => 'tao',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“商品分类”固定链接前缀',
		'id' => 'sp_cat_url',
		'std' => 'taobao',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义网址固定链接前缀',
		'desc' => '“网址”固定链接前缀',
		'id' => 'favorites_url',
		'class' => 'be_ico',
		'std' => 'sites',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '“网址分类”固定链接前缀',
		'id' => 'favorites_cat_url',
		'std' => 'favorites',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '自定义产品固定链接前缀',
		'desc' => '“产品”固定链接前缀',
		'id' => 'show_url',
		'class' => 'be_ico',
		'std' => 'show',
		'type' => 'text'
	);


	$options[] = array(
		'name' => '',
		'desc' => '“产品分类”固定链接前缀',
		'id' => 'show_cat_url',
		'std' => 'products',
		'type' => 'text'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '流量统计代码（异步）',
		'desc' => '用于在页头添加异步统计代码',
		'id' => 'tongji_h',
		'class' => 'be_ico',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '流量统计代码（同步）',
		'desc' => '用于在页脚添加同步统计代码',
		'id' => 'tongji_f',
		'class' => 'be_ico',
		'std' => '',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$wp_editor_settings = array(
		'quicktags' => 1,
		'tinymce' => 1,
		'media_buttons' => 1,
		'textarea_rows' => 5
	);

	$options[] = array(
		'name' => '页脚第一行信息',
		'desc' => '',
		'id' => 'footer_inf_t',
		'class' => 'be_ico',
		'std' => 'Copyright &copy;&nbsp;&nbsp;站点名称&nbsp;&nbsp;版权所有.',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'name' => '页脚第二行信息',
		'desc' => '',
		'id' => 'footer_inf_b',
		'class' => 'be_ico',
		'std' => '<a title="主题设计：知更鸟" href="http://zmingcx.com/" target="_blank"><img src="' . get_template_directory_uri() . '/img/logo.png" alt="Begin主题" width="120" height="27" /></a>',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);

	$options[] = array(
		'name' => '域名备案和公网安备',
		'desc' => '公网安备小图标',
		'id' => 'wb_img',
		'class' => 'be_ico',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '公网安备信息',
		'id' => 'wb_info',
		'std' => '',
		'type' => 'text'
	);


	$options[] = array(
		'name' => '',
		'desc' => '公网安备信息链接',
		'id' => 'wb_url',
		'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'name' => '',
		'desc' => '域名备案信息',
		'id' => 'yb_info',
		'std' => '',
		'type' => 'text'
	);



	// 广告设置

	$options[] = array(
		'name' => '广告位',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '头部通栏广告位',
		'desc' => '显示',
		'id' => 'ad_h_t',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '只在首页显示',
		'id' => 'ad_h_t_h',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入头部通栏广告代码（非移动端）',
		'desc' => '宽度小于等于 1080px',
		'id' => 'ad_ht_c',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入头部通栏广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_ht_m',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '头部两栏广告位',
		'desc' => '显示',
		'id' => 'ad_h',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '',
		'desc' => '只在首页显示',
		'id' => 'ad_h_h',
		'class' => 'hidden',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入头部左侧广告代码（非移动端）',
		'desc' => '宽度小于等于 758px',
		'id' => 'ad_h_c',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入头部左侧广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_h_c_m',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入头部右侧广告代码（非移动端）',
		'desc' => '宽度小于等于 307px',
		'id' => 'ad_h_cr',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oYfP.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '文章列表广告位',
		'desc' => '显示',
		'id' => 'ad_a',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入文章列表广告代码（非移动端）',
		'desc' => '宽度小于等于 760px',
		'id' => 'ad_a_c',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入文章列表广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_a_c_m',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '正文标题广告位',
		'desc' => '显示',
		'id' => 'ad_s',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入正文标题广告代码（非移动端）',
		'desc' => '宽度小于等于 740px',
		'id' => 'ad_s_c',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入正文标题广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_s_c_m',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '正文底部广告位',
		'desc' => '显示',
		'id' => 'ad_s_b',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入正文底部广告代码（非移动端）',
		'desc' => '宽度小于等于 740px',
		'id' => 'ad_s_c_b',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入正文底部广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_s_c_b_m',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '正文短代码广告位',
		'class' => 'be_ico',
	);

	$options[] = array(
		'name' => '输入正文短代码广告代码（非移动端）',
		'desc' => '宽度小于等于 740px',
		'id' => 'ad_s_z',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入正文短代码广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_s_z_m',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '评论上方广告位',
		'desc' => '显示',
		'id' => 'ad_c',
		'class' => 'be_ico',
		'std' => '0',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => '输入评论上方广告代码（非移动端）',
		'desc' => '宽度小于等于 760px',
		'id' => 'ad_c_c',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => '输入评论上方广告代码（用于移动端）',
		'desc' => '',
		'id' => 'ad_c_c_m',
		'class' => 'hidden',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '文件下载页面广告代码',
		'desc' => '',
		'id' => 'ad_down',
		'class' => 'be_ico',
		'std' => '<a href="#" target="_blank"><img src="https://s2.ax1x.com/2019/06/12/V2oNSf.jpg" alt="广告也精彩" /></a>',
		'type' => 'textarea'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '需要在页头<head></head>之间加载的广告代码',
		'desc' => '',
		'id' => 'ad_t',
		'class' => 'be_ico',
		'std' => '',
		'type' => 'textarea'
	);

	// 定制CSS

	$options[] = array(
		'name' => '定制风格',
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '页面宽度',
		'desc' => '固定宽度（推荐）',
		'id' => 'custom_width',
		'class' => 'be_ico',
		 'std' => '',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => '',
		'desc' => '按百分比',
		'id' => 'adapt_width',
		 'std' => '',
		'type' => 'text'
	);

	$options[] = array(
		'desc' => '不使用自定义宽度请留空！'
	);


	$options[] = array(
		'id' => 'clear'
	);

    $options[] = array(
		'name' => '颜色风格',
		'desc' => '选择自己喜欢的颜色，不使用自定义颜色清空即可',
		'id' => 'custom_color',
		'class' => 'be_ico'
	);

    $options[] = array(
		'name' => '',
		'desc' => '站点标题',
		'id' => 'blogname_color',
        'std' => '',
		'type' => 'color'
	);

    $options[] = array(
		'name' => '',
		'desc' => '副标题',
		'id' => 'blogdescription_color',
        'std' => '',
		'type' => 'color'
	);

    $options[] = array(
		'name' => '',
		'desc' => '超链接',
		'id' => 'link_color',
        'std' => '',
		'type' => 'color'
	);

    $options[] = array(
		'name' => '',
		'desc' => '菜单链接',
		'id' => 'menu_color',
		'std' => '',
		'type' => 'color'
	);

    $options[] = array(
		'name' => '',
		'desc' => '按钮',
		'id' => 'button_color',
		'std' => '',
		'type' => 'color'
	);

    $options[] = array(
		'name' => '',
		'desc' => '分类名称',
		'id' => 'cat_color',
		'std' => '',
		'type' => 'color'
	);

    $options[] = array(
		'name' => '',
		'desc' => '幻灯',
		'id' => 'slider_color',
		'std' => '',
		'type' => 'color'
	);

    $options[] = array(
		'name' => '',
		'desc' => '正文H标签',
		'id' => 'h_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'id' => 'clear'
	);

	$options[] = array(
		'name' => '参考颜色值',
		'desc' => '#56bbdc #32c5d2 #4cb6cb #2f889a #6491bb #cc0000 #ff4400 #e84266 #ff9966'
	);

	$options[] = array(
		'id' => 'clear'
	);

    $options[] = array(
		'name' => '自定义样式',
		'desc' => '',
		'id' => 'custom_css',
		'class' => 'be_ico',
		 'std' => '',
		'type' => 'textarea'
	);

    $options[] = array(
		'name' => '比如将顶部改为渐变背景色，输入以下代码：',
		'desc' => '#header-top {background: linear-gradient(to right, #ff7002, #ffff00, #ff7002, #ffff00, #ff7002);}'
	);

	return $options;
}