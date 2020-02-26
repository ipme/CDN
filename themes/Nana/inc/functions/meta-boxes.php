<?php
ini_set('display_errors', false);
// 文章相关自定义栏目
$new_meta_boxes =
array(
	"wzshow" => array(
		"name" => "wzshow",
		"std" => "",
		"title" => "指定缩略图地址（留空，则以文章第一张图片为缩略图，如无图片则显示随机图片）",
		"type"=>"text"),

	"description" => array(
		"name" => "description",
		"std" => "",
		"title" => "SEO文章描述（留空，则自动截取首段一定字数作为文章描述）",
		"type"=>"textarea"),

	"keywords" => array(
		"name" => "keywords",
		"std" => "",
		"title" => "SEO文章关键词，多个关键词用半角逗号隔开（留空，则以文章标签TAG为关键词）",
		"type"=>"text"),

	"hot" => array(
		"name" => "hot",
		"std" => "",
		"title" => "推送到『站长推荐』小工具",
		"type"=>"checkbox"),

	"zzwz" => array(
		"name" => "zzwz",
		"std" => "",
		"title" => "转载文章",
		"type"=>"checkbox"),

	"tgwz" => array(
		"name" => "tgwz",
		"std" => "",
		"title" => "投稿文章",
		"type"=>"checkbox"),

	"wzzz" => array(
		"name" => "wzzz",
		"std" => "",
		"title" => "原文作者",
		"type"=>"text"),

	"wzurl" => array(
		"name" => "wzurl",
		"std" => "",
		"title" => "原文链接地址",
		"type"=>"text"),
);

// 面板内容
function new_meta_boxes() {
    global $post, $new_meta_boxes;
	//获取保存
    foreach($new_meta_boxes as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'].'', true);

        if($meta_box_value != "")
        	//将默认值替换为已保存的值
            $meta_box['std'] = $meta_box_value;

        echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';

        //选择类型输出不同的html代码
        switch ( $meta_box['type'] ){
            case 'title':
                echo'<h4>'.$meta_box['title'].'</h4>';
                break;
			case 'text':
                echo'<h4>'.$meta_box['title'].'</h4>';
                echo '<span class="form-field"><input type="text" size="40" name="'.$meta_box['name'].'" value="'.$meta_box['std'].'" /></span><br />';
                break;
            case 'textarea':
                echo'<h4>'.$meta_box['title'].'</h4>';
                echo '<textarea cols="60" rows="3" name="'.$meta_box['name'].'">'.$meta_box['std'].'</textarea><br />';
                break;
            case 'radio':
                echo'<h4>'.$meta_box['title'].'</h4>';
                $counter = 1;
                foreach( $meta_box['buttons'] as $radiobutton ) {
                    $checked ="";
                    if(isset($meta_box['std']) && $meta_box['std'] == $counter) {
                        $checked = 'checked = "checked"';
                    }
                    echo '<input '.$checked.' type="radio" class="kcheck" value="'.$counter.'" name="'.$meta_box['name'].'_value"/>'.$radiobutton;
                    $counter++;
                }
				break;
            case 'checkbox':
                if( isset($meta_box['std']) && $meta_box['std'] == 'true' )
                    $checked = 'checked = "checked"';
                else
                    $checked  = '';
                echo '<br /><input type="checkbox" name="'.$meta_box['name'].'" value="true"  '.$checked.' />';
                echo'<label>'.$meta_box['title'].'</label><br />';
				break;
        }
    }
}

// 创建面板
function create_meta_box() {
    global $theme_name;

    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'new-meta-boxes', '文章相关设置', 'new_meta_boxes', 'post', 'normal', 'high' );
    }
}

// 保存数据
function save_postdata( $post_id ) {
    global $post, $new_meta_boxes;

    foreach($new_meta_boxes as $meta_box) {
        if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) ))  {
            return $post_id;
        }

        if ( 'page' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ))
                return $post_id;
        }
        else {
            if ( !current_user_can( 'edit_post', $post_id ))
                return $post_id;
        }

        $data = $_POST[$meta_box['name'].''];

        if(get_post_meta($post_id, $meta_box['name'].'') == "")
            add_post_meta($post_id, $meta_box['name'].'', $data, true);
        elseif($data != get_post_meta($post_id, $meta_box['name'].'', true))
            update_post_meta($post_id, $meta_box['name'].'', $data);
        elseif($data == "")
            delete_post_meta($post_id, $meta_box['name'].'', get_post_meta($post_id, $meta_box['name'].'', true));
    }
}

// 触发
add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_postdata'); 

// 页面相关自定义栏目
$new_meta_page_boxes =
array(
	"description" => array(
		"name" => "description",
		"std" => "",
		"title" => "文章描述（留空，则自动截取首段一定字数作为文章描述）",
		"type"=>"textarea"),

	"keywords" => array(
		"name" => "keywords",
		"std" => "",
		"title" => "文章关键词，多个关键词用半角逗号隔开（留空，则以文章标签TAG为关键词）",
		"type"=>"text"),
		
	"zttagid" => array(
		"name" => "zttagid",
		"std" => "",
		"title" => "tag标签专题（填写相应标签ID，选择专题页面模板）",
		"type"=>"text")
);

// 面板内容
function new_meta_page_boxes() {
    global $post, $new_meta_page_boxes;
	//获取保存
    foreach($new_meta_page_boxes as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'].'', true);

        if($meta_box_value != "")
        	//将默认值替换为已保存的值
            $meta_box['std'] = $meta_box_value;

        echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';

        //选择类型输出不同的html代码
        switch ( $meta_box['type'] ){
            case 'title':
                echo'<h4>'.$meta_box['title'].'</h4>';
                break;
			case 'text':
                echo'<h4>'.$meta_box['title'].'</h4>';
                echo '<span class="form-field"><input type="text" size="40" name="'.$meta_box['name'].'" value="'.$meta_box['std'].'" /></span><br />';
                break;
            case 'textarea':
                echo'<h4>'.$meta_box['title'].'</h4>';
                echo '<textarea cols="60" rows="3" name="'.$meta_box['name'].'">'.$meta_box['std'].'</textarea><br />';
                break;
            case 'radio':
                echo'<h4>'.$meta_box['title'].'</h4>';
                $counter = 1;
                foreach( $meta_box['buttons'] as $radiobutton ) {
                    $checked ="";
                    if(isset($meta_box['std']) && $meta_box['std'] == $counter) {
                        $checked = 'checked = "checked"';
                    }
                    echo '<input '.$checked.' type="radio" class="kcheck" value="'.$counter.'" name="'.$meta_box['name'].'_value"/>'.$radiobutton;
                    $counter++;
                }
				break;
            case 'checkbox':
                if( isset($meta_box['std']) && $meta_box['std'] == 'true' )
                    $checked = 'checked = "checked"';
                else
                    $checked  = '';
                echo '<br /><input type="checkbox" name="'.$meta_box['name'].'" value="true"  '.$checked.' />';
                echo'<label>'.$meta_box['title'].'</label><br />';
				break;
        }
    }
}

function create_meta_page_box() {
    global $theme_name;

    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'new-meta-boxes', '页面SEO设置', 'new_meta_page_boxes', 'page', 'normal', 'high' );
    }
}

function save_page_postdata( $post_id ) {
    global $post, $new_meta_page_boxes;

    foreach($new_meta_page_boxes as $meta_box) {
        if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) ))  {
            return $post_id;
        }

        if ( 'page' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ))
                return $post_id;
        }
        else {
            if ( !current_user_can( 'edit_post', $post_id ))
                return $post_id;
        }

        $data = $_POST[$meta_box['name'].''];

        if(get_post_meta($post_id, $meta_box['name'].'') == "")
            add_post_meta($post_id, $meta_box['name'].'', $data, true);
        elseif($data != get_post_meta($post_id, $meta_box['name'].'', true))
            update_post_meta($post_id, $meta_box['name'].'', $data);
        elseif($data == "")
            delete_post_meta($post_id, $meta_box['name'].'', get_post_meta($post_id, $meta_box['name'].'', true));
    }
}

add_action('admin_menu', 'create_meta_page_box');
add_action('save_post', 'save_page_postdata'); 