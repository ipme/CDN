<?php 
function begin_get_xml_sitemap() {
	ob_start();
	echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">
<!-- generated-on=<?php echo get_lastpostdate('blog'); ?> -->
<url>
<loc><?php echo get_home_url(); ?></loc>
<lastmod><?php echo gmdate('Y-m-d\TH:i:s+00:00', strtotime(get_lastpostmodified('GMT'))); ?></lastmod>
<changefreq>daily</changefreq>
<priority>1.0</priority>
</url>
<?php 
	// 文章
	$posts = get_posts('numberposts=-1&orderby=post_date&order=DESC');
	foreach($posts as $post) : 
?>
<url>
<loc><?php echo get_permalink($post->ID); ?></loc>
<lastmod><?php echo str_replace(" ", "T", get_post($post->ID)->post_modified); ?>+00:00</lastmod>
<changefreq>monthly</changefreq>
<priority>0.6</priority>
</url>
<?php 
	endforeach;
	// 公告
	$posts = get_posts('post_type=bulletin&numberposts=-1&orderby=post_date&order=DESC');
	foreach($posts as $post) : 
?>
<?php if (zm_get_option('no_bulletin')) { ?>
<url>
<loc><?php echo get_permalink($post->ID); ?></loc>
<lastmod><?php echo str_replace(" ", "T", get_post($post->ID)->post_modified); ?>+00:00</lastmod>
<changefreq>monthly</changefreq>
<priority>0.6</priority>
</url>
<?php } ?>
<?php 
	endforeach;
	// 图片
	$posts = get_posts('post_type=picture&numberposts=-1&orderby=post_date&order=DESC');
	foreach($posts as $post) : 
?>
<?php if (zm_get_option('no_gallery')) { ?>
<url>
<loc><?php echo get_permalink($post->ID); ?></loc>
<lastmod><?php echo str_replace(" ", "T", get_post($post->ID)->post_modified); ?>+00:00</lastmod>
<changefreq>monthly</changefreq>
<priority>0.6</priority>
</url>
<?php } ?>
<?php 
	endforeach;
	// 视频
	$posts = get_posts('post_type=video&numberposts=-1&orderby=post_date&order=DESC');
	foreach($posts as $post) : 
?>
<?php if (zm_get_option('no_videos')) { ?>
<url>
<loc><?php echo get_permalink($post->ID); ?></loc>
<lastmod><?php echo str_replace(" ", "T", get_post($post->ID)->post_modified); ?>+00:00</lastmod>
<changefreq>monthly</changefreq>
<priority>0.6</priority>
</url>
<?php } ?>
<?php 
	endforeach;
	// 商品
	$posts = get_posts('post_type=tao&numberposts=-1&orderby=post_date&order=DESC');
	foreach($posts as $post) : 
?>
<?php if (zm_get_option('no_tao')) { ?>
<url>
<loc><?php echo get_permalink($post->ID); ?></loc>
<lastmod><?php echo str_replace(" ", "T", get_post($post->ID)->post_modified); ?>+00:00</lastmod>
<changefreq>monthly</changefreq>
<priority>0.6</priority>
</url>
<?php } ?>
<?php 
	endforeach;
	// 产品
	$posts = get_posts('post_type=show&numberposts=-1&orderby=post_date&order=DESC');
	foreach($posts as $post) : 
?>
<?php if (zm_get_option('no_products')) { ?>
<url>
<loc><?php echo get_permalink($post->ID); ?></loc>
<lastmod><?php echo str_replace(" ", "T", get_post($post->ID)->post_modified); ?>+00:00</lastmod>
<changefreq>monthly</changefreq>
<priority>0.6</priority>
</url>
<?php } ?>
<?php 
	endforeach;
	// 页面
	$pages = get_pages('numberposts=-1&orderby=post_date&order=DESC');
	foreach($pages as $page) :
?>
<url>
<loc><?php echo get_page_link($page->ID); ?></loc>
<lastmod><?php echo str_replace(" ", "T", get_page($page->ID)->post_modified); ?>+00:00</lastmod>
<changefreq>weekly</changefreq>
<priority>0.6</priority>
</url>
<?php 
	endforeach;
	// 分类
	$categorys = get_terms('category', 'orderby=name&hide_empty=0');
	foreach ($categorys as $category) : 
?>
<url>
<loc><?php echo get_term_link($category, $category->slug); ?></loc>
<changefreq>weekly</changefreq>
<priority>0.8</priority>
</url>
<?php 
	endforeach;
	// 标签
	$tags = get_terms('post_tag', 'orderby=name&hide_empty=0');
	foreach ($tags as $tag) : 
?>
<url>
<loc><?php echo get_term_link($tag, $tag->slug); ?></loc>
<changefreq>monthly</changefreq>
<priority>0.4</priority>
</url>
<?php 
	endforeach;
	// 公告分类
	$categorys = get_terms('notice', 'orderby=name&hide_empty=0');
	foreach ($categorys as $category) : 
?>
<?php if (zm_get_option('no_bulletin')) { ?>
<url>
<loc><?php echo get_term_link($category, $category->slug); ?></loc>
<changefreq>weekly</changefreq>
<priority>0.8</priority>
</url>
<?php } ?>
<?php 
	endforeach;
	// 图片分类
	$categorys = get_terms('gallery', 'orderby=name&hide_empty=0');
	foreach ($categorys as $category) : 
?>
<?php if (zm_get_option('no_gallery')) { ?>
<url>
<loc><?php echo get_term_link($category, $category->slug); ?></loc>
<changefreq>weekly</changefreq>
<priority>0.8</priority>
</url>
<?php } ?>
<?php 
	endforeach;
	// 视频分类
	$categorys = get_terms('videos', 'orderby=name&hide_empty=0');
	foreach ($categorys as $category) : 
?>
<?php if (zm_get_option('no_videos')) { ?>
<url>
<loc><?php echo get_term_link($category, $category->slug); ?></loc>
<changefreq>weekly</changefreq>
<priority>0.8</priority>
</url>
<?php } ?>
<?php 
	endforeach;
	// 商品分类
	$categorys = get_terms('taobao', 'orderby=name&hide_empty=0');
	foreach ($categorys as $category) : 
?>
<?php if (zm_get_option('no_tao')) { ?>
<url>
<loc><?php echo get_term_link($category, $category->slug); ?></loc>
<changefreq>weekly</changefreq>
<priority>0.8</priority>
</url>
<?php } ?>
<?php 
	endforeach;
	// 产品分类
	$categorys = get_terms('products', 'orderby=name&hide_empty=0');
	foreach ($categorys as $category) : 
?>
<?php if (zm_get_option('no_products')) { ?>
<url>
<loc><?php echo get_term_link($category, $category->slug); ?></loc>
<changefreq>weekly</changefreq>
<priority>0.8</priority>
</url>
<?php } ?>
<?php 
	endforeach;
?>
</urlset>
<?php 
	$sitemap = ob_get_contents();
	ob_clean();
	return $sitemap;
}