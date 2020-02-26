<?php
// 小工具
if (function_exists('register_sidebar')){
	register_sidebar( array(
		'name'          => '首页侧边栏',
		'id'            => 'sidebar-1',
		'description'   => '显示在首页及分类归档页侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="cat">',
		'after_title'   => '</span></h3>',
	) );
	register_sidebar( array(
		'name'          => '正文侧边栏',
		'id'            => 'sidebar-2',
		'description'   => '显示在正文和页面侧边栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="cat">',
		'after_title'   => '</span></h3>',
	) );	
}

// 自定义菜单
register_nav_menus(
   array(
      'top-menu' => __( '右上角菜单' , 'Nana'),
      'header-menu' => __( '导航主菜单' , 'Nana'),
      'mini-menu' => __( '移动版菜单', 'Nana' )
   )
);

// 去掉描述P标签
function deletehtml($description) {
	$description = trim($description);
	$description = strip_tags($description,"");
	return ($description);
}
add_filter('category_description', 'deletehtml');
//标题文字截断
function cut_str($src_str,$cut_length)
{
    $return_str='';
    $i=0;
    $n=0;
    $str_length=strlen($src_str);
    while (($n<$cut_length) && ($i<=$str_length))
    {
        $tmp_str=substr($src_str,$i,1);
        $ascnum=ord($tmp_str);
        if ($ascnum>=224)
        {
            $return_str=$return_str.substr($src_str,$i,3);
            $i=$i+3;
            $n=$n+2;
        }
        elseif ($ascnum>=192)
        {
            $return_str=$return_str.substr($src_str,$i,2);
            $i=$i+2;
            $n=$n+2;
        }
        elseif ($ascnum>=65 && $ascnum<=90)
        {
            $return_str=$return_str.substr($src_str,$i,1);
            $i=$i+1;
            $n=$n+2;
        }
        else 
        {
            $return_str=$return_str.substr($src_str,$i,1);
            $i=$i+1;
            $n=$n+1;
        }
    }
    if ($i<$str_length)
    {
        $return_str = $return_str . '';
    }
    if (get_post_status() == 'private')
    {
        $return_str = $return_str . '（private）';
    }
    return $return_str;
}

//禁用工具条
show_admin_bar(false);
//禁止代码标点转换
remove_filter('the_content', 'wptexturize');

// 移除头部冗余代码
remove_action( 'wp_head', 'wp_generator' );// WP版本信息
remove_action( 'wp_head', 'rsd_link' );// 离线编辑器接口
remove_action( 'wp_head', 'wlwmanifest_link' );// 同上
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );// 上下文章的url
remove_action( 'wp_head', 'feed_links', 2 );// 文章和评论feed
remove_action( 'wp_head', 'feed_links_extra', 3 );// 去除评论feed
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );// 短链接

// 下载按钮
function button_a($atts, $content = null) {
return '<div id="down"><a id="load" title="下载链接" href="#button_file" rel="nofollow"><i class="fa fa-download"></i>&nbsp;下载地址</a><div class="clear"></div></div>';
}
add_shortcode("file", "button_a");

// 编辑器增强
 function enable_more_buttons($buttons) {
	$buttons[] = 'hr';
	$buttons[] = 'del';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page';
	$buttons[] = 'anchor';
	$buttons[] = 'backcolor';
	return $buttons;
}
add_filter( "mce_buttons_3", "enable_more_buttons" );

// 添加按钮
add_action('after_wp_tiny_mce', 'bolo_after_wp_tiny_mce');
function bolo_after_wp_tiny_mce($mce_settings) {
?>
<script type="text/javascript">
QTags.addButton( 'ygjtimes', '时光轴', "<div id='teamnewslist'><ol><li><b>日期(如20161205)</b>内容(多个内容请重复li之间的内容)</li></ol></div>" );
</script>
<?php }

// 添加下拉式按钮
function Nana_select(){
echo '
<select id="sc_select">
	<option value="您需要选择一个短代码">插入短代码</option>
	<option value="[file]">默认下载（弹窗）</option>
	<option value="[button]按钮名称[/button]">自定义下载（弹窗）</option>
	<option value="[url href=\'链接地址\']按钮名称[/url]">链接按钮</option>
	<option value="[lxtx_fa_insert_post ids=id1,id2]">插入站内文章</option>
	<option value="[collapse title=\'隐藏内容标题\']隐藏的内容，替换即可[/collapse]">隐藏收缩</option>
	<option value="<fieldset><legend>温馨提示</legend>提示内容</fieldset>">温馨提示</option>
	<option value="[netmusic id=\'网易云音乐ID\']">网易云音乐</option>
	<option value="[reply]评论可见的内容[/reply]">评论可见</option>
	<option value="[secret key=\'123456\']输入密码可见内容[/secret]">密码可见</option>
	<option value="[imgs]连续插入N张图片[/imgs]">图片相册</option>
	<option value="[abcd]">插入广告</option>
	<option value="[videos href=\'插入视频分享通用代码中的视频src源地址\']插入视频图片地址[/videos]">添加视频</option>
	<option value="[v_notice]绿色提示框里的内容[/v_notice]">绿色提示框</option>
	<option value="[v_error]红色提示框里的内容[/v_error]">红色提示框</option>
	<option value="[v_warn]黄色提示框里的内容[/v_warn]">黄色提示框</option>
	<option value="[v_tips]灰色提示框里的内容[/v_tips]">灰色提示框</option>
	<option value="[v_blue]蓝色提示框里的内容[/v_blue]">蓝色提示框</option>
	<option value="[v_black]黑色提示框里的内容[/v_black]">黑色提示框</option>
	<option value="[v_xuk]虚线提示框里的内容[/v_xuk]">虚线提示框</option>
	<option value="[v_lvb]绿边提示框里的内容[/v_lvb]">绿边提示框</option>
	<option value="[v_redb]红边提示框里的内容[/v_redb]">红边提示框</option>
	<option value="[v_orange]橙边提示框里的内容[/v_orange]">橙边提示框</option>
</select>';
}
if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
	add_action('media_buttons', 'Nana_select', 11);
}
function Nana_button() {
echo '<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#sc_select").change(function() {
	send_to_editor(jQuery("#sc_select :selected").val());
	return false;
	});
});
</script>';
}
add_action('admin_head', 'Nana_button');

// 短代码广告
function post_ad(){
if ( wp_is_mobile() ) {
		return '<div class="abc-pc abc-site">'.stripslashes( get_option('ygj_ddm_ad_m') ).'</div>';
	} else {
		return '<div class="abc-pc abc-site">'.stripslashes( get_option('ygj_ddm_ad') ).'</div>';
	}
}
add_shortcode('abcd', 'post_ad');

// 短代码信息框
function toz($atts, $content=null){
    return '<div id="sc_notice">'.$content.'</div>';
}
add_shortcode('v_notice','toz');
function toa($atts, $content=null){
    return '<div id="sc_error">'.$content.'</div>';
}
add_shortcode('v_error','toa');
function toc($atts, $content=null){
    return '<div id="sc_warn">'.$content.'</div>';
}
add_shortcode('v_warn','toc');
function tob($atts, $content=null){
    return '<div id="sc_tips">'.$content.'</div>';
}
add_shortcode('v_tips','tob');
function tod($atts, $content=null){
    return '<div id="sc_blue">'.$content.'</div>';
}
add_shortcode('v_blue','tod');
function toe($atts, $content=null){
    return '<div id="sc_black">'.$content.'</div>';
}
add_shortcode('v_black','toe');
function tof($atts, $content=null){
    return '<div id="sc_xuk">'.$content.'</div>';
}
add_shortcode('v_xuk','tof');
function tog($atts, $content=null){
    return '<div id="sc_lvb">'.$content.'</div>';
}
add_shortcode('v_lvb','tog');
function toh($atts, $content=null){
    return '<div id="sc_redb">'.$content.'</div>';
}
add_shortcode('v_redb','toh');
function toi($atts, $content=null){
    return '<div id="sc_orange">'.$content.'</div>';
}
add_shortcode('v_orange','toi');

// 链接按钮
function button_url($atts,$content=null){
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	return '<div id="down"><a href="'.$href.'" rel="external nofollow" target="_blank"><i class="fa fa-rocket"></i>&nbsp;'.$content.'</a><div class="clear"></div></div>';
}
add_shortcode('url', 'button_url');

// 添加视频
function my_videos( $atts, $content = null ) {
	extract( shortcode_atts( array (
		'href' => '',
		 'img' => '<img class="aligncenter" src="'.$content.'">'
	), $atts ) );
	return '<div class="video-content"><a data-fancybox data-type="iframe" class="videos" href="'.$href.'" title="播放视频">'.$img.'<i class="play"></i></a></div>';
}
add_shortcode('videos', 'my_videos');

// 自定义按钮
function button_b($atts, $content = null) {
	return '<div id="down"><a id="load" title="下载链接" href="#button_file" rel="nofollow"><i class="fa fa-download"></i>&nbsp;'.$content.'</a><div class="clear"></div></div>';
}
add_shortcode('button', 'button_b');

// 幻灯
function gallery($atts, $content=null){
	return '<div id="gallery"><div class="rslides" id="slide">'.$content.'</div><p class="wp-caption-text">共<span class="myimg"></span></p></div>
	<script type="text/javascript" src="'.get_template_directory_uri().'/js/slides.js"></script>';
}
add_shortcode('imgs', 'gallery');

// 所有图片
function all_img($soContent){
	$soImages = '~<img [^\>]*\ />~';
	preg_match_all( $soImages, $soContent, $thePics );
	$allPics = count($thePics);
	if( $allPics > 0 ){ 
		$count=0;
			foreach($thePics[0] as $v){
				 if( $count == 4 ){break;}
				 else {echo $v;}
				$count++;
			}
	}
}

//留言信息
function WelcomeCommentAuthorBack($email = ''){
	if(empty($email)){
		return;
	}
	global $wpdb;

	$past_30days = gmdate('Y-m-d H:i:s',((time()-(24*60*60*30))+(get_option('gmt_offset')*3600)));
	$sql = "SELECT count(comment_author_email) AS times FROM $wpdb->comments
					WHERE comment_approved = '1'
					AND comment_author_email = '$email'
					AND comment_date >= '$past_30days'";
	$times = $wpdb->get_results($sql);
	$times = ($times[0]->times) ? $times[0]->times : 0;
	$message = $times ? sprintf(__('过去30天内您有<strong>%1$s</strong>条留言，感谢关注!', 'Nana' ), $times) : '您已很久都没有留言了，这次说点什么吧？';
	return $message;
}


// 评论链接新窗口
function commentauthor($comment_ID = 0) {
	if(get_option('ygj_plwlgonof')&& get_option('ygj_wlgonof'))//{
	//$url = links_nofollow(get_comment_author_url( $comment_ID ));
	//}else{
    $url = get_comment_author_url( $comment_ID );
	//}
    $author = get_comment_author( $comment_ID );
    if ( empty( $url ) || 'http://' == $url ) {
    echo $author;
    } else {
	             if (!preg_match(home_url(),$url)) {
	   echo "<a href='".home_url()."/go/".base64_encode($url)."' rel='nofollow' target='_blank' class='url'>$author</a>";
	   } else {
		   echo "&lt;a href='$url' target='_blank' class='url'&gt;$author&lt;/a&gt;";
	     }
	   }
			     
}

// 禁止评论自动超链接
remove_filter('comment_text', 'make_clickable', 9);

// 禁止无中文留言
if ( is_user_logged_in() ) {
} else {
function refused_spam_comments( $comment_data ) {
	$pattern = '/[一-龥]/u'; 
	$jpattern = '/[ぁ-ん]+|[ァ-ヴ]+/u';	
	if(!preg_match($pattern,$comment_data['comment_content'])) {
		err('这里是中国，评论内容必须含有中文！');
	}
	if (preg_match($jpattern, $comment_data['comment_content'])) {
        err(__('这里是中国，不能用日文进行评论！', 'Nana'));
    }
	return( $comment_data );
}
add_filter('preprocess_comment','refused_spam_comments');
}
//屏蔽关键词，email，url，ip
function Shield_fuckspam($comment) {
    if (wp_blacklist_check($comment['comment_author'], $comment['comment_author_email'], $comment['comment_author_url'], $comment['comment_content'], $comment['comment_author_IP'], $comment['comment_agent'])) {
        header("Content-type: text/html; charset=utf-8");
        err(__('不好意思，您的评论违反了本站评论规则！', 'Nana'));
    } else {
        return $comment;
    }
}
add_filter('preprocess_comment', 'Shield_fuckspam');
// 禁止后台加载谷歌字体
function wp_remove_open_sans_from_wp_core() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'wp_remove_open_sans_from_wp_core' );

//屏蔽默认小工具
function my_unregister_widgets() {
//近期评论
	unregister_widget( 'WP_Widget_Recent_Comments' );
//近期文章
	unregister_widget( 'WP_Widget_Recent_Posts' );
}
add_action( 'widgets_init', 'my_unregister_widgets' );

// 主题设置
require get_template_directory() . '/inc/theme-options.php';
// 主题小工具
require get_template_directory() . '/inc/functions/widgets.php';
// 热门文章
require get_template_directory() . '/inc/functions/hot-post.php';
// 热评文章
require get_template_directory() . '/inc/functions/hot_comment.php';
// 分类和标签SEO标题和关键词
require get_template_directory() . '/inc/functions/custom-field.php';
// 分页
require get_template_directory() . '/inc/functions/pagenavi.php';
// 图片属性
require get_template_directory() . '/inc/functions/addclass.php';
// 面包屑导航
require get_template_directory() . '/inc/functions/breadcrumb.php';
// 评论模板
require get_template_directory() . '/inc/functions/comment-template.php';
// 评论通知
require get_template_directory() . '/inc/functions/notify.php';
// 自动缩略图
require get_template_directory() . '/inc/functions/thumbnail.php';
// 自定义栏目
require get_template_directory() . '/inc/functions/meta-boxes.php';
// 字母头像
require get_template_directory() . '/inc/functions/zimutouxiang.php';
// 友情链接
add_filter( 'pre_option_link_manager_enabled', '__return_true' );
// 加载前端脚本及样式
function nana_scripts() {
		wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', array(), '1.0', false );
	if ( is_singular() ) {
		wp_localize_script( 'script', 'wpl_ajax_url', admin_url() . "admin-ajax.php");
		wp_enqueue_style( 'highlight', get_template_directory_uri() . '/images/highlight.css', array(), '1.0');
		wp_enqueue_script( 'jquery.fancybox', get_template_directory_uri() . '/js/fancybox.min.js', array(), '2.0', false);
		wp_enqueue_style( 'fancybox', get_template_directory_uri() . '/fancybox.min.css', array(), '1.0');
        wp_enqueue_script( 'comments-ajax', get_template_directory_uri() . '/js/comments-ajax.js', array(), '1.5', false);
	}
}
add_action( 'wp_enqueue_scripts', 'nana_scripts' );

//fancybox3图片灯箱效果
add_filter('the_content', 'fancybox');
function fancybox ($content){ 
	global $post;
	$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>(.*?)<\/a>/i";
	$replacement = '<a$1href=$2$3.$4$5 data-fancybox="images"$6>$7</a>';
	$content = preg_replace($pattern, $replacement, $content);
	return $content;
}

// 自动缩略图
function catch_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = isset($matches [1] [0])?$matches [1] [0]:'';
  if(empty($first_img)){ //Defines a default image
		$random = mt_rand(1, 10);
		echo get_bloginfo ( 'stylesheet_directory' );
		echo '/images/random/'.$random.'.jpg';
  }
  return $first_img;
}

//avatar头像缓存
function my_avatar( $email = 'www@atzzz.com', $size = '40', $default = '', $alt = '') {
 $f = md5( strtolower($email) ); 
  $a = get_template_directory_uri() . '/static/avatar/'. $f . $size . '.png';
  $e = get_template_directory() . '/static/avatar/' . $f . $size . '.png';
  $d = get_template_directory() . '/static/avatar/' . $f . '-d.png';
  $txdf = tt_touxiang_generate_first_letter_uri($alt);
  if($default=='')
    $default = $txdf;
  $t = 2592000; // 缓存有效期30天, 这里单位:秒
  if ( !is_file($e) || (time() - filemtime($e)) > $t ) {
    if ( !is_file($d) || (time() - filemtime($d)) > $t ) {
      // 验证是否有头像
      $uri = 'https://cn.gravatar.com/avatar/' . $f . '?d=404';
      $headers = @get_headers($uri);
      if (!preg_match("|200|", $headers[0])) {
        // 没有头像，则新建一个空白文件作为标记
        $handle = fopen($d, 'w');
        fclose($handle);
        $a = $default;
      }
      else {
        // 有头像且不存在则更新
        $r = get_option('avatar_rating');
        $g = 'https://cn.gravatar.com/avatar/'. $f. '?s='. $size. '&r=' . $r;
        copy($g, $e);
      }
    }
    else {
      $a = $default;
    }
  } 
  $avatar = "<img alt='{$alt}' src='{$a}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
  return apply_filters('my_avatar', $avatar, $email, $size, $default, $alt);
}

//自定义表情路径和名称
function custom_smilies_src($src, $img){return get_template_directory_uri().'/images/smilies/' . $img;}
add_filter('smilies_src', 'custom_smilies_src', 10, 2);

	if ( !isset( $wpsmiliestrans ) ) {
		$wpsmiliestrans = array(
		'[呵呵]' => '1.gif',
		'[嘻嘻]' => '2.gif',
		'[哈哈]' => '3.gif',
		'[偷笑]' => '4.gif',
		  '[挖鼻屎]' => '5.gif',
		  '[互粉]' => '6.gif',
		  '[吃惊]' => '7.gif',
		   '[疑问]' => '8.gif',
		   '[怒火]' => '9.gif',
		   '[睡觉]' => '10.gif',
		   '[鼓掌]' => '11.gif',
		   '[抓狂]' => '12.gif',
		   '[黑线]' => '13.gif',
		   '[阴险]' => '14.gif',
		   '[懒得理你]' => '15.gif',
		   '[嘘]' => '16.gif',
		   '[亲亲]' => '17.gif',
		   '[可怜]' => '18.gif',
		   '[害羞]' => '19.gif',
		   '[思考]' => '20.gif',
		   '[失望]' => '21.gif',
		   '[挤眼]' => '22.gif',		   
		   '[委屈]' => '23.gif',
		    '[太开心]' => '24.gif',
		    '[哈欠]' => '25.gif',
		    '[晕]' => '26.gif',
		    '[泪]' => '27.gif',
			'[困]' => '28.gif',
			'[悲伤]' => '29.gif',
			'[衰]' => '30.gif',
		     '[围观]' => '31.gif',
		     '[给力]' => '32.gif',
		      '[囧]' => '33.gif',
		      '[威武]' => '34.gif',
			  '[OK]' => '35.gif',
			  '[赞]' => '36.gif',
		);
	}

//获取评论者等级称号
function get_author_class($comment_author_email,$user_id){
global $wpdb;
$author_count = count($wpdb->get_results(
"SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
$adminEmail = get_option('admin_email');if($comment_author_email ==$adminEmail) return;
if($author_count>=1 && $author_count< get_option('ygj_pldjs_1')){
echo ' <span class="dengji">【';echo stripslashes(get_option('ygj_pldjch_1')); echo '】</span>';}
else if($author_count>=get_option('ygj_pldjs_1') && $author_count< get_option('ygj_pldjs_2')){
echo ' <span class="dengji">【';echo stripslashes(get_option('ygj_pldjch_2')); echo '】</span>';}
else if($author_count>=get_option('ygj_pldjs_2') && $author_count< get_option('ygj_pldjs_3')){
echo ' <span class="dengji">【';echo stripslashes(get_option('ygj_pldjch_3')); echo '】</span>';}
else if($author_count>=get_option('ygj_pldjs_3') && $author_count< get_option('ygj_pldjs_4')){
echo ' <span class="dengji">【';echo stripslashes(get_option('ygj_pldjch_4')); echo '】</span>';}
else if($author_count>=get_option('ygj_pldjs_4') && $author_count< get_option('ygj_pldjs_5')){
echo ' <span class="dengji">【';echo stripslashes(get_option('ygj_pldjch_5')); echo '】</span>';}
else if($author_count>=get_option('ygj_pldjs_5') &&$author_count< get_option('ygj_pldjs_6')){
echo ' <span class="dengji">【';echo stripslashes(get_option('ygj_pldjch_6')); echo '】</span>';}
else if($author_count>=get_option('ygj_pldjs_6') && $author_count< get_option('ygj_pldjs_7')){
echo ' <span class="dengji">【';echo stripslashes(get_option('ygj_pldjch_7')); echo '】</span>';}
else if($author_count>=get_option('ygj_pldjs_7') && $author_count< get_option('ygj_pldjs_8')){
echo ' <span class="dengji">【';echo stripslashes(get_option('ygj_pldjch_8')); echo '】</span>';}
else if($author_count>= get_option('ygj_pldjs_8')){
echo ' <span class="dengji">【';echo stripslashes(get_option('ygj_pldjch_9')); echo '】</span>';}
}	

if(get_option('ygj_autonl')):
/* 自动为文章内的标签添加内链开始 */
$match_num_to = get_option('ygj_autonl_2');      //一篇文章中同一个标签最多自动链接几次
function tag_sort($a, $b){
    if ( $a->name == $b->name ) return 0;
    return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
}
function tag_link($content){
    global $match_num_to;
        $posttags = get_the_tags();
        if ($posttags) {
            usort($posttags, "tag_sort");
            foreach($posttags as $tag) {
                $link = get_tag_link($tag->term_id);
                $keyword = $tag->name;
                $cleankeyword = stripslashes($keyword);
                $url = "<a href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('【查看含有[%s]标签的文章】', 'Nana'))."\"";
                $url .= ' target="_blank"';
                $url .= ">".addcslashes($cleankeyword, '$')."</a>";
                $limit = $match_num_to;
                $content = preg_replace( '|(<a[^>]+>)(.*)('.$ex_word.')(.*)(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
                $content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
                $cleankeyword = preg_quote($cleankeyword,'\'');
                $regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
                $content = preg_replace($regEx,$url,$content,$limit);
                $content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);
            }
        }
    return $content;
}
add_filter('the_content','tag_link',1);
endif;
add_filter( 'max_srcset_image_width', create_function( '', 'return 1;' ) );//禁用4.4图片多屏自适应功能
remove_action( 'wp_head','print_emoji_detection_script',7);     //解决4.2版本部分主题大量404请求问题
remove_action('admin_print_scripts', 'print_emoji_detection_script'); //解决后台404请求
remove_action( 'wp_print_styles',   'print_emoji_styles'    );  //移除4.2版本前台表情样式钩子
remove_action( 'admin_print_styles',    'print_emoji_styles');  //移除4.2版本后台表情样式钩子
remove_action( 'the_content_feed',      'wp_staticize_emoji');  //移除4.2 emoji相关钩子
remove_action( 'comment_text_rss',      'wp_staticize_emoji');  //移除4.2 emoji相关钩子
add_filter('xmlrpc_enabled', '__return_false'); //关闭XML-RPC的pingback端口
// 禁用emoji
 function disable_emojis() {
 	remove_action( 'wp_print_styles', 'print_emoji_styles' );
 }
 add_action( 'init', 'disable_emojis' );

// 禁用oembed/rest
function disable_embeds_init() {
	global $wp;
	$wp->public_query_vars = array_diff( $wp->public_query_vars, array(
		'embed',
	) );
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	add_filter( 'embed_oembed_discover', '__return_false' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}

add_action( 'init', 'disable_embeds_init', 9999 );

remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );

function disable_embeds_tiny_mce_plugin( $plugins ) {
	return array_diff( $plugins, array( 'wpembed' ) );
}
function disable_embeds_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite ) {
		if ( false !== strpos( $rewrite, 'embed=true' ) ) {
			unset( $rules[ $rule ] );
		}
	}
	return $rules;
}
function disable_embeds_remove_rewrite_rules() {
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'disable_embeds_remove_rewrite_rules' );
function disable_embeds_flush_rewrite_rules() {
	remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'disable_embeds_flush_rewrite_rules' );
/**
    *WordPress 4.6后禁止WordPress头部加载s.w.org
    *http://boke112.com/3495.html
*/
function remove_dns_prefetch( $hints, $relation_type ) {
if ( 'dns-prefetch' === $relation_type ) {
return array_diff( wp_dependencies_unique_hosts(), $hints );
}
return $hints;
}
add_filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );

/**
    *WordPress 后台回复评论插入表情
    *http://www.endskin.com/admin-smiley.html
*/
function Bing_ajax_smiley_scripts(){
    echo '<script type="text/javascript">function grin(e){var t;e=" "+e+" ";if(!document.getElementById("replycontent")||document.getElementById("replycontent").type!="textarea")return!1;t=document.getElementById("replycontent");if(document.selection)t.focus(),sel=document.selection.createRange(),sel.text=e,t.focus();else if(t.selectionStart||t.selectionStart=="0"){var n=t.selectionStart,r=t.selectionEnd,i=r;t.value=t.value.substring(0,n)+e+t.value.substring(r,t.value.length),i+=e.length,t.focus(),t.selectionStart=i,t.selectionEnd=i}else t.value+=e,t.focus()}jQuery(document).ready(function(e){var t="";e("#comments-form").length&&e.get(ajaxurl,{action:"ajax_data_smiley"},function(n){t=n,e("#qt_replycontent_toolbar input:last").after("<br>"+t)})})</script>';
}
add_action( 'admin_head', 'Bing_ajax_smiley_scripts' );
//Ajax 获取表情
function Bing_ajax_data_smiley(){
    $site_url = site_url();
    foreach( array_unique( (array) $GLOBALS['wpsmiliestrans'] ) as $key => $value ){
        $src_url = apply_filters( 'smilies_src', includes_url( 'images/smilies/' . $value ), $value, $site_url );
        echo ' <a href="javascript:grin(\'' . $key . '\')"><img src="' . $src_url . '" alt="' . $key . '" /></a> ';
    }
    die;
}
add_action( 'wp_ajax_nopriv_ajax_data_smiley', 'Bing_ajax_data_smiley' );
add_action( 'wp_ajax_ajax_data_smiley', 'Bing_ajax_data_smiley' );

//网站整体变灰及一键换色
function hui_head_css() { 
	$styles = ""; 		
	if (get_option('ygj_site_gray')) { 
		$styles .= "html{overflow-y:scroll;filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter: grayscale(100%);}"; 
	} 
$skin_option = get_option('ygj_theme_skin'); 
		$skc = "#" . $skin_option; 
	
	if ($skin_option && ($skin_option !== "C01E22")) { 
		$styles .= "@media screen and (min-width: 550px){.pagination span.current {border: 1px solid $skc;background: $skc;}}#searchform button,.entry-content .cat a,.post-format a,.aside-cat,.page-links span,.page-links a:hover span,.tglx,.widget_categories a:hover,.widget_links a:hover,#sidebar .widget_nav_menu a:hover,#respond #submit,.comment-tool a:hover,.pagination a:hover,.pagination .prev,.pagination .next,#down a,.buttons a,.expand_collapse,#tag_letter li:hover,.foot .p2 li .isquare,.link-all a:hover,.meta-nav:hover,.new_cat li.hov .time,.rslides_tabs .rslides_here a,.fancybox-close,.wplist-btn{background: $skc;}.widget_categories li:hover,.widget_links li:hover,#sidebar .widget_nav_menu li:hover{background-color:$skc;}a:hover,.top-menu a:hover,.default-menu li a,#user-profile a:hover,#site-nav .down-menu > li > a:hover,#site-nav .down-menu > li.sfHover > a,#site-nav .down-menu > .current-menu-item > a,#site-nav .down-menu > .current-menu-item > a:hover,.scrolltext-title a,.cat-list,.archive-tag a:hover,.entry-meta a,.single-content a,.single-content a:visited,.single-content a:hover,.showmore span,.post_cat a,.single_info .comment a,.single_banquan a,.single_info_w a,.floor,.at,.at a,#dzq .readers-list a:hover em,#dzq .readers-list a:hover strong,#all_tags li a:hover,.showmore span,.new_cat li.hov .title,a.top_post_item:hover p,#related-medias .media-list .media-inner .media-name,#site-nav ul li.current-menu-parent>a,#primarys .cat-lists .item-st:hover h3 a,#post_list_box .archive-list:hover h2 a,.line-one .cat-dt:hover h2 a,.line-one .cat-lists .item-st:hover h3 a{color: $skc;}.page-links span,.page-links a:hover span,#respond #submit,.comment-tool a:hover,.pagination a:hover,#down a,.buttons a,.expand_collapse,.link-all a:hover,.meta-nav:hover,.rslides_tabs .rslides_here a,.wplist-btn{border: 1px solid $skc;}#dzq .readers-list a:hover{border-color: $skc;}.sf-arrows>li>.sf-with-ul:focus:after,.sf-arrows>li:hover>.sf-with-ul:after,.sf-arrows>.sfHover>.sf-with-ul:after,.sf-arrows>li>.sf-with-ul:focus:after,.sf-arrows>li:hover>.sf-with-ul:after,.sf-arrows>.sfHover>.sf-with-ul:after{border-top-color: $skc;}.sf-arrows ul li>.sf-with-ul:focus:after,.sf-arrows ul li:hover>.sf-with-ul:after,.sf-arrows ul .sfHover>.sf-with-ul:after{border-left-color: $skc;}.cat-box .cat-title a,.cat-box .cat-title .syfl,.widget-title .cat,#top_post_filter li:hover,#top_post_filter .top_post_filter_active{border-bottom: 3px solid $skc;}.entry-content .cat a{border-left: 3px solid $skc;}.single-content h2,.archives-yearmonth{border-left: 5px solid $skc;}.aside-cat{background: none repeat scroll 0 0 $skc;}.new_cat li.hov{border-bottom: dotted 1px $skc;}"; 
	}  
	if ($styles) { 
		echo "<style>" . $styles . "</style>"; 
	} 
}
add_action("wp_head", "hui_head_css"); 

if (!get_option('ygj_3dtag')) {
// 彩色标签云
function colorCloud($text) {
	$text = preg_replace_callback('|<a (.+?)>|i', 'colorCloudCallback', $text);
	return $text;
}
function colorCloudCallback($matches) {
	$text = $matches[1];
	$color = dechex(rand(0,16777215));
	$pattern = '/style=(\'|\")(.*)(\'|\")/i';
	$text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
	return "<a $text>";
}
add_filter('wp_tag_cloud', 'colorCloud', 1);
}

//文章编辑器中添加表情
function fa_get_wpsmiliestrans(){
global $wpsmiliestrans;
$wpsmilies = array_unique($wpsmiliestrans);
$output='';
foreach($wpsmilies as $alt => $src_path){
$output .= '<a class="add-smily" data-smilies="'.$alt.'"><img class="wp-smiley" src="'.get_template_directory_uri().'/images/smilies/'.rtrim($src_path, "gif").'gif" /></a>';
}
return $output;
}
add_action('media_buttons_context', 'fa_smilies_custom_button');
function fa_smilies_custom_button($context) {
$context .= '<style>.smilies-wrap{background:#fff;border: 1px solid #ccc;box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.24);padding: 10px;position: absolute;top: 60px;width: 380px;display:none}.smilies-wrap img{height:24px;width:24px;cursor:pointer;margin-bottom:5px} .is-active.smilies-wrap{display:block}</style><a id="insert-media-button" style="position:relative" class="button insert-smilies add_smilies" title="添加表情" data-editor="content" href="javascript:;">
添加表情
</a><div class="smilies-wrap">'. fa_get_wpsmiliestrans() .'</div><script>jQuery(document).ready(function(){jQuery(document).on("click", ".insert-smilies",function() { if(jQuery(".smilies-wrap").hasClass("is-active")){jQuery(".smilies-wrap").removeClass("is-active");}else{jQuery(".smilies-wrap").addClass("is-active");}});jQuery(document).on("click", ".add-smily",function() { send_to_editor(" " + jQuery(this).data("smilies") + " ");jQuery(".smilies-wrap").removeClass("is-active");return false;});});</script>';
return $context;
}
//时间格式多久以前
function timeago($ptime) {
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if ($etime < 1) return '刚刚';
    $interval = array(
        12 * 30 * 24 * 60 * 60 => '年前 (' . date('Y-m-d', $ptime) . ')',
        30 * 24 * 60 * 60 => '个月前 (' . date('m-d', $ptime) . ')',
        7 * 24 * 60 * 60 => '周前 (' . date('m-d', $ptime) . ')',
        24 * 60 * 60 => '天前',
        60 * 60 => '小时前',
        60 => '分钟前',
        1 => '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}

// 点赞
add_action('wp_ajax_nopriv_ality_ding', 'ality_ding');
add_action('wp_ajax_ality_ding', 'ality_ding');
function ality_ding(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
	    $bigfa_raters = get_post_meta($id,'ality_like',true);
	    $expire = time() + 99999999;
	    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	    setcookie('ality_like_'.$id,$id,$expire,'/',$domain,false);
	    if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
			update_post_meta($id, 'ality_like', 1);
		}
	    else {
			update_post_meta($id, 'ality_like', ($bigfa_raters + 1));
		}
		echo get_post_meta($id,'ality_like',true);
    }
    die;
}
if(get_option('ygj_wlgonof')):
//给外部链接加上跳转(需新建页面，模板选择Go跳转页面，别名为go)
add_filter('the_content','the_content_nofollow',999);
function the_content_nofollow($content)
{
	preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/',$content,$matches);
	if($matches && !is_page('about')){
		foreach($matches[2] as $val){
			if(strpos($val,'://')!==false && strpos($val,home_url())===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$val)){
			    $content=str_replace("href=\"$val\"", "href=\"".home_url()."/go/?url=$val\"  rel=\"nofollow\" ",$content);
			}
		}
	}
	return $content;
}
// 下载外链跳转
function links_nofollow($url) {
    if(strpos($url,'://')!==false && strpos($url,home_url())===false && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$url)) {
    $url = str_replace($url, home_url()."/go/?url=$url",$url);
         }
    return $url;
}
endif;
if(get_option('ygj_lssdjt')):

// 网址跳转
function sites_nofollow($url) {
	$url = str_replace($url, home_url()."/go/".base64_encode($url),$url);
	return $url;
}

//历史上的今天，代码来自于柳城博主的WP-Today插件
function wp_today(){
	global $wpdb;
	$post_year = get_the_time('Y');
	$post_month = get_the_time('m');
	$post_day = get_the_time('j');
	$h_post='';
	$result='';
	
	$sql = "select ID, year(post_date) as h_year, post_title, comment_count FROM 
			$wpdb->posts WHERE post_password = '' AND post_type = 'post' AND post_status = 'publish'
			AND year(post_date)!='$post_year' AND month(post_date)='$post_month' AND day(post_date)='$post_day'
			order by post_date_gmt DESC limit 5";
	$histtory_post = $wpdb->get_results($sql);
	if( $histtory_post ){
		foreach( $histtory_post as $post ){
			$h_year = $post->h_year;
			$h_post_title = $post->post_title;
			$h_permalink = get_permalink( $post->ID );
			$h_comments = $post->comment_count;
			$h_post .= "<li><strong>$h_year:</strong>&nbsp;&nbsp;<a href='".$h_permalink."' title='".$h_post_title."' target='_blank'>$h_post_title(".$h_comments."条评论)</a></li>";
		}
	}

	if ( $h_post ){
		$result = "<span class=\"plxiaoshi\"><h2>历史上的今天:</h2><ul>".$h_post."</ul></span>";
	}

	return $result;
}
function wp_today_auto($content){
	if( is_single() ){
		$content = $content.wp_today();
	}
	return $content;
}
add_filter('the_content', 'wp_today_auto',9999);
endif;
if(get_option('ygj_zntjtpat')):
//智能添加图片alt和title属性
function image_title( $imgalt ){
        global $post;
		$category = get_the_category();
		$flname=$category[0]->cat_name;
        $imgtitle = $post->post_title;
        $imgUrl = "<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*>";
		$imgblog= get_bloginfo();
        if(preg_match_all("/$imgUrl/siU",$imgalt,$matches,PREG_SET_ORDER)){
                if( !empty($matches) ){
                        for ($i=0; $i < count($matches); $i++){
                                $tag = $url = $matches[$i][0];
								$j=$i+1;
                                $judge = '/title=/';
                                preg_match($judge,$tag,$match,PREG_OFFSET_CAPTURE);
                                if( count($match) < 1 )
                                $altURL = ' alt="'.$imgtitle.' '.$flname.' 第'.$j.'张" title="'.$imgtitle.' '.$flname.' 第'.$j.'张-'.$imgblog.'" ';
                                $url = rtrim($url,'>');
                                $url .= $altURL.'>';
                                $imgalt = str_replace($tag,$url,$imgalt);
                        }
                }
        }
        return $imgalt;
}
add_filter( 'the_content','image_title');
endif;
if(get_option('ygj_baiduts')):
/**
* WordPress发布文章主动推送到百度熊掌号
*/
if(!function_exists('Baidu_Submit')){
    function Baidu_Submit($post_ID) {
		$XZH_ID  = get_option('ygj_xzh_id');  //这里请换成你的熊掌号ID
		$XZH_TOKEN  = get_option('ygj_xzh_token');  //这里请换成你的熊掌号token值
        //已成功推送的文章不再推送
        if(get_post_meta($post_ID,'Baidusubmit',true) == 1) return;
        $url = get_permalink($post_ID);
        $copy = get_post_meta($post_ID, 'wzurl', true);
        if (empty($copy) && get_option('ygj_xzh_yc')) {	 
        $api = 'http://data.zz.baidu.com/urls?appid='.$XZH_ID.'&token='.$XZH_TOKEN.'&type=original';
        }else{ 
        $api = 'http://data.zz.baidu.com/urls?appid='.$XZH_ID.'&token='.$XZH_TOKEN.'&type=realtime';   
        }
        $request = new WP_Http;
        $result = $request->request( $api , array( 'method' => 'POST', 'body' => $url , 'headers' => 'Content-Type: text/plain') );
        $result = json_decode($result['body'],true);
        //如果推送成功则在文章新增自定义栏目Baidusubmit，值为1
        if (array_key_exists('success_realtime',$result)||array_key_exists('success_original',$result)) {
            add_post_meta($post_ID, 'Baidusubmit', 1, true);
        }
    }
    add_action('publish_post', 'Baidu_Submit', 0);
}
endif;

if(get_option('ygj_xiegang')):
/* 分类目录和页面链接地址以斜杠/结尾*/
function nice_trailingslashit($string, $type_of_url) {
    if ( $type_of_url != 'single' )
      $string = trailingslashit($string);
    return $string;
}
add_filter('user_trailingslashit', 'nice_trailingslashit', 10, 2);
endif;

if(get_option('ygj_admin_link')):
/* 加密后台登录地址*/
function ygj_login_protection() {
    if ($_GET[''.get_option('ygj_admin_q').''] !== ''.get_option('ygj_admin_a').'') {
     header('Location: '.get_option('ygj_admin_url').'');
    }
}
add_action('login_enqueue_scripts', 'ygj_login_protection');
endif;

if(get_option('ygj_sinasync')):
/**
* WordPress发布文章同步到新浪微博（带图片&自定义栏目版）
*/
function post_to_sina_weibo($post_ID) { 
   /* 此处修改为通过文章自定义栏目来判断是否同步 */
   if(get_post_meta($post_ID,'weibo_sync',true) == 1) return;
   $get_post_info = get_post($post_ID);
   $get_post_centent = get_post($post_ID)->post_content;
   $get_post_title = get_post($post_ID)->post_title;
   if ($get_post_info->post_status == 'publish' && $_POST['original_post_status'] != 'publish') {
	   $appkey = '' . get_option('ygj_sinasync_key') . ''; 
       $username = '' . get_option('ygj_sinasync_user') . '';
       $userpassword = '' . get_option('ygj_sinasync_pwd') . '';
       $request = new WP_Http;
       $keywords = ""; 
       /* 获取文章标签关键词 */
       $tags = wp_get_post_tags($post_ID);
       foreach ($tags as $tag ) {
          $keywords = $keywords.'#'.$tag->name."#";
       }
      /* 修改了下风格，并添加文章关键词作为微博话题，提高与其他相关微博的关联率 */
     $string1 = '【'. strip_tags( $get_post_title ).'】';
     $string2 = $keywords.' 查看全文：'.get_permalink($post_ID);
     /* 微博字数控制，避免超标同步失败 */
     $wb_num = (138 - WeiboLength($string1.$string2))*2;
     $status = $string1.mb_strimwidth(strip_tags( apply_filters('the_content', $get_post_centent)),0, $wb_num,'...').$string2;
       /* 获取指定图片，如果没设置就抓取文章第一张图片 */ 
		$url = catch_images($post_ID);
       /* 判断是否存在图片，定义不同的接口 */
       if(!empty($url)){
           $api_url = 'https://api.weibo.com/2/statuses/upload_url_text.json'; /* 新的API接口地址 */
           $body = array('status' => $status,'source' => $appkey,'url' => $url);
       } else {
           $api_url = 'https://api.weibo.com/2/statuses/update.json';
           $body = array('status' => $status,'source' => $appkey);
       }
       $headers = array('Authorization' => 'Basic ' . base64_encode("$username:$userpassword"));
       $result = $request->post($api_url, array('body' => $body,'headers' => $headers));
       /* 若同步成功，则给新增自定义栏目weibo_sync，避免以后更新文章重复同步 */
       add_post_meta($post_ID, 'weibo_sync', 1, true);
    }
}
add_action('publish_post', 'post_to_sina_weibo', 0);

/*
//获取微博字符长度函数 
*/
function WeiboLength($str)
{
    $arr = arr_split_zh($str);   //先将字符串分割到数组中
    foreach ($arr as $v){
        $temp = ord($v);        //转换为ASCII码
        if ($temp > 0 && $temp < 127) {
            $len = $len+0.5;
        }else{
            $len ++;
        }
    }
    return ceil($len);        //加一取整
}

/*
//拆分字符串函数,只支持 gb2312编码  
//参考：http://u-czh.iteye.com/blog/1565858
*/
function arr_split_zh($tempaddtext){
    $tempaddtext = iconv("UTF-8", "GBK//IGNORE", $tempaddtext);
    $cind = 0;
    $arr_cont=array();
    for($i=0;$i<strlen($tempaddtext);$i++)
    {
        if(strlen(substr($tempaddtext,$cind,1)) > 0){
            if(ord(substr($tempaddtext,$cind,1)) < 0xA1 ){ //如果为英文则取1个字节
                array_push($arr_cont,substr($tempaddtext,$cind,1));
                $cind++;
            }else{
                array_push($arr_cont,substr($tempaddtext,$cind,2));
                $cind+=2;
            }
        }
    }
    foreach ($arr_cont as &$row)
    {
        $row=iconv("gb2312","UTF-8",$row);
    }
    return $arr_cont;
}
function catch_images($post_ID) {
 $post=get_post($post_ID);
  $first_img = '';
  if ( get_post_meta($post->ID, 'wzshow', true) ) {
    	$first_img = get_post_meta($post->ID, 'wzshow', true);
  }else{
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];
	}
  if(empty($first_img)){ //Defines a default image
		$random = mt_rand(1, 10);
		echo get_bloginfo ( 'stylesheet_directory' );
		echo '/images/random/'.$random.'.jpg';
  }
  return $first_img;
}
endif;
if (get_option('ygj_mailsmtp_b')):
//SMTP邮箱设置
function googlo_mail_smtp($phpmailer) {
    $phpmailer->From = '' . get_option('ygj_maildizhi_b') . ''; //发件人地址
    $phpmailer->FromName = '' . get_option('ygj_mailnichen_b') . ''; //发件人昵称
    $phpmailer->Host = '' . get_option('ygj_mailsmtp_b') . ''; //SMTP服务器地址
    $phpmailer->Port = '' . get_option('ygj_mailport_b') . ''; //SMTP邮件发送端口
    if (get_option('ygj_smtpssl_b')) {
    $phpmailer->SMTPSecure = 'ssl';
    }else{
    $phpmailer->SMTPSecure = '';
    }//SMTP加密方式(SSL/TLS)没有为空即可
    $phpmailer->Username = '' . get_option('ygj_mailuser_b') . ''; //邮箱帐号
    $phpmailer->Password = '' . get_option('ygj_mailpass_b') . ''; //邮箱密码
    $phpmailer->IsSMTP();
    $phpmailer->SMTPAuth = true; //启用SMTPAuth服务

}
    add_action('phpmailer_init', 'googlo_mail_smtp');
endif;

//评论微信推送
function sc_send($comment_id)
{
$text = '博客上有一条新的评论';
$comment = get_comment($comment_id);
$desp = $comment->comment_content;
$key = 'SCU9965T15f561823ad759c3f59e1f1de62e1b215960e491e7342';
$postdata = http_build_query(
array(
'text' => $text,
'desp' => $desp
)
);
 
$opts = array('http' =>
array(
'method' => 'POST',
'header' => 'Content-type: application/x-www-form-urlencoded',
'content' => $postdata
)
);
$context = stream_context_create($opts);
return $result = file_get_contents('http://sc.ftqq.com/'.$key.'.send', false, $context);
}
add_action('comment_post', 'sc_send', 19, 2);

//修复 WordPress 找回密码提示“抱歉，该key似乎无效”
function reset_password_message( $message, $key ) {
	if ( strpos($_POST['user_login'], '@') ) {
		$user_data = get_user_by('email', trim($_POST['user_login']));
	} else {
		$login = trim($_POST['user_login']);
		$user_data = get_user_by('login', $login);
	}
	$user_login = $user_data->user_login;
	$msg = __('有人要求重设如下帐号的密码：'). "\r\n\r\n";
	$msg .= network_site_url() . "\r\n\r\n";
	$msg .= sprintf(__('用户名：%s'), $user_login) . "\r\n\r\n";
	$msg .= __('若这不是您本人要求的，请忽略本邮件，一切如常。') . "\r\n\r\n";
	$msg .= __('要重置您的密码，请打开下面的链接：'). "\r\n\r\n";
	$msg .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') ;
	return $msg;
}
add_filter('retrieve_password_message', reset_password_message, null, 2); 

//禁止七牛缓存
if(!is_admin()) {
    add_action('init', 'deny_mirrored_request', 0);
}
function deny_mirrored_request()
{
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if(!$ua || preg_match('/PHP/i', $ua)) {
        header("Content-type: text/html; charset=utf-8");
        wp_die('请勿采集本站，因为采集的站长木有小JJ!');
    }
}

//实现侧边栏文本工具运行PHP代码
add_filter('widget_text', 'php_text', 99);
function php_text($text) {
if (strpos($text, '<' . '?') !== false) {
ob_start();
eval('?' . '>' . $text);
$text = ob_get_contents();
ob_end_clean();
}
return $text;
}

//导航菜单添加手气不错按钮
function random_postlite() {
	$loop = new WP_Query( array( 'post_type' => array(post),'orderby' => 'rand','posts_per_page' => 1 ) );
while ( $loop->have_posts() ) : $loop->the_post();
wp_redirect( get_permalink() );
endwhile;
}
if ( isset( $_GET['random'] ) )
add_action( 'template_redirect', 'random_postlite' );

// 分页
function ygj_pagenav() {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="nav-below">
			<div class="nav-next"><?php previous_posts_link(''); ?></div>
			<div class="nav-previous"><?php next_posts_link(''); ?></div>
		</nav>
	<?php endif;
}

// Ajax加载
if (get_option('ygj_gdjz') !== '标准页码') {
	function ajax_scroll_js() {
		if ( !is_singular() && !is_paged() ) { ?>
	<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jquery-ias.js"></script>
	<script type="text/javascript">
	<?php if ((get_option('ygj_fllbbj') == '图片布局' && is_category())||(get_option('ygj_home') == '图片布局' ) && is_home()) {?>
    var ias = $.ias({
        container: ".cat-one-list",
        item: ".cat-lists",
        pagination: "#nav-below",
        next: "#nav-below .nav-previous a",
    });
	<?php }if ((get_option('ygj_fllbbj') == '列表布局' && is_category())||(get_option('ygj_home') == '博客布局' && is_home())||is_tag()||is_author() || is_day()) {?>
	var ias = $.ias({
        container: "#post_list_box",
        item: "article",
        pagination: "#nav-below",
        next: "#nav-below .nav-previous a",
    });
	<?php }?>
	<?php if (get_option('ygj_gdjz') == '人工加载') {?>
    ias.extension(new IASTriggerExtension({
        text: '<i class="fa fa-chevron-circle-down"></i>加载更多', 
        offset: 1, 
    }));
	<?php }?>
    ias.extension(new IASSpinnerExtension());
    ias.extension(new IASNoneLeftExtension({
        text: '已经加载完成！',
    }));
    ias.on('rendered', function(items) {
        $("img").lazyload({
            effect: "fadeIn",
        failure_limit : 10
        }); 
    })
</script>
	
<?php
}
}
		add_action('wp_footer', 'ajax_scroll_js', 100);
}

// 浏览总数
function all_view(){
global $wpdb;
$count=0;
$views= $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key='views'");
foreach($views as $key=>$value)
	{
		$meta_value=$value->meta_value;
		if($meta_value!=' '){
			$count+=(int)$meta_value;
		}
	}
return $count;
}

if (get_option('ygj_lostpwd')) {
//将忘记密码这个链接隐藏掉
add_action('login_head', 'wpdit_login_head');
function wpdit_login_head() {
?>
    <style type="text/css">
       #nav,#login_error,.login-action-lostpassword { 
         display: none; 
       }
    </style>
    <?php
}

/* 隐藏获取新密码页面内容*/
function ygj_lostpassword_protection() {
    if ($_GET['action'] === 'lostpassword') {
       header('Location: '.home_url( '/' ).'');
    }
}
add_action('login_enqueue_scripts', 'ygj_lostpassword_protection');
}

// 文章部分内容评论可见
function reply_read($atts, $content=null) {
    extract(shortcode_atts(array("notice" => '
    <div class="reply-read">
		<div class="reply-ts">
    		<div class="read-sm"><i class="fa fa-exclamation-circle"></i>此处为隐藏的内容！</div>
			<div class="read-smx"><i class="fa fa-spinner"></i>发表评论并刷新，才能查看</div>
		</div>
    	<div class="read-pl"><a href="#respond">发表评论</a></div>
    	<div class="clear"></div>
    </div>'), $atts));
    $email = null;
    $user_ID = (int) wp_get_current_user()->ID;
    if ($user_ID > 0) {
        $email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {
	return '<div class="secret-password"><h2>隐藏的内容：</h2><p>'.do_shortcode( $content ).'</p></div>';
		}
    } else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
        $email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
    } else {
        return $notice;
    }
    if (empty($email)) {
        return $notice;
    }
    global $wpdb;
    $post_id = get_the_ID();
    $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
    if ($wpdb->get_results($query)) {
        return do_shortcode('<div class="secret-password"><h2>隐藏的内容：</h2><p>'.do_shortcode( $content ).'</p></div>');
    } else {
        return $notice;
    }
}
add_shortcode('reply', 'reply_read');  
// 文章部分内容密码可见
function e_secret($atts, $content=null){
extract(shortcode_atts(array('key'=>null), $atts));
if(isset($_POST['secret_key']) && $_POST['secret_key']==$key){
	return '<div class="secret-password"><h2>加密的内容：</h2><p>'.do_shortcode( $content ).'</p></div>';
	} else {
		return '
		<form class="post-password-form" action="'.get_permalink().'" method="post">
			<p><div class="post-secret"><i class="fa fa-exclamation-circle"></i>输入密码查看加密内容：</div></p>
			<p>
				<input id="pwbox" type="password" size="20" name="secret_key">
				<a class="a2" href="javascript:;"><input type="submit" value="提交" name="Submit"></a>
			</p>
		</form>	';
	}
}
add_shortcode('secret','e_secret');
// 评论@回复
function comment_at( $comment_text, $comment = '') {
  if( $comment->comment_parent > 0) {
    $comment_text = '<a class="at" href="#comment-' . $comment->comment_parent . '">@'.get_comment_author( $comment->comment_parent ) . '</a>' . $comment_text;
  }
  return $comment_text;
}
add_filter( 'comment_text' , 'comment_at', 20, 2);

if (get_option('ygj_mirror')) {
/**
    *防止WordPress站点被恶意镜像，请修改$currentDomain代码中的网址
    *http://boke112.com/3497.html
*/
add_action('wp_footer','deny_mirrored_websites');
function deny_mirrored_websites(){
    $currentDomain = 'atzzz." + "com';
    echo '<img style="display:none" src=" " onerror=\'var str1="'.$currentDomain.'";str2="docu"+"ment.loca"+"tion.host";str3=eval(str2);if( str1!=str3 ){ do_action = "loca" + "tion." + "href = loca" + "tion.href" + ".rep" + "lace(docu" +"ment"+".loca"+"tion.ho"+"st," + "\"' . $currentDomain .'\"" + ")";eval(do_action) }\' />';
}
}
if (get_option('ygj_yhhtml')) {
/**
    *压缩优化WordPress前端html代码
    *http://boke112.com/3549.html
*/
function wp_compress_html(){
    function wp_compress_html_main ($buffer){
        $initial=strlen($buffer);
        $buffer=explode("<!--wp-compress-html-->", $buffer);
        $count=count ($buffer);
        for ($i = 0; $i <= $count; $i++){
            if (stristr($buffer[$i], '<!--wp-compress-html no compression-->')) {
                $buffer[$i]=(str_replace("<!--wp-compress-html no compression-->", " ", $buffer[$i]));
            } else {
                $buffer[$i]=(str_replace("\t", " ", $buffer[$i]));
                $buffer[$i]=(str_replace("\n\n", "\n", $buffer[$i]));
                $buffer[$i]=(str_replace("\n", "", $buffer[$i]));
                $buffer[$i]=(str_replace("\r", "", $buffer[$i]));
                while (stristr($buffer[$i], '  ')) {
                    $buffer[$i]=(str_replace("  ", " ", $buffer[$i]));
                }
            }
            $buffer_out.=$buffer[$i];
        }
        $final=strlen($buffer_out);
        $savings=($initial-$final)/$initial*100;
        $savings=round($savings, 2);
        $buffer_out.="\n<!--压缩前的大小: $initial bytes; 压缩后的大小: $final bytes; 节约：$savings% -->";
    return $buffer_out;
}
//WordPress后台不压缩
if ( !is_admin() ) {
        ob_start("wp_compress_html_main");
    }
}
add_action('init', 'wp_compress_html');
//当检测到文章内容中有代码标签时文章内容不会被压缩
function unCompress($content) {
    if(preg_match_all('/(crayon-|<\/pre>)/i', $content, $matches)) {
        $content = '<!--wp-compress-html--><!--wp-compress-html no compression-->'.$content;
        $content.= '<!--wp-compress-html no compression--><!--wp-compress-html-->';
    }
    return $content;
}
add_filter( "the_content", "unCompress");
}

//展开收缩功能
function xcollapse($atts, $content = null) {
    extract(shortcode_atts(array( "title" => "" ) , $atts));
    return '<div style="margin: 0.5em 0;"><div class="xControl" style="text-indent: 1em;margin: 0.5em 0;"><a href="javascript:void(0)" class="collapseButton xButton"><i class="fa fa-plus-square" ></i> ' . $title . '</a><div style="clear: both;"></div></div><div class="xContent" style="text-indent: 2em;display: none;">' . $content . '</div></div>';
}
add_shortcode('collapse', 'xcollapse');

//网易云音乐
function music163($atts) {
    extract(shortcode_atts(array("id" => "" ) , $atts));
    return '<iframe style="width:100%;max-height:86px;" frameborder="no" border="0" marginwidth="0" marginheight="0" src="http://music.163.com/outchain/player?type=2&id=' . $id . '&auto=1&height=66"></iframe>';
}
add_shortcode('netmusic', 'music163');


// 图片数量
if( !function_exists('get_post_images_number') ){
	function get_post_images_number(){
		global $post;
		$content = $post->post_content; 
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $result, PREG_PATTERN_ORDER);
		return count($result[1]);
	}
}
//360自动收录
if (get_option('ygj_360sl')) {

if(!function_exists('sll_check_record')){
  function sll_check_record($url,$post_id){
    $sll_record  = get_post_meta($post_id,'sll_record',true);
    if( $sll_record != 1){
        $url='https://www.so.com/s?q='.$url;
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        $rs=curl_exec($curl);
        curl_close($curl);
        if( !preg_match_all('/提交网址/u',$rs)){
            update_post_meta($post_id, 'sll_record', 1) || add_post_meta($post_id, 'sll_record', 1, true);
            return 1;
        } else {
            return 0;
        }
    } else {
       return 1;
    }
  }
}
if(!function_exists('sllPushData')){
  function sllPushData() {
    global $wpdb;
	$sllkey=get_option('ygj_360sl_id');
    $post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
    $currentUrl = home_url(add_query_arg(array()));
    if(sll_check_record(get_permalink($post_id), $post_id) == 0 && $currentUrl == get_permalink($post_id)) {
        echo "<script>
(function(){
   var src = (document.location.protocol == 'http:') ? 'http://js.passport.qihucdn.com/11.0.1.js?".$sllkey."':'https://jspassport.ssl.qhimg.com/11.0.1.js?".$sllkey."';
   document.write('<script src='' + src + '' id='sozz'><\/script>');
})();
</script>";
   }
 }
}
add_action( 'wp_footer', 'sllPushData', 999);
}

//个人资料中直接添加额外的作者信息
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );
function extra_user_profile_fields( $user ) { ?>
<h3>额外信息</h3>
<table class="form-table">
	<tr>
		<th><label for="qq">QQ在线</label></th>
		<td>
			<input type="text" name="qq" id="qq" value="<?php echo esc_attr( get_the_author_meta( 'qq', $user->ID ) ); ?>" class="regular-text" /><br />
			<span class="description">QQ在线的QQ号</span>
		</td>
	</tr>
	<tr>
		<th><label for="weixin">微信号</label></th>
		<td>
			<input type="text" name="weixin" id="weixin" value="<?php echo esc_attr( get_the_author_meta( 'weixin', $user->ID ) ); ?>" class="regular-text" /><br />
			<span class="description">微信二维码图片地址(120*120)</span>
		</td>
	</tr>
	<tr>
		<th><label for="weibo">新浪微博</label></th>
		<td>
			<input type="text" name="weibo" id="weibo" value="<?php echo esc_attr( get_the_author_meta( 'weibo', $user->ID ) ); ?>" class="regular-text" /><br />
			<span class="description">新浪微博地址</span>
		</td>
	</tr>
	<tr>
		<th><label for="toutiao">头条号</label></th>
		<td>
			<input type="text" name="toutiao" id="toutiao" value="<?php echo esc_attr( get_the_author_meta( 'toutiao', $user->ID ) ); ?>" class="regular-text" /><br />
			<span class="description">头条号地址</span>
		</td>
	</tr>	
</table>
<?php }
 
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
 
function save_extra_user_profile_fields( $user_id ) {
 
	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
 
	update_usermeta( $user_id, 'qq', $_POST['qq'] );
	update_usermeta( $user_id, 'weixin', $_POST['weixin'] );
	update_usermeta( $user_id, 'weibo', $_POST['weibo'] );
	update_usermeta( $user_id, 'toutiao', $_POST['toutiao'] );
}

//通过短代码显示站内文章图文
function lxtx_fa_insert_posts( $atts, $content = null ){
    extract( shortcode_atts( array(
        'ids' => ''
    ),
        $atts ) );
    global $post;
    $content = '';
    $postids =  explode(',', $ids);
    $inset_posts = get_posts(array('post__in'=>$postids));
    foreach ($inset_posts as $key => $post) {
        setup_postdata( $post );
        $content .=  '<div class="wplist-item"><a href="' . get_permalink() . '" target="_blank" isconvert="1" rel="nofollow"><div class="wplist-item-img">'.ygj_thumbnailnolinkwz(480,312).'</div><div class="wplist-title">' . get_the_title() . '</div><p class="wplist-des">'.wp_trim_words( get_the_content(), 90, '......' ).'</p><div class="wplist-btn">阅读全文</div><div class="clear"></div></a><div class="clear"></div></div>';
    }
    wp_reset_postdata();
    return $content;
}
add_shortcode('lxtx_fa_insert_post', 'lxtx_fa_insert_posts');

?>
