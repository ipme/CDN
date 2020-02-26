<?php
/*
Template Name: TAG页面模板
*/
?>
<?php ini_set('display_errors', false);get_header();?>
<style type="text/css">
#all_tags li {
    margin: 0 10px;
}
</style>
<div id="content" class="site-content">	
<div class="clear"></div>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php while ( have_posts() ) : the_post(); ?>
			
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>	
		<div class="single_info">
					<span class="date"><?php the_time( 'Y-m-d H:i' ) ?></span>
					<span class="views"><?php if( function_exists( 'the_views' ) ) { the_views(); print '人阅读 '; } ?></span>			
					<span class="edit"><?php edit_post_link('编辑', '  ', '  '); ?></span>
				</div>			
	</header><!-- .entry-header -->

	<div class="entry-content">
					<div class="single-content">									
	<?php the_content(); ?>
	<?php 
function specs_getfirstchar($s0){
	$fchar = ord($s0{0});
	if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
	$s1 = iconv("UTF-8","gb2312", $s0);
	$s2 = iconv("gb2312","UTF-8", $s1);
	if($s2 == $s0){$s = $s1;}else{$s = $s0;}
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	if($asc >= -20319 and $asc <= -20284) return "A";
	if($asc >= -20283 and $asc <= -19776) return "B";
	if($asc >= -19775 and $asc <= -19219) return "C";
	if($asc >= -19218 and $asc <= -18711) return "D";
	if($asc >= -18710 and $asc <= -18527) return "E";
	if($asc >= -18526 and $asc <= -18240) return "F";
	if($asc >= -18239 and $asc <= -17923) return "G";
	if($asc >= -17922 and $asc <= -17418) return "H";
	if($asc >= -17417 and $asc <= -16475) return "J";
	if($asc >= -16474 and $asc <= -16213) return "K";
	if($asc >= -16212 and $asc <= -15641) return "L";
	if($asc >= -15640 and $asc <= -15166) return "M";
	if($asc >= -15165 and $asc <= -14923) return "N";
	if($asc >= -14922 and $asc <= -14915) return "O";
	if($asc >= -14914 and $asc <= -14631) return "P";
	if($asc >= -14630 and $asc <= -14150) return "Q";
	if($asc >= -14149 and $asc <= -14091) return "R";
	if($asc >= -14090 and $asc <= -13319) return "S";
	if($asc >= -13318 and $asc <= -12839) return "T";
	if($asc >= -12838 and $asc <= -12557) return "W";
	if($asc >= -12556 and $asc <= -11848) return "X";
	if($asc >= -11847 and $asc <= -11056) return "Y";
	if($asc >= -11055 and $asc <= -10247) return "Z";
	return null;
}
function specs_pinyin($zh){
	$ret = "";
	$i=0;
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    $s1 = substr($zh,$i,1);
    $p = ord($s1);
    if($p > 160){
        $s2 = substr($zh,$i++,2);
        $ret .= specs_getfirstchar($s2);
    }else{
        $ret .= $s1;
    }
	return strtoupper($ret);
}

function specs_show_tags() {
	$output='';
		$categories = get_terms( 'post_tag', array(
			'orderby'    => 'count',
			'hide_empty' => 1
		 ) );
                $r = array();
		foreach($categories as $v){
			for($i = 65; $i <= 90; $i++){
				if(specs_pinyin($v->name) == chr($i)){
					$r[chr($i)][] = $v;
				}
			}
			for($i=48;$i<=57;$i++){
				if(specs_pinyin($v->name) == chr($i)){
					$r[chr($i)][] = $v;
				}
			}
		}
		ksort($r);
		$output = "<ul class='list-inline' id='tag_letter'>";
		for($i=65;$i<=90;$i++){
			$tagi = $r[chr($i)];
			if(is_array($tagi)){
				$output .= "<li><a href='#".chr($i)."'>".chr($i)."</a></li>";
			}else{
				$output .= "<li>".chr($i)."</li>";
			}
		}
		for($i=48;$i<=57;$i++){
			$tagi = $r[chr($i)];
			if(is_array($tagi)){
				$output .= "<li><a href='#".chr($i)."'>".chr($i)."</a></li>";
			}else{
				$output .= "<li>".chr($i)."</li>";
			}
		}
		$output .= "</ul>";
		$output .= "<ul id='all_tags' class='list-unstyled'>";
		for($i=65;$i<=90;$i++){
			$tagi = $r[chr($i)];
			if(is_array($tagi)){
				$output .= "<li id='".chr($i)."'><h4 class='tag_name'>".chr($i)."</h4>";
				foreach($tagi as $tag){
					$output .= "<a href='".get_tag_link($tag->term_id)."'>".$tag->name."(".$tag->count.")</a>";
				}
			}
		}
		for($i=48;$i<=57;$i++){
			$tagi = $r[chr($i)];
			if(is_array($tagi)){
				$output .= "<li id='".chr($i)."'><h4 class='tag_name'>".chr($i)."</h4>";
				foreach($tagi as $tag){
					$output .= "<a href='".get_tag_link($tag->term_id)."'>".$tag->name."(".$tag->count.")</a>";
				}
			}
		}
		$output .= "</ul>";
    echo $output;
}
?>
<?php specs_show_tags(); ?>
			</div>
<div class="clear"></div>
<?php get_template_part( 'inc/social' ); ?>
				<div class="clear"></div>
	</div><!-- .entry-content -->

	</article><!-- #post -->
<?php if (get_option('ygj_taobaoym') == '显示') { get_template_part('/inc/taobao'); } ?>	
	<?php if (get_option('ygj_g_full') == '显示') { get_template_part( 'inc/ad/ad_full' ); } ?>															
	<?php comments_template( '', true ); ?>			
			<?php endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<div class="clear"></div>
</div><!-- .site-content -->
<?php get_footer();?>