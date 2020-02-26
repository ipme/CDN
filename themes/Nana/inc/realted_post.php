<div class="tab-site">
	<div id="layout-tab">
		<div class="tit">
        <span class="name"><i class="fas fa-bookmark"></i>&nbsp;相关文章</span>
            <span class="plxiaoshi"><span class="keyword">
            	<i class="fas fa-tags"></i>&nbsp;关键词：<?php the_tags('', '', ''); ?>
            </span></span>
        </div>
		<ul class="tab-bd">
		<?php
		$post_num = get_option('ygj_related_count');
global $post;
$exclude_id = $post->ID;
$post_tags = wp_get_post_tags($post->ID); 
$i = 0;
$tag_list[]='';
if ($post_tags) {
  foreach ($post_tags as $tag) {
    $tag_list[] .= $tag->term_id;
  }
  $args = array(
        'tag__in' => $tag_list,
        'post__not_in' => explode(',', $exclude_id),
        'showposts' => $post_num, 
        'ignore_sticky_posts' => 1
    );
  query_posts($args);
  if (have_posts()) {
    while (have_posts()) {
      the_post(); update_post_caches($posts); ?>
    <li><span class="post_spliter">•</span><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></li>
<?php
$exclude_id .= ',' . $post->ID;
    $i += 1;}
  }
 wp_reset_query();
}
if ( $i < $post_num ) {
    $cats = '';$post_num -= $i;
    foreach (get_the_category() as $cat) $cats.= $cat->cat_ID . ',';
    $args = array(
        'category__in' => explode(',', $cats) ,
        'post__not_in' => explode(',', $exclude_id) ,
        'showposts' => $post_num,
		'ignore_sticky_posts' => 1		
    );
    query_posts($args);
    while (have_posts()) {
        the_post(); ?>
    <li><span class="post_spliter">•</span><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></li>
<?php
    };
    wp_reset_query();
}
wp_reset_query();
?>
		</ul>
	</div>
</div>
