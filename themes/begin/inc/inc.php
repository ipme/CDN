<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
begin_setup();
function begin_pagenav() {
	if (zm_get_option('infinite_post')) {
		global $wp_query;
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="nav-below">
				<div class="nav-next"><?php previous_posts_link(''); ?></div>
				<div class="nav-previous"><?php next_posts_link(''); ?></div>
			</nav>
		<?php endif;
	}
	if (wp_is_mobile()) {
		the_posts_pagination( array(
			'mid_size'           => 1,
			'prev_text'          => '<i class="be be-arrowleft"></i>',
			'next_text'          => '<i class="be be-arrowright"></i>',
			'before_page_number' => '<span class="screen-reader-text">'.sprintf(__( '第', 'begin' )).' </span>',
			'after_page_number'  => '<span class="screen-reader-text"> '.sprintf(__( '页', 'begin' )).'</span>',
		) );
	} else {
		the_posts_pagination( array(
			'mid_size'           => 4,
			'prev_text'          => '<i class="be be-arrowleft"></i>',
			'next_text'          => '<i class="be be-arrowright"></i>',
			'before_page_number' => '<span class="screen-reader-text">'.sprintf(__( '第', 'begin' )).' </span>',
			'after_page_number'  => '<span class="screen-reader-text"> '.sprintf(__( '页', 'begin' )).'</span>',
		) );
	}
}
// 只搜索文章标题
function only_search_by_title( $search, $wp_query ) {
	if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
		global $wpdb;
		$q = $wp_query->query_vars;
		$n = ! empty( $q['exact'] ) ? '' : '%';
		$search = array();
		foreach ( ( array ) $q['search_terms'] as $term )
			$search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
		if ( ! is_user_logged_in() )
			$search[] = "$wpdb->posts.post_password = ''";
		$search = ' AND ' . implode( ' AND ', $search );
	}
	return $search;
}

// 修改搜索URL
function change_search_url_rewrite() {
	if ( is_search() && ! empty( $_GET['s'] ) ) {
		wp_redirect( home_url( '/search/' ) . urlencode( get_query_var( 's' ) ) . '/');
		exit();
	}
}

if (!zm_get_option('search_option') || (zm_get_option('search_option') == 'search_url')) {
	add_action( 'template_redirect', 'change_search_url_rewrite' );
}

if (zm_get_option('search_option') == 'search_cat') {
function search_cat_args() { ?>
	<span class="search-cat">
		<?php $args = array(
			'show_option_all' => '全部分类',
			'hide_empty'      => 0,
			'name'            => 'cat',
			'show_count'      => 0,
			'taxonomy'        => 'category',
			'hierarchical'    => 1,
			'depth'           => -1,
			'echo'            => 1,
			'exclude'         => zm_get_option('not_search_cat'),
		); ?>
		<?php wp_dropdown_categories( $args ); ?>
	</span>
<?php }
}

// 禁用WP搜索

function disable_search( $query, $error = true ) {
	if (is_search() && !is_admin()) {
		$query->is_search = false;
		$query->query_vars['s'] = false;
		$query->query['s'] = false;
		if ( $error == true )
		//$query->is_home = true; //跳转到首页
		$query->is_404 = true;//跳转到404页
	}
}
if (! zm_get_option('wp_s')) {
add_action( 'parse_query', 'disable_search' );
add_filter( 'get_search_form', create_function( '$a', "return null;" ) );
}
// gravatar头像调用
function cn_avatar($avatar) {
	$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://cn.gravatar.com/avatar/$1?s=$2&d=mm" alt="avatar" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
	return $avatar;
}

function ssl_avatar($avatar) {
	$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2&d=mm" alt="avatar" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
	return $avatar;
}

function dn_avatar($avatar) {
	$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="' . zm_get_option("dn_avatar_url") .'$1?s=$2&d=mm" alt="avatar" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
	return $avatar;
}

if (zm_get_option('no') !== 'no') :
	if (!zm_get_option('gravatar_url') || (zm_get_option("gravatar_url") == 'dn')) {
		add_filter('get_avatar', 'cn_avatar');
	}

	if (zm_get_option('gravatar_url') == 'ssl') {
		add_filter('get_avatar', 'ssl_avatar');
	}

	if (zm_get_option('gravatar_url') == 'dn') {
		add_filter('get_avatar', 'dn_avatar');
	}
endif;

// 后台禁止头像
if (zm_get_option('ban_avatars') && is_admin()) {
add_filter( 'get_avatar' , 'ban_avatar' , 1 , 1 );
}
function ban_avatar( $avatar) {
    $avatar = "";
}
// 标签
require get_template_directory() . '/inc/tag-letter.php';

// 字数统计
function count_words ($text) {
	global $post;
	if ( '' == $text ) {
		$text = $post->post_content;
		if (mb_strlen($output, 'UTF-8') < mb_strlen($text, 'UTF-8')) $output .= '<span class="word-count"><i class="be be-paper"> </i>' . mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8') . ''.sprintf(__( '字', 'begin' )).'</span>';
		return $output;
	}
}

// 阅读时间
function get_reading_time($content) {
	$zm_format = '<span class="reading-time">阅读%min%分%sec%秒</span>';
	$zm_chars_per_minute = 300; // 估算1分种阅读字数

	$zm_format = str_replace('%num%', $zm_chars_per_minute, $zm_format);
	$words = mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($content))),'UTF-8');
	//$words = mb_strlen(strip_tags($content));

	$minutes = floor($words / $zm_chars_per_minute);
	$seconds = floor($words % $zm_chars_per_minute / ($zm_chars_per_minute / 60));
	return str_replace('%sec%', $seconds, str_replace('%min%', $minutes, $zm_format));
}

function reading_time() {
	echo get_reading_time(get_the_content());
}

// 字数统计
function word_num () {
	global $post;
	$text_num = mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8');
	return $text_num;
}

// 分类优化
function zm_category() {
	$category = get_the_category();
	if($category[0]){
	echo '<a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</a>';
	}
}

// 索引
function zm_get_current_count() {
	global $wpdb;
	$current_post = get_the_ID();
	$query = "SELECT post_id, meta_value, post_status FROM $wpdb->postmeta";
	$query .= " LEFT JOIN $wpdb->posts ON post_id=$wpdb->posts.ID";
	$query .= " WHERE post_status='publish' AND meta_key='zm_like' AND post_id = '".$current_post."'";
	$results = $wpdb->get_results($query);
	if ($results) {
		foreach ($results as $o):
			echo $o->meta_value;
		endforeach;
	} else {echo( '0' );}
}

if (zm_get_option('index_c')) {
// 目录
function article_catalog($content) {
	$matches = array();
	$ul_li = '';
	$r = "/<h4>([^<]+)<\/h4>/im";

	if(preg_match_all($r, $content, $matches)) {
		foreach($matches[1] as $num => $title) {
			$content = str_replace($matches[0][$num], '<span class="directory"></span><h4 id="title-'.$num.'">'.$title.'</h4>', $content);
			$ul_li .= '<li><a href="#title-'.$num.'"><i class="be be-sort"></i> '.$title."</a></li>\n";
		}
		$content = "
			\n<div id=\"log-box\">
				<div id=\"catalog\"><ul id=\"catalog-ul\">\n" . $ul_li . "</ul><span class=\"log-zd\"><span class=\"log-close\"><a title=\"" . sprintf(__( '隐藏目录', 'begin' )) . "\"><i class=\"be be-cross\"></i><strong>" . sprintf(__( '目录', 'begin' )) . "</strong></a></span></span></div>
			</div>\n" . $content;
	}
	return $content;
}
add_filter( "the_content", "article_catalog" );
}

if (zm_get_option('tag_c')) {
// 关键词加链接
$match_num_from = 1;
$match_num_to = 1;
add_filter('the_content','tag_link',1);
function tag_sort($a, $b){
	if ( $a->name == $b->name ) return 0;
	return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
}
function tag_link($content) {
global $match_num_from,$match_num_to;
	$posttags = get_the_tags();
	if ($posttags) {
		usort($posttags, "tag_sort");
		foreach($posttags as $tag) {
			$link = get_tag_link($tag->term_id);
			$keyword = $tag->name;
			// if (preg_match_all('|(<h[^>]+>)(.*?)'.$keyword.'(.*?)(</h[^>]*>)|U', $content, $matchs)) {continue;}
			// if (preg_match_all('|(<a[^>]+>)(.*?)'.$keyword.'(.*?)(</a[^>]*>)|U', $content, $matchs)) {continue;}

			$cleankeyword = stripslashes($keyword);
			$url = "<a href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('查看与 %s 相关的文章', 'begin' ))."\"";
			$url .= ' target="_blank"';
			$url .= ">".addcslashes($cleankeyword, '$')."</a>";
			$limit = rand($match_num_from,$match_num_to);

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
}

// 图片alt
if (zm_get_option('image_alt')) {
function image_alt_title($content) {
	global $post;
	$pattern = "/<img(.*?)src=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
	$replacement = '<img$1src=$2$3.$4$5 alt="'.$post->post_title.'" $6>';
	$content = preg_replace($pattern,$replacement,$content);
	return $content;
}
add_filter('the_content','image_alt_title',15);
}

// 形式名称
function begin_post_format( $safe_text ) {
	if ( $safe_text == '引语' )
		return '软件';
	return $safe_text;
}

// 点击最多文章
function get_timespan_most_viewed($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title =  get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
			$temp .= "<li><span class='li-icon li-icon-$i'>$i</span><a href=\"".get_permalink()."\">$post_title</a></li>";
			$i++;
		}
	} else {
		$temp = '<li>暂无文章</li>';
	}
	if($display) {
		echo $temp;
	} else {
		return $temp;
	}
}

// 热门文章
function get_timespan_most_viewed_img($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
			echo "<li>";
			echo "<span class='thumbnail'>";
			echo zm_thumbnail();
			echo "</span>"; 
			echo the_title( sprintf( '<span class="new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></span>' ); 
			echo "<span class='date'>";
			echo the_time('m/d');
			echo "</span>";
			echo the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>');
			echo "</li>"; 
		}
	}
}

//点赞最多文章
function get_like_most($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS zm_like FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'zm_like' AND post_password = '' ORDER  BY zm_like DESC LIMIT $limit");
	if($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_like = intval($post->like);
			$post_like = number_format($post_like);
			$temp .= "<li><span class='li-icon li-icon-$i'>$i</span><a href=\"".get_permalink()."\">$post_title</a></li>";
			$i++;
		}
	} else {
		$temp = '<li>暂无文章</li>';
	}
	if($display) {
		echo $temp;
	} else {
		return $temp;
	}
}

// 点赞最多有图
function get_like_most_img($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS zm_like FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'zm_like' AND post_password = '' ORDER  BY zm_like DESC LIMIT $limit");
	if($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_like = intval($post->like);
			$post_like = number_format($post_like);
			echo "<li>";
			echo "<span class='thumbnail'>";
			echo zm_thumbnail();
			echo "</span>";
			echo the_title( sprintf( '<span class="new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></span>' );
			echo "<span class='discuss'><i class='be be-thumbs-up-o'>&nbsp;";
			echo zm_get_current_count();
			echo "</i></span>";
			echo "<span class='date'>";
			echo the_time( 'm/d' );
			echo "</span>";
			echo "</li>";
		}
	}
}

// 点赞
function begin_like(){
	global $wpdb,$post;
	$id = $_POST["um_id"];
	$action = $_POST["um_action"];
	if ( $action == 'ding'){
		$bigfa_raters = get_post_meta($id,'zm_like',true);
		$expire = time() + 99999999;
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		setcookie('zm_like_'.$id,$id,$expire,'/',$domain,false);
		if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
			update_post_meta($id, 'zm_like', 1);
		}
		else {
			update_post_meta($id, 'zm_like', ($bigfa_raters + 1));
		}
		echo get_post_meta($id,'zm_like',true);
	}
	die;
}

if (zm_get_option('baidu_submit')) {
// 主动推送
	if(!function_exists('Baidu_Submit') && function_exists('curl_init')) {
		function Baidu_Submit($post_ID) {
			$WEB_TOKEN = zm_get_option('token_p');
			$WEB_DOMAIN = get_option('home');
			if(get_post_meta($post_ID,'Baidusubmit',true) == 1) return;
			$url = get_permalink($post_ID);
			$api = 'http://data.zz.baidu.com/urls?site='.$WEB_DOMAIN.'&token='.$WEB_TOKEN;
			$ch  = curl_init();
			$options =  array(
				CURLOPT_URL => $api,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $url,
				CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
			);
			curl_setopt_array($ch, $options);
			$result = json_decode(curl_exec($ch),true);
			if (array_key_exists('success',$result)) {
				add_post_meta($post_ID, 'Baidusubmit', 1, true);
			}
		}
		add_action('publish_post', 'Baidu_Submit', 0);
	}
}

// 评论贴图
if (zm_get_option('embed_img')) {
add_action('comment_text', 'comments_embed_img', 2);
}
function comments_embed_img($comment) {
	$size = 'auto';
	$comment = preg_replace(array('#(http://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#','#(https://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#'),'<img src="$1" alt="评论" style="width:'.$size.'; height:'.$size.'" />', $comment);
	return $comment;
}

// connector
function connector() {
	if (zm_get_option('blank_connector')) {echo '';}else{echo ' ';}
	echo zm_get_option('connector');
	if (zm_get_option('blank_connector')) {echo '';}else{echo ' ';}
}

// title
if (zm_get_option('wp_title')) {
// filters title
function custom_filters_title() { 
	$separator = ''.zm_get_option('connector').'';
	return $separator;
}
add_filter('document_title_separator', 'custom_filters_title');
} else {
function begin_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}
	$title .= get_bloginfo( 'name', 'display' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'begin_wp_title', 10, 2 );
}

if (zm_get_option('refused_spam')) {
	// 禁止无中文留言
	if ( current_user_can('level_10') ) {
	} else {
	function refused_spam_comments( $comment_data ) {
		$pattern = '/[一-龥]/u';  
		if(!preg_match($pattern,$comment_data['comment_content'])) {
			err('评论必须含中文！');
		}
		return( $comment_data );
	}
	add_filter('preprocess_comment','refused_spam_comments');
	}
}
// @回复
if (zm_get_option('at')) {
function comment_at( $comment_text, $comment = '') {
	if( $comment->comment_parent > 0) {
		$comment_text = '<span class="at">@<a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a></span> ' . $comment_text;
	}
	return $comment_text;
}
add_filter( 'comment_text' , 'comment_at', 20, 2);
}

// 浏览总数
function all_view(){
	global $wpdb;
	$count =  $wpdb->get_var("SELECT sum(meta_value) FROM $wpdb->postmeta WHERE meta_key='views'");
	return $count;
}

// 某作者文章浏览数
if(!function_exists('author_posts_views')) {
	function author_posts_views($author_id = 1 ,$display = true) {
		global $wpdb;
		$sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = 'views' AND post_author =$author_id";
		$comment_views = intval($wpdb->get_var($sql));
		if($display) {
			echo number_format_i18n($comment_views);
		} else {
			return $comment_views;
		}
	}
}

// 编辑_blank
function edit_blank($text) {
	return str_replace('<a', '<a target="_blank"', $text);
}
add_filter('edit_post_link', 'edit_blank');

// 登录提示
function  zm_login_title() {
	return get_bloginfo('name');
}
add_filter('login_headertitle', 'zm_login_title');
// logo url
function custom_loginlogo_url($url) {
	return get_bloginfo('url');
}
add_filter( 'login_headerurl', 'custom_loginlogo_url' );

if (zm_get_option('link_to')) {
// 外链跳转
add_filter('the_content','wl_the_content_to',999);
function wl_the_content_to($content){
	preg_match_all('/href="(http.*?)"/',$content,$matches);
	if($matches){
		foreach($matches[1] as $val){
			 if( strpos($val,home_url())===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$val) && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$val))
			 $content=str_replace("href=\"$val\"", "rel=\"external nofollow\" target=\"_blank\" href=\"" .get_template_directory_uri(). "/go.php?url=" .base64_encode($val). "\"",$content);
		}
 	}
	return $content;
}
}

if (zm_get_option('comment_to')) {
// 评论者链接跳转
add_filter('get_comment_author_link', 'comment_author_link_to');
function comment_author_link_to() {
	$encodeurl = get_comment_author_url( $comment_ID );
	$url = get_template_directory_uri().'/go.php?url=' . base64_encode($encodeurl);
	$author = get_comment_author( $comment_ID );
	if ( empty( $encodeurl ) || 'http://' == $encodeurl )
		return $author;
	else
		return "<a href='$url' target='_blank' rel='external nofollow' class='url'>$author</a>";
}
}

// 网址跳转
function sites_nofollow($url) {
	$url = str_replace($url, get_template_directory_uri()."/go.php?url=".$url,$url);
	return $url;
}

if (zm_get_option('link_external')) {
// 外链nofollow
add_filter('the_content','wl_the_content',999);
function wl_the_content($content){
	preg_match_all('/href="(http.*?)"/',$content,$matches);
	if($matches){
		foreach($matches[1] as $val){
			 if( strpos($val,home_url())===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$val) && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$val))
			 $content=str_replace("href=\"$val\"", "rel=\"external nofollow\" target=\"_blank\" href=\"$val\" ",$content);
 		}
 	}
	return $content;
}
}

// 添加斜杠
function nice_trailingslashit($string, $type_of_url) {
	if ( $type_of_url != 'single' && $type_of_url != 'page' && $type_of_url != 'single_paged' )
		$string = trailingslashit($string);
	return $string;
}
if (zm_get_option('category_x')) {
	add_filter('user_trailingslashit', 'nice_trailingslashit', 10, 2);
}
function html_page_permalink() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
	}
}

// 文章分页
function begin_link_pages() {
	if (zm_get_option('link_pages_all')) {
		wp_link_pages();
	} else {
		wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="be be-arrowleft"></i></span>', 'nextpagelink' => ""));
		wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span class="next-page">', 'link_after'=>'</span>'));
		wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => '<span><i class="be be-arrowright"></i></span> '));
	}
}

function begin_user_contact($user_contactmethods){
	//去掉默认联系方式
	unset($user_contactmethods['aim']);
	unset($user_contactmethods['yim']);
	unset($user_contactmethods['jabber']);

	//添加自定义联系方式
	$user_contactmethods['qq'] = 'QQ';
	$user_contactmethods['weixin'] = '微信';
	$user_contactmethods['weibo'] = '微博';
	$user_contactmethods['phone'] = '电话';

    return $user_contactmethods;
}

// 用户文章
function num_of_author_posts($authorID=''){
	if ($authorID) {
		$author_query = new WP_Query( 'posts_per_page=-1&author='.$authorID );
		$i=0;
		while ($author_query->have_posts()) : $author_query->the_post(); ++$i; endwhile; wp_reset_postdata();
		return $i;
	}
	return false;
}

// 密码提示
function change_protected_title_prefix() {
	return '%s';
}
add_filter('protected_title_format', 'change_protected_title_prefix');

// 评论等级
if (zm_get_option('vip')) {
	function get_author_class($comment_author_email,$user_id){
		global $wpdb;
		$author_count = count($wpdb->get_results(
		"SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
		$adminEmail = get_option('admin_email');if($comment_author_email ==$adminEmail) return;
		if($author_count>=0 && $author_count<2)
			echo '<a class="vip vip0" title="评论达人 VIP.0"><i class="be be-favoriteoutline"></i><span class="lv">0</span></a>';
		else if($author_count>=2 && $author_count<5)
			echo '<a class="vip vip1" title="评论达人 VIP.1"><i class="be be-favorite"></i><span class="lv">1</span></a>';
		else if($author_count>=5 && $author_count<10)
			echo '<a class="vip vip2" title="评论达人 VIP.2"><i class="be be-favorite"></i><span class="lv">2</span></a>';
		else if($author_count>=10 && $author_count<20)
			echo '<a class="vip vip3" title="评论达人 VIP.3"><i class="be be-favorite"></i><span class="lv">3</span></a>';
		else if($author_count>=20 && $author_count<50)
			echo '<a class="vip vip4" title="评论达人 VIP.4"><i class="be be-favorite"></i><span class="lv">4</span></a>';
		else if($author_count>=50 && $author_count<100)
			echo '<a class="vip vip5" title="评论达人 VIP.5"><i class="be be-favorite"></i><span class="lv">5</span></a>';
		else if($author_count>=100 && $author_count<200)
			echo '<a class="vip vip6" title="评论达人 VIP.6"><i class="be be-favorite"></i><span class="lv">6</span></a>';
		else if($author_count>=200 && $author_count<300)
			echo '<a class="vip vip7" title="评论达人 VIP.7"><i class="be be-favorite"></i><span class="lv">7</span></a>';
		else if($author_count>=300 && $author_count<400)
			echo '<a class="vip vip8" title="评论达人 VIP.8"><i class="be be-favorite"></i><span class="lv">8</span></a>';
		else if($author_count>=400)
			echo '<a class="vip vip9" title="评论达人 VIP.9"><i class="be be-favorite"></i><span class="lv">9</span></a>';
	}
}

// admin
function get_author_admin($comment_author_email,$user_id){
	global $wpdb;
	$author_count = count($wpdb->get_results(
	"SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
	$adminEmail = get_option('admin_email');if($comment_author_email ==$adminEmail) echo '<span class="author-admin">Admin</span>';
}

// 自定义图标
class iconfont {
	function __construct(){
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ) );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );
	}
	function nav_menu_css_class( $classes ){
		if( is_array( $classes ) ){
			$tmp_classes = preg_grep( '/^(zm)(-\S+)?$/i', $classes );
			if( !empty( $tmp_classes ) ){
				$classes = array_values( array_diff( $classes, $tmp_classes ) );
			}
		}
		return $classes;
	}

	protected function replace_item( $item_output, $classes ){
		if( !in_array( 'zm', $classes ) ){
			array_unshift( $classes, 'zm' );
		}
		$before = true;
		$icon = '<i class="' . implode( ' ', $classes ) . '"></i>';
		preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
		if( 4 === count( $matches ) ){
			$item_output = $matches[1];
			if( $before ){
				$item_output .= $icon . '<span class="font-text">' . $matches[2] . '</span>';
			} else {
				$item_output .= '<span class="font-text">' . $matches[2] . '</span>' . $icon;
			}
			$item_output .= $matches[3];
		}
		return $item_output;
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
		if( is_array( $item->classes ) ){
			$classes = preg_grep( '/^(zm)(-\S+)?$/i', $item->classes );
			if( !empty( $classes ) ){
				$item_output = $this->replace_item( $item_output, $classes );
			}
		}
		return $item_output;
	}
}
new iconfont();

// 图标
class be_font {
	function __construct(){
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ) );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );
	}
	function nav_menu_css_class( $classes ){
		if( is_array( $classes ) ){
			$tmp_classes = preg_grep( '/^(be)(-\S+)?$/i', $classes );
			if( !empty( $tmp_classes ) ){
				$classes = array_values( array_diff( $classes, $tmp_classes ) );
			}
		}
		return $classes;
	}

	protected function replace_item( $item_output, $classes ){
		if( !in_array( 'be', $classes ) ){
			array_unshift( $classes, 'be' );
		}
		$before = true;
		$icon = '<i class="' . implode( ' ', $classes ) . '"></i>';
		preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
		if( 4 === count( $matches ) ){
			$item_output = $matches[1];
			if( $before ){
				$item_output .= $icon . '<span class="font-text">' . $matches[2] . '</span>';
			} else {
				$item_output .= '<span class="font-text">' . $matches[2] . '</span>' . $icon;
			}
			$item_output .= $matches[3];
		}
		return $item_output;
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
		if( is_array( $item->classes ) ){
			$classes = preg_grep( '/^(be)(-\S+)?$/i', $item->classes );
			if( !empty( $classes ) ){
				$item_output = $this->replace_item( $item_output, $classes );
			}
		}
		return $item_output;
	}
}
new be_font();

// 防冒充管理员
function usercheck($incoming_comment) {
	$isSpam = 0;
	if (trim($incoming_comment['comment_author']) == ''.zm_get_option('admin_name').'')
	$isSpam = 1;
	if (trim($incoming_comment['comment_author_email']) == ''.zm_get_option('admin_email').'')
	$isSpam = 1;
	if(!$isSpam)
	return $incoming_comment;
	err('<i class="be be-info"></i>请勿冒充管理员发表评论！');
}

// 页面添加标签
class PTCFP{
	function __construct(){
	add_action( 'init', array( $this, 'taxonomies_for_pages' ) );
		if ( ! is_admin() ) {
			add_action( 'pre_get_posts', array( $this, 'tags_archives' ) );
		}
	}
	function taxonomies_for_pages() {
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}
	function tags_archives( $wp_query ) {
	if ( $wp_query->get( 'tag' ) )
		$wp_query->set( 'post_type', 'any' );
	}
}
$ptcfp = new PTCFP();

// 分类标签
function get_category_tags($args) {
	global $wpdb;
	$tags = $wpdb->get_results ("
		SELECT DISTINCT terms2.term_id as tag_id, terms2.name as tag_name
		FROM
			$wpdb->posts as p1
			LEFT JOIN $wpdb->term_relationships as r1 ON p1.ID = r1.object_ID
			LEFT JOIN $wpdb->term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
			LEFT JOIN $wpdb->terms as terms1 ON t1.term_id = terms1.term_id,

			$wpdb->posts as p2
			LEFT JOIN $wpdb->term_relationships as r2 ON p2.ID = r2.object_ID
			LEFT JOIN $wpdb->term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
			LEFT JOIN $wpdb->terms as terms2 ON t2.term_id = terms2.term_id
		WHERE
			t1.taxonomy = 'category' AND p1.post_status = 'publish' AND terms1.term_id IN (".$args['categories'].") AND
			t2.taxonomy = 'post_tag' AND p2.post_status = 'publish'
			AND p1.ID = p2.ID
			ORDER by tag_name
	");
	$count = 0;

    if($tags) {
		foreach ($tags as $tag) {
			$mytag[$count] = get_term_by('id', $tag->tag_id, 'post_tag');
			$count++;
		}
	} else {
      $mytag = NULL;
    }
    return $mytag;
}

// 获取当前页面地址
function currenturl() {
	$current_url = home_url(add_query_arg(array()));
	if (is_single()) {
		$current_url = preg_replace('/(\/comment|page|#).*$/','',$current_url);
	} else {
		$current_url = preg_replace('/(comment|page|#).*$/','',$current_url);
	}
	echo $current_url;
}

// 自定义类型面包屑
function begin_taxonomy_terms( $product_id, $taxonomy, $args = array() ) {
    $terms = wp_get_post_terms( $product_id, $taxonomy, $args );
  return apply_filters( 'begin_taxonomy_terms' , $terms, $product_id, $taxonomy, $args );
}

// 子分类
function get_category_id($cat) {
	$this_category = get_category($cat);
	while($this_category->category_parent) {
		$this_category = get_category($this_category->category_parent);
	}
	return $this_category->term_id;
}

// 评论加nofollow
function nofollow_comments_popup_link(){
	return ' rel="external nofollow"';
}

// 图片数量
if( !function_exists('get_post_images_number') ){
	function get_post_images_number(){
		global $post;
		$content = $post->post_content; 
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $result, PREG_PATTERN_ORDER);
		return count($result[1]);
	}
}

// user_only
add_filter('the_content','user_only');
function user_only($text) {
	global $post;
	$user_only = get_post_meta($post->ID,'user_only',true);
	if($user_only) {
		global $user_ID;
		if(!$user_ID) {
			$redirect = urlencode(get_permalink($post->ID));
			$text = '	<div class="reply-read">
		<div class="reply-ts">
			<div class="read-sm"><i class="be be-info"></i>' . sprintf(__( '提示！', 'begin' )) . '</div>
			<div class="read-sm"><i class="be be-loader"></i>' . sprintf(__( '本文登录后方可查看！', 'begin' )) . '</div>
		</div>
		<div class="read-pl"><a href="#login" class="flatbtn show-layer" data-show-layer="login-layer" role="button"><i class="be be-timerauto"></i>' . sprintf(__( '登录', 'begin' )) . '</a></div>
		<div class="clear"></div>
	</div>';
		}
	}
	return $text;
}

// 头部冗余代码
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

// 编辑器增强
function enable_more_buttons($buttons) {
	$buttons[] = 'del';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page';
	$buttons[] = 'backcolor';
	return $buttons;
}
add_filter( "mce_buttons_2", "enable_more_buttons" );

// 禁止代码标点转换
remove_filter( 'the_content', 'wptexturize' );

if (zm_get_option('xmlrpc_no')) {
// 禁用xmlrpc
add_filter('xmlrpc_enabled', '__return_false');
}

// 禁止评论自动超链接
if (zm_get_option('comment_url')) {
remove_filter('comment_text', 'make_clickable', 9);
}
// 禁止评论HTML
if (zm_get_option('comment_html')) {
add_filter('comment_text', 'wp_filter_nohtml_kses');
add_filter('comment_text_rss', 'wp_filter_nohtml_kses');
add_filter('comment_excerpt', 'wp_filter_nohtml_kses');
}

// 链接管理
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// 显示全部设置
function all_settings_link() {
    add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
}
if (zm_get_option('all_settings')) {
add_action('admin_menu', 'all_settings_link');
}
// 屏蔽自带小工具
function unregister_default_wp_widgets() {
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Media_Gallery');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);

// 禁用版本修订
if (zm_get_option('revisions_no')) {
	add_filter( 'wp_revisions_to_keep', 'disable_wp_revisions_to_keep', 10, 2 );
}
function disable_wp_revisions_to_keep( $num, $post ) {
	return 0;
}

// 禁止后台加载谷歌字体
function wp_remove_open_sans_from_wp_core() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'wp_remove_open_sans_from_wp_core' );

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
if (zm_get_option('embed_no')) {
	add_action( 'init', 'disable_embeds_init', 9999 );
}

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

// 禁止dns-prefetch
function remove_dns_prefetch( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		return array_diff( wp_dependencies_unique_hosts(), $hints );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );

// 禁用REST API
if (zm_get_option('disable_api')) {
	add_filter('rest_enabled', '_return_false');
	add_filter('rest_jsonp_enabled', '_return_false');
}

// 移除wp-json链接
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

if (zm_get_option('my_author')) {
// 替换用户链接
add_filter( 'request', 'my_author' );
function my_author( $query_vars ) {
	if ( array_key_exists( 'author_name', $query_vars ) ) {
		global $wpdb;
		$author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='first_name' AND meta_value = %s", $query_vars['author_name'] ) );
		if ( $author_id ) {
			$query_vars['author'] = $author_id;
			unset( $query_vars['author_name'] );
		}
	}
	return $query_vars;
}

add_filter( 'author_link', 'my_author_link', 10, 3 );
function my_author_link( $link, $author_id, $author_nicename ) {
	$my_name = get_user_meta( $author_id, 'first_name', true );
	if ( $my_name ) {
		$link = str_replace( $author_nicename, $my_name, $link );
	}
	return $link;
}
}
// 屏蔽用户名称类
function remove_comment_body_author_class( $classes ) {
	foreach( $classes as $key => $class ) {
	if(strstr($class, "comment-author-")||strstr($class, "author-")) {
			unset( $classes[$key] );
		}
	}
	return $classes;
}

// 最近更新过
function recently_updated_posts($num=10,$days=7) {
	if( !$recently_updated_posts = get_option('recently_updated_posts') ) {
		query_posts('post_status=publish&orderby=modified&posts_per_page=-1');
		$i=0;
		while ( have_posts() && $i<$num ) : the_post();
			if (current_time('timestamp') - get_the_time('U') > 60*60*24*$days) {
				$i++;
				$the_title_value=get_the_title();
				$recently_updated_posts.='<li><a href="'.get_permalink().'" title="'.$the_title_value.'">'
				.$the_title_value.'</a></li>';
			}
		endwhile;
		wp_reset_query();
		if ( !empty($recently_updated_posts) ) update_option('recently_updated_posts', $recently_updated_posts);
	}
	$recently_updated_posts=($recently_updated_posts == '') ? '<li>目前没有文章被更新</li>' : $recently_updated_posts;
	echo $recently_updated_posts;
}

function clear_cache_recently() {
	update_option('recently_updated_posts', '');
}
add_action('save_post', 'clear_cache_recently');

// code button
if (zm_get_option('gcp_code')) {require get_template_directory() . '/inc/code/code-button.php';}

// shortcode
if (in_array($pagenow, array('post.php', 'post-new.php', 'page.php', 'page-new.php'))) {
	add_action('admin_head', 'tab_code_plugin');
	add_action('admin_head', 'lists_code_plugin');
	add_action('admin_head', 'begin_add_mce_button');
}

// edd custom-fields
if (zm_get_option('edd')) {
$download_args = array('supports' => apply_filters( 'edd_download_supports', array( 'custom-fields') ),);
register_post_type( 'download', apply_filters( 'edd_download_post_type_args', $download_args ) );
}

// 注册时间
function user_registered(){
	$userinfo=get_userdata(get_current_user_id());
	$authorID= $userinfo->ID;
	$user = get_userdata( $authorID );
	$registered = $user->user_registered;
	echo '' . date( "" . sprintf(__( 'Y年m月d日', 'begin' )) . "", strtotime( $registered ) );
}

// 文章归档更新
function begin_clear_archives() {
	update_option('cx_archives_list', '');
	update_option('up_archives_list', '');
}

// 登录时间
function begin_user_last_login($user_login) {
	global $user_ID;
	date_default_timezone_set('PRC');
	$user = get_user_by( 'login', $user_login );
	update_user_meta($user->ID, 'last_login', date('Y-m-d H:i:s'));
}

function get_last_login($user_id) {
	$last_login = get_user_meta($user_id, 'last_login', true);
	$date_format = get_option('date_format') . ' ' . get_option('time_format');
	$the_last_login = mysql2date($date_format, $last_login, false);
	echo $the_last_login;
}

// 登录角色
function get_user_role() {
	global $current_user;
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	return $user_role;
}

// 读者排行
function top_comment_authors($amount = 98) {
	global $wpdb;
		$prepared_statement = $wpdb->prepare(
		'SELECT
		COUNT(comment_author) AS comments_count, comment_author, comment_author_url, comment_author_email, MAX( comment_date ) as last_commented_date
		FROM '.$wpdb->comments.'
		WHERE comment_author != "" AND comment_type = "" AND comment_approved = 1  AND user_id = ""
		GROUP BY comment_author
		ORDER BY comments_count DESC, comment_author ASC
		LIMIT %d',
		$amount);
	$results = $wpdb->get_results($prepared_statement);
	$output = '<div class="top-comments">';
	foreach($results as $result) {
		$c_url = $result->comment_author_url;
		$output .= '<div class="lx8"><div class="top-author ms">';
			if (zm_get_option('cache_avatar')) {
				$output .= '<div class="top-comment"><a href="' . $c_url . '" target="_blank" rel="external nofollow">' . begin_avatar($result->comment_author_email, 128) . '<div class="author-url"><strong> ' . $result->comment_author . '</div></strong></a></div>';
			} else {
				$output .= '<div class="top-comment"><a href="' . $c_url . '" target="_blank" rel="external nofollow">' . get_avatar($result->comment_author_email, 128) . '<div class="author-url"><strong> ' . $result->comment_author . '</div></strong></a></div>';
			}
		$output .= '<div class="top-comment">'.$result->comments_count.'条留言</div><div class="top-comment">最后' . human_time_diff(strtotime($result->last_commented_date)) . '前</div></div></div>';
	}
	$output .= '<div class="clear"></div></div>';
	echo $output;
}

// meta_delete
if (zm_get_option('meta_delete')) {} else {require get_template_directory() . '/inc/meta-delete.php';}
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/show-meta.php';
// 邀请码
if (zm_get_option('invitation_code')) { require get_template_directory() . '/inc/invitation-code.php'; }

// 分类ID
function show_id() {
	global $wpdb;
	$request = "SELECT $wpdb->terms.term_id, name FROM $wpdb->terms ";
	$request .= " LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ";
	$request .= " WHERE $wpdb->term_taxonomy.taxonomy = 'category' ";
	$request .= " ORDER BY term_id asc";
	$categorys = $wpdb->get_results($request);
	foreach ($categorys as $category) { 
		$output = '<ol class="show-id">' . $category->name . '<span>' . $category->term_id . '</span></ol>';
		echo $output;
	}
}

function search_cat(){
	$categories = get_categories();
	foreach ($categories as $cat) {
	$output = '<option value="'.$cat->cat_ID.'">'.$cat->cat_name.'</option>';
		echo $output;
	}

	// $categories = get_categories(array('taxonomy' => 'gallery'));
	// foreach ($categories as $cat) {
	// $output = '<option value="'.$cat->cat_ID.'">'.$cat->cat_name.'</option>';
	// echo $output;
	// }
}

// 热评文章
function hot_comment_viewed($number, $days){
	global $wpdb;
	$sql = "SELECT ID , post_title , comment_count
			FROM $wpdb->posts
			WHERE post_type = 'post' AND post_status = 'publish' AND TO_DAYS(now()) - TO_DAYS(post_date) < $days
			ORDER BY comment_count DESC LIMIT 0 , $number ";
	$posts = $wpdb->get_results($sql);
	$i = 1;
	$output = "";
	foreach ($posts as $post){
		$output .= "\n<li><span class='li-icon li-icon-$i'>$i</span><a href= \"".get_permalink($post->ID)."\" rel=\"bookmark\" title=\" (".$post->comment_count."条评论)\" >".$post->post_title."</a></li>";
		$i++;
	}
	echo $output;
}

// 历史今天
function begin_today(){
	global $wpdb;
	$post_year = get_the_time('Y');
	$post_month = get_the_time('m');
	$post_day = get_the_time('j');
	$sql = "select ID, year(post_date_gmt) as today_year, post_title, comment_count FROM 
			$wpdb->posts WHERE post_password = '' AND post_type = 'post' AND post_status = 'publish'
			AND year(post_date_gmt)!='$post_year' AND month(post_date_gmt)='$post_month' AND day(post_date_gmt)='$post_day'
			order by post_date_gmt DESC limit 8";
	$histtory_post = $wpdb->get_results($sql);
	if( $histtory_post ){
		foreach( $histtory_post as $post ){
			$today_year = $post->today_year;
			$today_post_title = $post->post_title;
			$today_permalink = get_permalink( $post->ID );
			// $today_comments = $post->comment_count;
			$today_post .= '<li><a href="'.$today_permalink.'" title="'.$today_post_title.'" target="_blank"><span>'.$today_year.'</span>'.$today_post_title.'</a></li>';
		}
	}
	if ( $today_post ){
		$result = '<div class="begin-today"><fieldset><legend><h5>'. sprintf(__( '历史上的今天', 'begin' )) .'</h5></legend><div class="today-date"><div class="today-m">'.get_the_date( 'F' ).'</div><div class="today-d">'.get_the_date( 'j' ).'</div></div><ul>'.$today_post.'</ul></fieldset></div>';
	}
	return $result;
}

// menu description
function begin_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'navigation' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-des">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}
	return $item_output;
}
if (zm_get_option('menu_des')) {
add_filter( 'walker_nav_menu_start_el', 'begin_nav_description', 10, 4 );
}

// menu post
function cat_content() {
	global $post;
	$cat_content_output = '';
	$cat_post_query = new WP_Query( array ( 'meta_key' => 'menu_post', 'ignore_sticky_posts' => 1 )  );
	if( $cat_post_query->have_posts() ) {
		$cat_content_output .= '<div class="cat-con-section">';
		while( $cat_post_query->have_posts() ) {
			$cat_post_query->the_post();
			if ( get_post_meta($post->ID, 'thumbnail', true) ) {
				$thumbnail  = get_post_meta(get_the_ID(),'thumbnail',true);
				$thumb_img =  '<img src="'.$thumbnail.'">';
			} else {
				$content = $post->post_content;
				preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
				$thumb_img = '<img src="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /><div class="clear"></div>';
			}
			$cat_content_output .= '<div class="menu-post-block"><a href="'.get_the_permalink().'">'.$thumb_img.'</a><h3><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3></div>';
		}
		$cat_content_output .= '</div>';
		wp_reset_postdata();
	} else {
		$cat_content_output .= '<div class="cat-con-section"><div class="menu-post-block"><h3>编辑文章在“将文章添加到”面板中，勾选“菜单图文”，将指定文章添加到此模块中。</h3></div></div>';
	}
	return $cat_content_output;
}

function add_custom_post($items, $args) {
	$custom_items = '<li class="menu-img-box"><a href="#"><i class="'.zm_get_option('menu_post_ico').'"></i>'.zm_get_option('menu_post_t').'</a><ul class="menu-img"><li>'. cat_content( $mega_category ) . '</li></ul></li>';
	if( $args->theme_location == 'navigation') {
		return $items.$custom_items;
	}
	return $items;
}

if (zm_get_option('menu_post')) {
	add_filter('wp_nav_menu_items', 'add_custom_post', 10, 2);
}
// custum font
function custum_font_family($initArray){
   $initArray['font_formats'] = "微软雅黑='微软雅黑';华文彩云='华文彩云';华文行楷='华文行楷';华文琥珀='华文琥珀';华文新魏='华文新魏';华文中宋='华文中宋';华文仿宋='华文仿宋';华文楷体='华文楷体';华文隶书='华文隶书';华文细黑='华文细黑';宋体='宋体';仿宋='仿宋';黑体='黑体';隶书='隶书';幼圆='幼圆'";
   return $initArray;
}
// section_bg
function carousel_bg(){
echo'<style>#section-gtg {background: url('.zm_get_option('carousel_bg_img').') no-repeat;background-position: center center;background-size: cover;width: 100%;background-attachment: fixed;}</style>';}
function service_bg(){
echo'<style>#service-bg {background: url('.zm_get_option('service_bg_img').') no-repeat;background-position: center center;background-size: cover;width: 100%;background-attachment: fixed;}</style>';}

 // 删除文章菜单
function remove_menus(){
	remove_menu_page( 'edit.php?post_type=bulletin' );// 公告
	remove_menu_page( 'edit.php?post_type=picture' );// 图片* 
	remove_menu_page( 'edit.php?post_type=video' );// 视频
	remove_menu_page( 'edit.php?post_type=tao' );// 商品
	remove_menu_page( 'edit.php?post_type=sites' );// 网址
	remove_menu_page( 'edit.php?post_type=show' );// 产品
	remove_menu_page( 'link-manager.php' );// 链接
	remove_menu_page( 'upload.php' );//媒体
	remove_menu_page( 'edit-comments.php' );// 评论
	remove_menu_page( 'tools.php' );// 工具
}

function disable_create_newpost() {
	global $wp_post_types;
if (zm_get_option('no_bulletin')) {
	$wp_post_types['bulletin']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_gallery')) {
	$wp_post_types['picture']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_videos')) {
	$wp_post_types['video']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_tao')) {
	$wp_post_types['tao']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_favorites')) {
	$wp_post_types['sites']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_products')) {
	$wp_post_types['show']->cap->create_posts = 'do_not_allow';
}
}

if (zm_get_option('no_type')) {
	if ($current_user->user_level < zm_get_option('user_level')) { // 作者及投稿者不可见
		add_action( 'admin_menu', 'remove_menus' );
		add_action('init','disable_create_newpost');
	}
}

// 复制提示
function zm_copyright_tips() {
	echo '<script>document.body.oncopy=function(){alert("复制成功！转载请务必保留原文链接，申明来源，谢谢合作！");}</script>';
}

// sitemap_xml
if (zm_get_option('sitemap_xml')) {
function begin_sitemap_refresh() {
	require_once get_template_directory() . '/inc/sitemap-xml.php';
	$sitemap_xml = begin_get_xml_sitemap();
	file_put_contents(ABSPATH.'sitemap.xml', $sitemap_xml);

	// require_once get_template_directory() . '/inc/sitemap-html.php';
	// $sitemap_html = begin_get_html_sitemap();
	// file_put_contents(ABSPATH.'sitemap.html', $sitemap_html);
}

if ( defined('ABSPATH') ) {
	add_action('publish_post', 'begin_sitemap_refresh');
	add_action('save_post', 'begin_sitemap_refresh');
	add_action('edit_post', 'begin_sitemap_refresh');
	add_action('delete_post', 'begin_sitemap_refresh');
}
}
// 显示全部分类
add_filter( 'widget_categories_args', 'show_empty_cats' );
function show_empty_cats($cat_args) {
	$cat_args['hide_empty'] = 0;
	return $cat_args;
}
// 上传头像
if (zm_get_option('local_avatars')) {
$begin_local_avatars = new Begin_Local_Avatars;
}

// 登录跳转
function register_auto_login( $user_id ) {
	wp_set_current_user($user_id);
	wp_set_auth_cookie($user_id);
	wp_redirect( home_url() ); 
	exit;
}
if (zm_get_option('reg_home')) {
add_action( 'user_register', 'register_auto_login');
}

// 登录注册时间
if (zm_get_option('last_login')) {
add_action( 'wp_login', 'insert_last_login' );
function insert_last_login( $login ) {
	global $user_id;
	$user = get_userdatabylogin( $login );
	update_user_meta( $user->ID, 'last_login', current_time( 'mysql' ) );
}

add_filter('manage_users_columns', 'add_user_additional_column');
function add_user_additional_column($columns) {
	$columns['user_nickname'] = '昵称';
	$columns['user_url'] = '网站';
	$columns['reg_time'] = '注册时间';
	$columns['last_login'] = '上次登录';
	return $columns;
}

add_action('manage_users_custom_column',  'show_user_additional_column_content', 10, 3);
function show_user_additional_column_content($value, $column_name, $user_id) {
	$user = get_userdata( $user_id );
	if ( 'user_nickname' == $column_name )
		return $user->nickname;
	if ( 'user_url' == $column_name )
		return '<a href="'.$user->user_url.'" target="_blank">'.$user->user_url.'</a>';
	if('reg_time' == $column_name ){
		return get_date_from_gmt($user->user_registered) ;
	}
	if ( 'last_login' == $column_name && $user->last_login ){
		return get_user_meta( $user->ID, 'last_login', true );
	}
	return $value;
}

add_filter( "manage_users_sortable_columns", 'cmhello_users_sortable_columns' );
function cmhello_users_sortable_columns($sortable_columns){
	$sortable_columns['reg_time'] = 'reg_time';
	return $sortable_columns;
}

add_action( 'pre_user_query', 'cmhello_users_search_order' );
function cmhello_users_search_order($obj){
	if(!isset($_REQUEST['orderby']) || $_REQUEST['orderby']=='reg_time' ){
		if( !in_array($_REQUEST['order'],array('asc','desc')) ){
			$_REQUEST['order'] = 'desc';
		}
		$obj->query_orderby = "ORDER BY user_registered ".$_REQUEST['order']."";
	}
}
}
// 支持中文用户名
function zm_sanitize_user ($username, $raw_username, $strict) {
	$username = wp_strip_all_tags( $raw_username );
	$username = remove_accents( $username );
	$username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
	$username = preg_replace( '/&.+?;/', '', $username );
	if ($strict) {
		$username = preg_replace ('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
	}
	$username = trim( $username );
	$username = preg_replace( '|\s+|', ' ', $username );

	return $username;
}

// 隐藏后台标题中的“WordPress”
add_filter('admin_title', 'zm_custom_admin_title', 10, 2);
	function zm_custom_admin_title($admin_title, $title){
		return $title.' &lsaquo; '.get_bloginfo('name');
}
add_filter('login_title', 'zm_custom_login_title', 10, 2);
	function zm_custom_login_title($login_title, $title){
		return $title.' &lsaquo; '.get_bloginfo('name');
}
// 隐藏左上角WordPress标志
function hidden_admin_bar_remove() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'hidden_admin_bar_remove', 0);

// 移除隐私功能
add_action('admin_menu', function () {
	global $menu, $submenu;
	unset($submenu['options-general.php'][45]);
	remove_action( 'admin_menu', '_wp_privacy_hook_requests_page' );
},9);

// disable wp image sizes
function begin_customize_image_sizes( $sizes ){
	unset( $sizes[ 'thumbnail' ]);
	unset( $sizes[ 'medium' ]);
	unset( $sizes[ 'medium_large' ] );
	unset( $sizes[ 'large' ]);
	unset( $sizes[ 'full' ] );
	return $sizes;
}
// post type link
if (zm_get_option('begin_types_link')) {
require get_template_directory() . '/inc/types-permalink.php';
}
// 评论 Cookie
if (zm_get_option('comment_ajax') == '' ) {
	add_action('set_comment_cookies','coffin_set_cookies',10,3);
}
function coffin_set_cookies( $comment, $user, $cookies_consent){
	$cookies_consent = true;
	wp_set_comment_cookies($comment, $user, $cookies_consent);
}

// qq info
if (zm_get_option('qq_info')) {
function generate_code($length = 3) {
	return rand(pow(10,($length-1)), pow(10,$length)-1);
}
}
// 获取首字母
function getFirstCharter($str){
	if(empty($str)){
		return '';
	}
	if(is_numeric($str{0})) return $str{0};// 如果是数字开头 则返回数字
	$fchar=ord($str{0});
	if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0}); //如果是字母则返回字母的大写
	$s1=iconv('UTF-8','gb2312',$str);
	$s2=iconv('gb2312','UTF-8',$s1);
	$s=$s2==$str?$s1:$str;
	$asc=ord($s{0})*256+ord($s{1})-65536;
	if($asc>=-20319&&$asc<=-20284) return 'A';
	if($asc>=-20283&&$asc<=-19776) return 'B';
	if($asc>=-19775&&$asc<=-19219) return 'C';
	if($asc>=-19218&&$asc<=-18711) return 'D';
	if($asc>=-18710&&$asc<=-18527) return 'E';
	if($asc>=-18526&&$asc<=-18240) return 'F';
	if($asc>=-18239&&$asc<=-17923) return 'G';
	if($asc>=-17922&&$asc<=-17418) return 'H';
	if($asc>=-17417&&$asc<=-16475) return 'J';
	if($asc>=-16474&&$asc<=-16213) return 'K';
	if($asc>=-16212&&$asc<=-15641) return 'L';
	if($asc>=-15640&&$asc<=-15166) return 'M';
	if($asc>=-15165&&$asc<=-14923) return 'N';
	if($asc>=-14922&&$asc<=-14915) return 'O';
	if($asc>=-14914&&$asc<=-14631) return 'P';
	if($asc>=-14630&&$asc<=-14150) return 'Q';
	if($asc>=-14149&&$asc<=-14091) return 'R';
	if($asc>=-14090&&$asc<=-13319) return 'S';
	if($asc>=-13318&&$asc<=-12839) return 'T';
	if($asc>=-12838&&$asc<=-12557) return 'W';
	if($asc>=-12556&&$asc<=-11848) return 'X';
	if($asc>=-11847&&$asc<=-11056) return 'Y';
	if($asc>=-11055&&$asc<=-10247) return 'Z';
	return null;
}

// 禁用网站健康检测
function prefix_remove_site_health( $tests ) {
	unset( $tests['direct']['php_version'] );
	unset( $tests['direct']['wordpress_version'] );
	unset( $tests['direct']['plugin_version'] );
	unset( $tests['direct']['theme_version'] );
	unset( $tests['direct']['sql_server'] );
	unset( $tests['direct']['php_extensions'] );
	unset( $tests['direct']['utf8mb4_support'] );
	unset( $tests['direct']['https_status'] );
	unset( $tests['direct']['ssl_support'] );
	unset( $tests['direct']['scheduled_events'] );
	unset( $tests['direct']['http_requests'] );
	unset( $tests['direct']['is_in_debug_mode'] );
	unset( $tests['direct']['dotorg_communication'] );
	unset( $tests['direct']['background_updates'] );
	unset( $tests['direct']['loopback_requests'] );
	unset( $tests['direct']['rest_availability'] );
	return $tests;
}

if (zm_get_option('remove_sitehealth')) {
	add_filter( 'site_status_tests', 'prefix_remove_site_health' );
}

// 禁用错误处理程序
if (zm_get_option('disable_error_handler')) {
	add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
}

// 打开缓冲区
add_action('init', 'do_output_buffer');
	function do_output_buffer() {
	ob_start();
}
// 禁用评论
function close_comments( $posts ) {
	$postids = array('','');
	if ( !empty( $posts ) && is_singular() && !in_array($posts[0]->ID, $postids) ) {
		$posts[0]->comment_status = 'closed';
		$posts[0]->post_status = 'closed';
	}
	return $posts;
}
if (zm_get_option('close_comments')) {
	add_filter( 'the_posts', 'close_comments' );
}

// 登录震动
function wps_login_error() {
	remove_action('login_head', 'wp_shake_js', 12);
}
add_action('login_head', 'wps_login_error');

// 禁用响应图片
add_filter( 'wp_calculate_image_srcset_meta', '__return_false' );

// 删除特色图像
if (zm_get_option('delete_thumbnail')) {
	delete_post_meta_by_key( '_thumbnail_id' );
}

// 修改登录链接
function login_protect(){
	if($_GET[''.zm_get_option('pass_h').''] != ''.zm_get_option('word_q').'')header('Location: '.zm_get_option('go_link').'');// 忘了删除
}