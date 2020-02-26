<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (is_single()) { ?>
<?php if ( get_post_meta($post->ID, 'description', true) ) : ?>
<meta property="og:description" content="<?php $description = get_post_meta($post->ID, 'description', true);{echo $description;}?>">
<?php else: ?>
<meta property="og:description" content="<?php echo zm_og_excerpt(); ?>">
<?php endif; ?>
<meta property="og:type" content="acticle">
<meta property="og:locale" content="<?php echo get_bloginfo( 'language' ); ?>" />
<meta property="og:site_name" content="<?php bloginfo('name'); ?>">
<meta property="og:title" content="<?php the_title();?>">
<meta property="og:url" content="<?php the_permalink();?>"/> 
<meta property="og:image" content="<?php echo og_post_img();?>">
<?php } ?>
<?php 
function zm_og_excerpt($len=220){
	if ( is_single() || is_page() ){
		global $post;
		if ($post->post_excerpt) {
			$excerpt  = $post->post_excerpt;
		} else {
			if(preg_match('/<p>(.*)<\/p>/iU',trim(strip_tags($post->post_content,"<p>")),$result)){
				$post_content = $result['1'];
			} else {
				$post_content_r = explode("\n",trim(strip_tags($post->post_content)));
				$post_content = $post_content_r['0'];
			}
			$excerpt = preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,0}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s','$1',$post_content);
		}
		return str_replace(array("\r\n", "\r", "\n"), "", $excerpt);
	}
}

function og_post_img(){
	global $post;
	$content = $post->post_content;  
	preg_match_all('/<img .*?src=[\"|\'](.+?)[\"|\'].*?>/', $content, $strResult, PREG_PATTERN_ORDER); 
	$n = count($strResult[1]);  
	if($n >= 3){
		$src = $strResult[1][0];
	}else{
		if( $values = get_post_custom_values("thumbnail") ) {
			$values = get_post_custom_values("thumbnail");
			$src = $values [0];
		} elseif( has_post_thumbnail() ){
			$thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
			$src = $thumbnail_src [0];
		} else {
			if($n > 0){
				$src = $strResult[1][0];
			} 
		}
	}
	return $src;
}