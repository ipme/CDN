<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// 最新文章
class new_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'new_cat',
			'description' => __( '显示全部分类或某个分类的最新文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('new_cat', '最新文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'show_icon'   => 0,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'><span></span><span></span><span></span></span></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<?php if (zm_get_option('cat_icon') && $instance['show_icon']) { ?>
	<?php $q .= '&category__and='.$instance['cat']; query_posts($q);?>
	<h3 class="widget-title-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" rel="bookmark"><i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<ul>
	<?php 
		global $post;
		if ( is_single() ) {
		$q =  new WP_Query(array(
			'ignore_sticky_posts' => 1,
			'showposts' => $instance['numposts'],
			'post__not_in' => array($post->ID),
			'category__and' => $instance['cat'],
		));
		} else {
		$q =  new WP_Query(array(
			'ignore_sticky_posts' => 1,
			'showposts' => $instance['numposts'],
			'category__and' => $instance['cat'],
		));
	} ?>
	<?php while ($q->have_posts()) : $q->the_post(); ?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } else { ?>
				<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
	$instance['show_icon'] = $new_instance['show_icon']?1:0;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['title_z'] = strip_tags($new_instance['title_z']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '最新文章',
		'titleUrl' => '',
		'numposts' => 5,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_icon') ); ?>" <?php checked( (bool) $instance["show_icon"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>">显示分类图标</label>
		</p>
<?php }
}

add_action( 'widgets_init', 'new_cat_init' );
function new_cat_init() {
	register_widget( 'new_cat' );
}

// 分类文章（图片）
class img_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'img_cat',
			'description' => __( '以图片形式调用一个分类的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('img_cat', '分类图片', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_icon'   => 0,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'><span></span><span></span><span></span></span></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<?php if (zm_get_option('cat_icon') && $instance['show_icon']) { ?>
	<?php $q .= '&category__and='.$instance['cat']; query_posts($q);?>
	<h3 class="widget-title-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" rel="bookmark"><i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>

<div class="picture img_cat">
	<?php 
		global $post;
		if ( is_single() ) {
		$q =  new WP_Query(array(
			'ignore_sticky_posts' => 1,
			'showposts' => $instance['numposts'],
			'post__not_in' => array($post->ID),
			'category__and' => $instance['cat'],
		));
		} else {
		$q =  new WP_Query(array(
			'ignore_sticky_posts' => 1,
			'showposts' => $instance['numposts'],
			'category__and' => $instance['cat'],
		));
	} ?>
	<?php while ($q->have_posts()) : $q->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<span class="img-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo wp_trim_words( get_the_title(), 12 ); ?></a></span>
				<?php zm_thumbnail(); ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['show_icon'] = $new_instance['show_icon']?1:0;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['title_z'] = strip_tags($new_instance['title_z']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类图片',
		'titleUrl' => '',
		'numposts' => 4,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_icon') ); ?>" <?php checked( (bool) $instance["show_icon"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>">显示分类图标</label>
		</p>
<?php }
}

add_action( 'widgets_init', 'img_cat_init' );
function img_cat_init() {
	register_widget( 'img_cat' );
}

// 近期留言
class recent_comments extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'recent_comments',
			'description' => __( '带头像的近期留言' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('recent_comments', '近期留言', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$authornot = strip_tags($instance['authornot']) ? absint( $instance['authornot'] ) : 1;
?>

<div id="message" class="message-widget">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php 
		$no_comments = false;
		$avatar_size = 64;
		$comments_query = new WP_Comment_Query();
		$comments = $comments_query->query( array_merge( array( 'number' => $number, 'status' => 'approve', 'type' => 'comments', 'post_status' => 'publish', 'author__not_in' => explode(',',$instance["authornot"]) ) ) );
		if ( $comments ) : foreach ( $comments as $comment ) : ?>

		<li>
			<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="发表在：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
				<?php if (zm_get_option('cache_avatar')) { ?>
					<?php echo begin_avatar( $comment->comment_author_email, $avatar_size ); ?>
				<?php } else { ?>
					<?php echo get_avatar( $comment->comment_author_email, $avatar_size ); ?>
				<?php } ?>
				<span class="comment_author"><strong><?php echo get_comment_author( $comment->comment_ID ); ?></strong></span>
				<?php echo convert_smilies($comment->comment_content); ?>
			</a>
		</li>

		<?php endforeach; else : ?>
			<li><?php _e('暂无留言', 'begin'); ?></li>
			<?php $no_comments = true;
		endif; ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['authornot'] = strip_tags($new_instance['authornot']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '近期留言';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('authornot' => '1'));
		$authornot = strip_tags($instance['authornot']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('authornot'); ?>">排除的用户ID：</label>
		<p><input id="<?php echo $this->get_field_id( 'authornot' ); ?>" name="<?php echo $this->get_field_name( 'authornot' ); ?>" type="text" value="<?php echo $authornot; ?>" /></p>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function recent_comments_init() {
	register_widget( 'recent_comments' );
}
add_action( 'widgets_init', 'recent_comments_init' );

// 热评文章
class hot_comment extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_comment',
			'description' => __( '调用评论最多的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_comment', '热评文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div id="hot_comment_widget">
<?php } ?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php if($instance['show_thumbs']) { ?>
			<?php
				$review = new WP_Query( array(
					'post_type' => array( 'post' ),
					'showposts' => $number,
					'ignore_sticky_posts' => true,
					'orderby' => 'comment_count',
					'order' => 'dsc',
					'date_query' => array(
						array(
							'after' => ''.$days. 'month ago',
						),
					),
				) );
			?>
			
			<?php while ( $review->have_posts() ): $review->the_post(); ?>
				<li>
					<span class="thumbnail">
						<?php zm_thumbnail(); ?>
					</span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<span class="date"><?php the_time('m/d') ?></span>
					<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' ); ?></span>
				</li>
			<?php endwhile;?>
		<?php } else { ?>
			<?php hot_comment_viewed($number, $days); ?>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热评文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>">时间限定：</label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
		<label>有图/无图：月/天</label>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function hot_comment_init() {
	register_widget( 'hot_comment' );
}
add_action( 'widgets_init', 'hot_comment_init' );

// 标签云
class cx_tag_cloud extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cx_tag_cloud',
			'description' => __( '可实现3D特效' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('cx_tag_cloud', '热门标签', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_3d'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 20;
		$tags_id = strip_tags($instance['tags_id']) ? absint( $instance['tags_id'] ) : 1;
?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
<?php if ($instance['show_3d']) { ?>
	<div id="tag_cloud_widget">
<?php } else { ?>
	<div class="tagcloud">
<?php } ?>
	<?php wp_tag_cloud( array ( 'smallest' => '14', 'largest' => 14, 'unit' => 'px', 'order' => 'RAND', 'hide_empty' => 0, 'number' => $number, 'include' => $instance["tags_id"] ) ); ?>
	<div class="clear"></div>
	<?php if ($instance['show_3d']) : ?><?php wp_enqueue_script( '3dtag.min', get_template_directory_uri() . '/js/3dtag.js', array(), version, false ); ?><?php endif; ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_3d'] = $new_instance['show_3d']?1:0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['tags_id'] = strip_tags($new_instance['tags_id']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热门标签';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '20'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('tags_id' => ''));
		$tags_id = strip_tags($instance['tags_id']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tags_id'); ?>">输入标签ID调用指定标签：</label>
		<textarea style="height:50px;" class="widefat" id="<?php echo $this->get_field_id( 'tags_id' ); ?>" name="<?php echo $this->get_field_name( 'tags_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['tags_id'] ), ENT_QUOTES)); ?></textarea>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_3d') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_3d') ); ?>" <?php checked( (bool) $instance["show_3d"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_3d') ); ?>">显示3D特效</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function cx_tag_cloud_init() {
	register_widget( 'cx_tag_cloud' );
}
add_action( 'widgets_init', 'cx_tag_cloud_init' );

// 随机文章
class random_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'random_post',
			'description' => __( '显示随机文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('random_post', '随机文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
			'this_cat'   => 0,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div id="random_post_widget">
<?php } ?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
		$cat = get_the_category();
		foreach($cat as $key=>$category){
		    $catid = $category->term_id;
		}
		if($instance['this_cat']) {
			$args = array( 'orderby' => 'rand', 'showposts' => $number, 'ignore_sticky_posts' => 1,'cat' => $catid );
		} else {
			$args = array( 'orderby' => 'rand', 'showposts' => $number, 'ignore_sticky_posts' => 1 );
		}
		$query_posts = new WP_Query();
		$query_posts->query($args);
		while ($query_posts->have_posts()) : $query_posts->the_post();
		?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } else { ?>
				<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['this_cat'] = $new_instance['this_cat']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '随机文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('this_cat') ); ?>" name="<?php echo esc_attr( $this->get_field_name('this_cat') ); ?>" <?php checked( (bool) $instance["this_cat"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('this_cat') ); ?>">同分类文章</label>
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function random_post_init() {
	register_widget( 'random_post' );
}
add_action( 'widgets_init', 'random_post_init' );

// 相关文章
class related_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'related_post',
			'description' => __( '显示相关文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('related_post', '相关文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div id="related_post_widget">
<?php } ?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			$post_num = $number;
			global $post;
			$tmp_post = $post;
			$tags = ''; $i = 0;
			if ( get_the_tags( $post->ID ) ) {
			foreach ( get_the_tags( $post->ID ) as $tag ) $tags .= $tag->slug . ',';
			$tags = strtr(rtrim($tags, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&tag='.$tags.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } else { ?>
				<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php
			$i += 1;
			}
			}
			if ( $i < $post_num ) {
			$post = $tmp_post; setup_postdata($post);
			$cats = ''; $post_num -= $i;
			foreach ( get_the_category( $post->ID ) as $cat ) $cats .= $cat->cat_ID . ',';
			$cats = strtr(rtrim($cats, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&category='.$cats.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } else { ?>
				<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
		</li>
		<?php
		}
		}
		$post = $tmp_post; setup_postdata($post);
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '相关文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function related_post_init() {
	register_widget( 'related_post' );
}
add_action( 'widgets_init', 'related_post_init' );

// 本站推荐
class hot_commend extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_commend',
			'description' => __( '调用指定的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_commend', '本站推荐', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div id="hot" class="hot_commend">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php $i = 1; query_posts( array ( 'meta_key' => 'hot', 'showposts' => $number, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>
			<li>
				<?php if($instance['show_thumbs']) { ?>
					<span class="thumbnail">
						<?php zm_thumbnail(); ?>
					</span>
					<span class="hot-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
					<?php if (function_exists('zm_link')) { zm_link(); } ?><i class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></i>
				<?php } else { ?>
					<?php if($i < 4) { ?>
						<span class="new-title"><span class='li-number'><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<?php } else { ?>
						<span class="new-title"><span class='li-numbers'><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<?php } ?>
				<?php } ?>
			</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '本站推荐';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function hot_commend_init() {
	register_widget( 'hot_commend' );
}
add_action( 'widgets_init', 'hot_commend_init' );

// 读者墙
class readers extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'readers',
			'description' => __( '最活跃的读者' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('readers', '读者墙', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 6;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>
<?php if($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
<?php } ?>
<div id="readers_widget" class="readers">
	<?php
		global $wpdb;
		  $counts = wp_cache_get( 'mostactive' );

		  if ( false === $counts ) {
		    $counts = $wpdb->get_results("SELECT COUNT(comment_author) AS cnt, comment_author, comment_author_url, comment_author_email
		        FROM {$wpdb->prefix}comments
		        WHERE comment_date > date_sub( NOW(), INTERVAL $days DAY )
		            AND comment_approved = '1'
		            AND comment_author_email != 'example@example.com'
		            AND comment_author_email != ''
		            AND comment_author_url != ''
		            AND comment_type = ''
		            AND user_id = '0'
		        GROUP BY comment_author_email
		        ORDER BY cnt DESC
		        LIMIT $number");
		  }

		  $mostactive = '';

		  if ( $counts ) {
		    wp_cache_set( 'mostactive', $counts );

		    foreach ($counts as $count) {
		      $c_url = $count->comment_author_url;
				if (zm_get_option('cache_avatar')) {
			      $mostactive .= '<div class="readers-avatar"><span>' . '<a href="'. get_template_directory_uri()."/go.php?url=".base64_encode($c_url) . '" title="' . $count->comment_author .'  '. $count->cnt . ' 个脚印" target="_blank" rel="external nofollow">' . begin_avatar($count->comment_author_email, 96, '', $count->comment_author . ' 发表 ' . $count->cnt . ' 条评论') . '</a></span></div>';
				} else {
			      $mostactive .= '<div class="readers-avatar"><span>' . '<a href="'. get_template_directory_uri()."/go.php?url=".base64_encode($c_url) . '" title="' . $count->comment_author .'  '. $count->cnt . ' 个脚印" target="_blank" rel="external nofollow">' . get_avatar($count->comment_author_email, 96, '', $count->comment_author . ' 发表 ' . $count->cnt . ' 条评论') . '</a></span></div>';
				}
		    }
		  echo $mostactive;
		  }
	?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '读者墙';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '6'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>">显示数量：</label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>">时间限定（天）：</label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function readers_init() {
	register_widget( 'readers' );
}
add_action( 'widgets_init', 'readers_init' );

// 关注我们
class feed extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'feed',
			'description' => __( 'RSS、微信、微博' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('feed', '关注我们', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>

<div id="feed_widget">
	<div class="feed-rss">
		<ul>
			<li class="weixin">
				<span class="weixin-b"><span class="weixin-qr"><span class="weixin-arrow"><span class="arrow-s"><i class="be be-favorite"></i></span><span class="arrow-b"><i class="be be-favorite"></i></span></span><img src="<?php echo $instance['weixin']; ?>" alt=" weixin"/></span><a title="微信"><i class="be be-weixin"></i></a></span>
			</li>
			<li class="feed"><a title="" href="<?php echo $instance['rssurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['rss']; ?>"></i></a></li>
			<li class="tsina"><a title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></li>
			<li class="tqq"><a title="" href="<?php echo $instance['tqqurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tqq']; ?>"></i></a></li>
		</ul>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['weixin'] = $new_instance['weixin'];
			$instance['tsina'] = $new_instance['tsina'];
			$instance['tsinaurl'] = $new_instance['tsinaurl'];
			$instance['tqq'] = $new_instance['tqq'];
			$instance['tqqurl'] = $new_instance['tqqurl'];
			$instance['rss'] = $new_instance['rss'];
			$instance['rssurl'] = $new_instance['rssurl'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关注我们';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('weixin' => '' . get_template_directory_uri() . '/img/favicon.png"'));
		$weixin = $instance['weixin'];
		$instance = wp_parse_args((array) $instance, array('tsina' => 'be be-stsina'));
		$tsina = $instance['tsina'];
		$instance = wp_parse_args((array) $instance, array('tsinaurl' => '输入链接地址'));
		$tsinaurl = $instance['tsinaurl'];
		$instance = wp_parse_args((array) $instance, array('tqq' => 'be be-tqq'));
		$tqq = $instance['tqq'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '输入链接地址'));
		$tqqurl = $instance['tqqurl'];
		$instance = wp_parse_args((array) $instance, array('rss' => 'be be-rss'));
		$rss = $instance['rss'];
		$instance = wp_parse_args((array) $instance, array('rssurl' => 'http://域名/feed/'));
		$rssurl = $instance['rssurl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('weixin'); ?>">微信二维码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'weixin' ); ?>" name="<?php echo $this->get_field_name( 'weixin' ); ?>" type="text" value="<?php echo $weixin; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo $rss; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rssurl' ); ?>" name="<?php echo $this->get_field_name( 'rssurl' ); ?>" type="text" value="<?php echo $rssurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsina' ); ?>" name="<?php echo $this->get_field_name( 'tsina' ); ?>" type="text" value="<?php echo $tsina; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsinaurl' ); ?>" name="<?php echo $this->get_field_name( 'tsinaurl' ); ?>" type="text" value="<?php echo $tsinaurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqq'); ?>">腾讯微博图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqq' ); ?>" name="<?php echo $this->get_field_name( 'tqq' ); ?>" type="text" value="<?php echo $tqq; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">腾讯微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqqurl' ); ?>" name="<?php echo $this->get_field_name( 'tqqurl' ); ?>" type="text" value="<?php echo $tqqurl; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function feed_init() {
	register_widget( 'feed' );
}
add_action( 'widgets_init', 'feed_init' );

// 广告位
class advert extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'advert',
			'description' => __( '用于侧边添加广告代码' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('advert', '广告位', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;

		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
?>

<?php if ( ! wp_is_mobile() ) { ?>
<div id="advert_widget">
	<?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			if ( current_user_can('unfiltered_html') )
				$instance['text'] =  $new_instance['text'];
			else
				$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
			$instance['filter'] = ! empty( $new_instance['filter'] );
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '广告位';
		}
		$text = esc_textarea($instance['text']);
		global $wpdb;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>">内容：</label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea></p>
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
		<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function advert_init() {
	register_widget( 'advert' );
}
add_action( 'widgets_init', 'advert_init' );

// 关于本站
class about extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about',
			'description' => __( '本站信息、RSS、微信、微博、QQ' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('about', '关于本站', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_social_icon' => 1,
			'show_animate_num' => 1
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>

<div id="feed_widget">
	<div class="feed-about">
		<?php if ( $instance[ 'about_back' ]  ) { ?>
			<div class="author-back"><img src="<?php echo $instance['about_back']; ?>" alt="bj"/></div>
		<?php } ?>
		<div class="about-main">
			<div class="about-img">
				<img src="<?php echo $instance['about_img']; ?>" alt="name"/>
			</div>
			<div class="clear"></div>
			<div class="about-name"><?php echo $instance['about_name']; ?></div>
			<div class="about-the"><?php echo $instance['about_the']; ?></div>
		</div>
		<div class="clear"></div>

		<?php if($instance['show_social_icon']) { ?>
		<ul>
			<li class="weixin">
				<span class="weixin-b"><span class="weixin-qr"><span class="weixin-arrow"><span class="arrow-s"><i class="be be-favorite"></i></span><span class="arrow-b"><i class="be be-favorite"></i></span></span><img src="<?php echo $instance['weixin']; ?>" alt=" weixin"/></span><a title="微信"><i class="be be-weixin"></i></a></span>
			</li>
			<li class="tqq"><a target=blank rel="external nofollow" href=http://wpa.qq.com/msgrd?V=3&uin=<?php echo $instance['tqqurl']; ?>&Site=QQ&Menu=yes title="QQ在线"><i class="<?php echo $instance['tqq']; ?>"></i></a></li>
			<li class="tsina"><a title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></li>
			<li class="feed"><a title="" href="<?php echo $instance['rssurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['rss']; ?>"></i></a></li>
		</ul>
		<?php } else { ?>
			<span class="social-clear"></span>
		<?php } ?>

		<div class="about-inf">
			<span class="about about-cn"><?php _e( '文章', 'begin' ); ?><br /><?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?></span>
			<span class="about about-pn"><?php _e( '留言', 'begin' ); ?><br />
			<?php 
				$my_email = get_bloginfo ( 'admin_email' );
				global $wpdb;echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments where comment_author_email!='$my_email' And comment_author_email!=''");
			?>
			</span>
			<?php if($instance['show_animate_num']) { ?>
				<span class="about about-cn"><?php _e( '访客', 'begin' ); ?><br /><span class="animateNum all-view" data-animatetype="num" data-animatetarget="<?php echo all_view(); ?>"><?php echo all_view(); ?></span></span>
			<?php } else { ?>
				<span class="about about-cn"><?php _e( '访客', 'begin' ); ?><br /><?php echo all_view(); ?></span>
			<?php } ?>
		</div>
	</div>
</div>

<?php
	echo $after_widget;
	}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
			$instance = $old_instance;
			$instance = array();
			$instance['show_social_icon'] = $new_instance['show_social_icon']?1:0;
			$instance['show_animate_num'] = $new_instance['show_animate_num']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['about_img'] = $new_instance['about_img'];
			$instance['about_name'] = $new_instance['about_name'];
			$instance['about_back'] = $new_instance['about_back'];
			$instance['about_the'] = $new_instance['about_the'];
			$instance['weixin'] = $new_instance['weixin'];
			$instance['tsina'] = $new_instance['tsina'];
			$instance['tsinaurl'] = $new_instance['tsinaurl'];
			$instance['rss'] = $new_instance['rss'];
			$instance['rssurl'] = $new_instance['rssurl'];
			$instance['tqq'] = $new_instance['tqq'];
			$instance['tqqurl'] = $new_instance['tqqurl'];
			return $instance;
		}

	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关于本站';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('weixin' => '' . get_template_directory_uri() . '/img/favicon.png"'));
		$weixin = $instance['weixin'];
		$instance = wp_parse_args((array) $instance, array('about_img' => '' . get_template_directory_uri() . '/img/favicon.png"'));
		$about_img = $instance['about_img'];
		$instance = wp_parse_args((array) $instance, array('about_name' => '网站名称'));
		$about_name = $instance['about_name'];
		$instance = wp_parse_args((array) $instance, array('about_back' => 'https://s2.ax1x.com/2019/05/31/Vlw7B6.jpg'));
		$about_back = $instance['about_back'];
		$instance = wp_parse_args((array) $instance, array('about_the' => '到小工具中更改此内容'));
		$about_the = $instance['about_the'];
		$instance = wp_parse_args((array) $instance, array('tsina' => 'be be-stsina'));
		$tsina = $instance['tsina'];
		$instance = wp_parse_args((array) $instance, array('tsinaurl' => '输入链接地址'));
		$tsinaurl = $instance['tsinaurl'];
		$instance = wp_parse_args((array) $instance, array('rss' => 'be be-rss'));
		$rss = $instance['rss'];
		$instance = wp_parse_args((array) $instance, array('rssurl' => 'http://域名/feed/'));
		$rssurl = $instance['rssurl'];
		$instance = wp_parse_args((array) $instance, array('tqq' => 'be be-qq'));
		$tqq = $instance['tqq'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '88888'));
		$tqqurl = $instance['tqqurl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_img'); ?>">头像：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_img' ); ?>" name="<?php echo $this->get_field_name( 'about_img' ); ?>" type="text" value="<?php echo $about_img; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_back'); ?>">背景图片：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_back' ); ?>" name="<?php echo $this->get_field_name( 'about_back' ); ?>" type="text" value="<?php echo $about_back; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_name'); ?>">昵称：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_name' ); ?>" name="<?php echo $this->get_field_name( 'about_name' ); ?>" type="text" value="<?php echo $about_name; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_the'); ?>">说明：</label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('about_the'); ?>" name="<?php echo $this->get_field_name('about_the'); ?>"><?php echo $about_the; ?></textarea></p>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_social_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_social_icon') ); ?>" <?php checked( (bool) $instance["show_social_icon"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_social_icon') ); ?>">显示社交图标</label>
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_animate_num') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_animate_num') ); ?>" <?php checked( (bool) $instance["show_animate_num"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_animate_num') ); ?>">显示数字动画</label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('weixin'); ?>">微信二维码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'weixin' ); ?>" name="<?php echo $this->get_field_name( 'weixin' ); ?>" type="text" value="<?php echo $weixin; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqq'); ?>">QQ图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqq' ); ?>" name="<?php echo $this->get_field_name( 'tqq' ); ?>" type="text" value="<?php echo $tqq; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">QQ号：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqqurl' ); ?>" name="<?php echo $this->get_field_name( 'tqqurl' ); ?>" type="text" value="<?php echo $tqqurl; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsina' ); ?>" name="<?php echo $this->get_field_name( 'tsina' ); ?>" type="text" value="<?php echo $tsina; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsinaurl' ); ?>" name="<?php echo $this->get_field_name( 'tsinaurl' ); ?>" type="text" value="<?php echo $tsinaurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo $rss; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rssurl' ); ?>" name="<?php echo $this->get_field_name( 'rssurl' ); ?>" type="text" value="<?php echo $rssurl; ?>" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function about_init() {
	register_widget( 'about' );
}
add_action( 'widgets_init', 'about_init' );

// 图片
class img_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'img_widget',
			'description' => __( '调用最新图片文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('img_widget', '最新图片', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'><span></span><span></span><span></span></span></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<?php if($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" rel="bookmark"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<div class="picture img_widget">
	<?php
	    $args = array(
	        'post_type' => 'picture',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'gallery',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php zm_thumbnail(); ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
		$instance['hideTitle'] = isset($new_instance['hideTitle']);
		$instance['newWindow'] = isset($new_instance['newWindow']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['cat'] = $new_instance['cat'];
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新图片';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '4'));
	$number = strip_tags($instance['number']);
	$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
	$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '选择分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'gallery', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 视频
class video_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'video_widget',
			'description' => __( '调用最新视频文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('video_widget', '最新视频', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'><span></span><span></span><span></span></span></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>
<?php if($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" rel="bookmark"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<div class="picture video_widget">
	<?php
	    $args = array(
	        'post_type' => 'video',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'videos',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php videos_thumbnail(); ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
		$instance['hideTitle'] = isset($new_instance['hideTitle']);
		$instance['newWindow'] = isset($new_instance['newWindow']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['cat'] = $new_instance['cat'];
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新视频';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '4'));
	$number = strip_tags($instance['number']);
	$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
	$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '选择分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'videos', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 淘客
class tao_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'tao_widget',
			'description' => __( '调用最新商品' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('tao_widget', '最新商品', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'><span></span><span></span><span></span></span></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<?php if($instance['show_icon']) { ?>
	<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" rel="bookmark"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
<?php } ?>
<div class="picture tao_widget">
	<?php
	    $args = array(
	        'post_type' => 'tao',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'taobao',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php tao_thumbnail(); ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
			$instance['hideTitle'] = isset($new_instance['hideTitle']);
			$instance['newWindow'] = isset($new_instance['newWindow']);
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新商品';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '4'));
	$number = strip_tags($instance['number']);
	$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
	$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '选择分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'taobao', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 多功能小工具
class php_text extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'php_text',
			'description' => __( '支持PHP、JavaScript、短代码等' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('php_text', '增强文本', $widget_ops);
	}

	function widget( $args, $instance ) {

		if (!isset($args['widget_id'])) {
			$args['widget_id'] = null;
		}

		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$cssClass = empty($instance['cssClass']) ? '' : $instance['cssClass'];
		$text = apply_filters('widget_enhanced_text', $instance['text'], $instance);
		$hideTitle = !empty($instance['hideTitle']) ? true : false;
		$hideEmpty = !empty($instance['hideEmpty']) ? true : false;
		$newWindow = !empty($instance['newWindow']) ? true : false;
		$filterText = !empty($instance['filter']) ? true : false;
		$bare = !empty($instance['bare']) ? true : false;

		if ( $cssClass ) {
			if( strpos($before_widget, 'class') === false ) {
				$before_widget = str_replace('>', 'class="'. $cssClass . '"', $before_widget);
			} else {
				$before_widget = str_replace('class="', 'class="'. $cssClass . ' ', $before_widget);
			}
		}

	// 通过PHP解析文本
	ob_start();
	eval('?>' . $text);
	$text = ob_get_contents();
	ob_end_clean();

		// 通过do_shortcode运行文本
		$text = do_shortcode($text);

		if (!empty($text) || !$hideEmpty) {
			echo $bare ? '' : $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title</a>";
				echo $bare ? $title : $before_title . $title . $after_title;
			}
			echo $bare ? '' : '<div class="textwidget widget-text">';

			// 重复的内容
			echo $filterText ? wpautop($text) : $text;
			echo $bare ? '' : '</div>' . $after_widget;
		}
	}

    /**
     * 更新内容
     */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = wp_filter_post_kses($new_instance['text']);
			$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
			$instance['cssClass'] = strip_tags($new_instance['cssClass']);
			$instance['hideTitle'] = isset($new_instance['hideTitle']);
			$instance['hideEmpty'] = isset($new_instance['hideEmpty']);
			$instance['newWindow'] = isset($new_instance['newWindow']);
			$instance['filter'] = isset($new_instance['filter']);
			$instance['bare'] = isset($new_instance['bare']);

		return $instance;
	}

    /**
     * 管理面板
     */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'titleUrl' => '',
			'cssClass' => '',
			'text' => ''
		));
		$title = $instance['title'];
		$titleUrl = $instance['titleUrl'];
		$cssClass = $instance['cssClass'];
		$text = format_to_edit($instance['text']);
?>

		<style>
			.monospace {
				font-family: Consolas, Lucida Console, monospace;
			}
			.etw-credits {
				font-size: 6.9em;
				background: #F7F7F7;
				border: 1px solid #EBEBEB;
				padding: 4px 6px;
			}
		</style>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('cssClass'); ?>">CSS 类:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('cssClass'); ?>" name="<?php echo $this->get_field_name('cssClass'); ?>" type="text" value="<?php echo $cssClass; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">内容：</label>
			<textarea class="widefat monospace" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('hideTitle'); ?>" class="checkbox" name="<?php echo $this->get_field_name('hideTitle'); ?>" type="checkbox" <?php checked(isset($instance['hideTitle']) ? $instance['hideTitle'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hideTitle'); ?>">不显示标题</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('hideEmpty'); ?>" class="checkbox" name="<?php echo $this->get_field_name('hideEmpty'); ?>" type="checkbox" <?php checked(isset($instance['hideEmpty']) ? $instance['hideEmpty'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hideEmpty'); ?>">不显示空的小工具</label>
		</p>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('filter'); ?>" class="checkbox" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>">自动添加段落</label>
		</p>
		<!-- 
		<p>
			<input id="<?php echo $this->get_field_id('bare'); ?>" name="<?php echo $this->get_field_name('bare'); ?>" type="checkbox" <?php checked(isset($instance['bare']) ? $instance['bare'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('bare'); ?>">标题之前不输出after_widget</label>
		</p>
		 -->
<?php }
}

function php_text_init() {
	register_widget( 'php_text' );
}
add_action( 'widgets_init', 'php_text_init' );

// 即将发布
class timing_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'timing_post',
			'description' => __( '即将发表的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('timing_post', '即将发布', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div class="timing_post">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
		$my_query = new WP_Query( array ( 'post_status' => 'future','order' => 'ASC','showposts' => $number,'ignore_sticky_posts' => '1'));
		if ($my_query->have_posts()) {
			while ($my_query->have_posts()) : $my_query->the_post();
				$do_not_duplicate = $post->ID; ?>
				<li><i class="be be-schedule"> <?php the_time('G:i') ?></i><?php the_title(); ?></li>
			<?php endwhile;}
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
	} else {
		$title = '即将发布';
	}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '5'));
	$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function timing_post_init() {
	register_widget( 'timing_post' );
}
add_action( 'widgets_init', 'timing_post_init' );

// 作者墙
class author_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'author_widget',
			'description' => __( '显示所有作者头像' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('author_widget', '作者墙', $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<?php
	$author_numbers=$instance['author_numbers'];
	if($author_numbers) {} else { $author_numbers=50; }
	$list = $instance['exclude_author'];
	$array = explode(',', $list); 
	$count=count($array);
	for($excludeauthor=0;$excludeauthor<=$count;$excludeauthor++) {
		$exclude.="user_login!='".trim($array[$excludeauthor])."'";
		if($excludeauthor!=$count) {
			$exclude.=" and ";
		}
	}
	$where = "WHERE ".$exclude."";
	global $wpdb;
	$table_prefix.=$wpdb->base_prefix;
	$table_prefix.="users";
	$table_prefix1.=$wpdb->base_prefix;
	$table_prefix1.="posts";

	$get_results="SELECT count(p.post_author) as post1,c.id, c.user_login, c.display_name, c.user_email, c.user_url, c.user_registered FROM {$table_prefix} as c , {$table_prefix1} as p {$where} and p.post_type = 'post' AND p.post_status = 'publish' and c.id=p.post_author GROUP BY p.post_author order by post1 DESC limit {$author_numbers}";
	$comment_counts = (array) $wpdb->get_results("{$get_results}", object);
?>
<div class="author_widget_box">
	<?php
		foreach ( $comment_counts as $count ) {
			$user = get_userdata($count->id);
			echo '<ul class="xl9"><li class="author_box">';
			$post_count = get_usernumposts($user->ID);
			if (zm_get_option('cache_avatar')) {
				$postount = begin_avatar( $user->user_email, $size = 96);
			} else {
				$postount = get_avatar( $user->user_email, $size = 96);
			}

				$temp=explode(" ",$user->display_name);
			 	$link = sprintf(
					'<a href="%1$s" title="%2$s" >'.$postount.' <span class="clear"></span>%3$s %4$s %5$s</a>',
					get_author_posts_url( $user->ID, $user->user_login ),
					esc_attr( sprintf( ' %s 发表 %s 篇文章', $user->display_name,get_usernumposts($user->ID) ) ),
					$temp[0],$temp[1],$temp[2]
				);
			echo $link;
			echo "</li></ul>";
		}
	?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
}
function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '作者墙'
		)); 
		?> 

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
			<?php $video_embed_c = stripslashes(htmlspecialchars($instance['exclude_author'], ENT_QUOTES)); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude_author' ); ?>">排除的作者：</label>
			<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'exclude_author' ); ?>" name="<?php echo $this->get_field_name( 'exclude_author' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['exclude_author'] ), ENT_QUOTES)); ?></textarea>
		</p>
		<p>
		<p>
			<label for="<?php echo $this->get_field_id( 'author_numbers' ); ?>">显示数量：</label>
			<input type="text" id="<?php echo $this->get_field_id('author_numbers'); ?>" name="<?php echo $this->get_field_name('author_numbers'); ?>" value="<?php echo $instance['author_numbers']; ?>" style="width:100%;" />
        </p>
	<?php
	}
}
function author_widget_init() {
	register_widget( 'author_widget' );
}
add_action( 'widgets_init', 'author_widget_init' );

// 关于作者
class about_author extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about_author',
			'description' => __( '只显示在正文和作者页面' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('about_author', '关于作者', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		if ( is_author() || is_single() ){ 
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
     	}
?>

<?php if ( is_author() || is_single() ) { ?>
<?php
	global $wpdb;
	$author_id = get_the_author_meta( 'ID' );
	$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
?>
<div id="about_author_widget">
	<div class="author-meta-box">
		<?php if ( $instance[ 'author_back' ]  ) { ?>
			<div class="author-back"><img src="<?php echo $instance['author_back']; ?>" alt="bj"/></div>
		<?php } ?>
		<div class="author-meta">
			<div class="author-avatar">
				<?php if (zm_get_option('cache_avatar')) { ?>
					<?php echo begin_avatar( get_the_author_meta('user_email'), '96' ); ?>
				<?php } else { ?>
					<?php echo get_avatar( get_the_author_meta('user_email'), '96' ); ?>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<h4 class="author-the"><?php the_author(); ?></h4>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="author-th">
			<div class="author-description"><?php the_author_meta( 'user_description' ); ?></div>
			<div class="author-th-inf">
				<div class="author-n author-nickname"><span><?php the_author_posts(); ?></span><br /><?php _e( '文章', 'begin' ); ?></div>
				<div class="author-n"><span><?php echo $comment_count;?></span><br /><?php _e( '评论', 'begin' ); ?></div>
				<div class="author-n author-th-views"><span><?php author_posts_views(get_the_author_meta('ID'));?></span><br /><?php _e( '浏览', 'begin' ); ?></div>
			<div class="clear"></div>
			</div>
			<div class="author-m"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php _e( '更多文章', 'begin' ); ?></a></div>
			<div class="clear"></div>
		</div>
	<div class="clear"></div>
	</div>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['author_back'] = $new_instance['author_back'];
			// $instance['author_url'] = $new_instance['author_url'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关于作者';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('author_back' => 'https://s2.ax1x.com/2019/05/31/Vlw7B6.jpg'));
		$author_back = $instance['author_back'];
		// $instance = wp_parse_args((array) $instance, array('author_url' => ''));
		// $author_url = $instance['author_url'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('author_back'); ?>">背景图片：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'author_back' ); ?>" name="<?php echo $this->get_field_name( 'author_back' ); ?>" type="text" value="<?php echo $author_back; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function about_author_init() {
	register_widget( 'about_author' );
}
add_action( 'widgets_init', 'about_author_init' );

// 最近更新过的文章
class updated_posts extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'updated_posts',
			'description' => __( '调用最近更新过的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('updated_posts', '最近更新过的文章', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 15;
?>

<div class="post_cat">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php if ( function_exists('recently_updated_posts') ) recently_updated_posts($number,$days); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最近更新过的文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '15'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('days'); ?>">限制时间（天）：</label>
	<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function updated_posts_init() {
	register_widget( 'updated_posts' );
}
add_action( 'widgets_init', 'updated_posts_init' );

// EDD下载
class edd_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'edd_widget',
			'description' => __( '调用最新EDD下载' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('edd_widget', '最新下载', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture tao_widget">
	<?php
	    $args = array(
	        'post_type' => 'download',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'download_category',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php tao_thumbnail(); ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新下载';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '4'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '选择分类', 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'download_category', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
if (function_exists( 'edd_get_actions' )) {
function edd_widget_init() {
	register_widget( 'edd_widget' );
}
add_action( 'widgets_init', 'edd_widget_init' );
}

// 用户登录
class user_login extends WP_Widget {
	static private $login_registration_status;
	public function __construct() {
		$widget_ops = array(
			'classname' => 'user_login',
			'description' => '用户登录、管理站点及用户中心等链接',
			'customize_selective_refresh' => true,
		);
		parent::__construct('user_login', '用户登录', $widget_ops);
	}

	static function login_form() {
		get_currentuserinfo();global $current_user, $user_ID, $user_identity;
		if( !$user_ID || '' == $user_ID ) {
			$html = '<div id="login_widget"><form method="post" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">';
			$html .= '<input type="text" name="login_username" placeholder="'.sprintf(__( '用户名', 'begin' )).'" /><br/>';
			$html .= '<input type="password" name="login_password" placeholder="'.sprintf(__( '密码', 'begin' )).'" /><br/>';
			$html .= '<input type="submit" name="login_submit" value="'.sprintf(__( '登录', 'begin' )).'" /><br/>';
			$html .= '<p class="rememberme pretty success">';
			$html .= '<input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme" checked />';
			$html .= '<label for="rememberme" type="checkbox"/><i class="mdi" data-icon=""></i>'.sprintf(__( '记住我的登录信息', 'begin' )).'</label>';
			$html .= '</p>';
			$html .= '</form></div>';
			return $html;
		}
	}

	static function registration_form() {
		get_currentuserinfo();global $current_user, $user_ID, $user_identity;
		if( !$user_ID || '' == $user_ID ) {
			$html = '<div id="login_widget"><form method="post" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">';
			$html .= '<input type="text" name="registration_username" placeholder="用户名" /><br/>';
			$html .= '<input type="password" name="registration_password" placeholder="密码" /><br/>';
			$html .= '<input type="email" name="registration_email" placeholder="邮件" /><br/>';
			$html .= '<input type="submit" name="reg_submit" value="注册" /><br/>';
			$html .= '</form></div>';
			return $html;
		}
	}

	function register_user() {
		if ( isset( $_POST['reg_submit'] ) ) {
			$username = esc_attr( $_POST['registration_username'] );
			$password = esc_attr( $_POST['registration_password'] );
			$email    = esc_attr( $_POST['registration_email'] );
			$register_user = wp_create_user( $username, $password, $email );

			if ( $register_user && ! is_wp_error( $register_user ) ) {
				self::$login_registration_status = '注册成功！';
			} elseif ( is_wp_error( $register_user ) ) {
				self::$login_registration_status = $register_user->get_error_message();
			}
		}
	}

	function login_user() {
		if ( isset( $_POST['login_submit'] ) ) {
			$creds                  = array();
			$creds['user_login']    = esc_attr( $_POST['login_username'] );
			$creds['user_password'] = esc_attr( $_POST['login_password'] );
			$creds['remember']      = esc_attr( $_POST['remember_login'] );
			$login_user = wp_signon( $creds, false );
			if ( ! is_wp_error( $login_user ) ) {
				wp_redirect( home_url() );
			} elseif ( is_wp_error( $login_user ) ) {
				self::$login_registration_status = $login_user->get_error_message();
			}
		}
	}

	public function widget( $args, $instance ) { ?>
		<?php
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>

		<?php $this->login_user(); ?>
		<?php $this->register_user(); ?>

		<div class="login-tab-widget">
			<div class="tab-title">
				<h3>
					<span class="selected"><?php _e( '登录', 'begin' ); ?></span><span><?php _e( '注册', 'begin' ); ?></span>
				</h3>
			</div>
			<div class="tab-content">
				<div class="login-error"><?php echo self::$login_registration_status; ?></div>
				<ul>
					<li><?php echo self::login_form(); ?></li>
					<li class="login-form"><?php do_action('login_form'); ?></li>
				</ul>
				<ul class="tab-reg">
					<li><?php echo self::registration_form(); ?></li>
				</ul>

				<?php global $user_identity,$user_level;get_currentuserinfo();if ($user_identity) { ?>
					<div id="login_widget">
						<div class="login-user-widget">
							<div class="login-widget-avata">
								<?php 
									global $current_user;
									get_currentuserinfo();
									if (zm_get_option('cache_avatar')) {
										echo begin_avatar( get_the_author_meta('user_email'), '96' );
									} else {
										echo get_avatar( get_the_author_meta('user_email'), '96' );
									}
								?>
								<div class="clear"></div>
								您已登录：<?php echo $user_identity; ?>
							</div>
							<div class="login-widget-link">
								<?php if ( zm_get_option('user_url') == '' ) { ?>
								<?php } else { ?>
							  		<a href="<?php echo get_permalink( zm_get_option('user_url') ); ?>">用户中心</a>
							  	<?php } ?>
								<?php if (current_user_can('level_10') ){ ?><?php wp_register('', ''); ?><?php } ?>
								<a href="<?php echo wp_logout_url( home_url() ); ?>" title="">退出登录</a>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				 <?php } ?>

			</div>
		</div>

		<?php
		echo $args['after_widget'];
	}
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = '';
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
	<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

}
function register_user_login() {
	register_widget( 'user_login' );
}
add_action( 'widgets_init', 'register_user_login' );

// 留言板
class pages_recent_comments extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'pages_recent_comments',
			'description' => __( '调用“留言板”页面留言' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('pages_recent_comments', '留言板', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div id="message" class="message-widget">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<ul>
		<?php 
		$no_comments = false;
		$avatar_size = 64;
		$comments_query = new WP_Comment_Query();
		$comments = $comments_query->query( array_merge( array( 'number' => $number, 'post_id' => $instance["pages_id"] ) ) );
		if ( $comments ) : foreach ( $comments as $comment ) : ?>

		<li>
			<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="发表在：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
				<?php if (zm_get_option('cache_avatar')) { ?>
					<?php echo begin_avatar( $comment->comment_author_email, $avatar_size ); ?>
				<?php } else { ?>
					<?php echo get_avatar( $comment->comment_author_email, $avatar_size ); ?>
				<?php } ?>
				<span class="comment_author"><strong><?php echo get_comment_author( $comment->comment_ID ); ?></strong></span>
				<?php echo convert_smilies($comment->comment_content); ?>
			</a>
		</li>

		<?php endforeach; else : ?>
			<li><?php _e('暂无留言', 'begin'); ?></li>
			<?php $no_comments = true;
		endif; ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['pages_id'] = $new_instance['pages_id'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '留言板';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('pages_id'); ?>">选择页面：</label>
		<?php wp_dropdown_pages( array( 'name' => $this->get_field_name("pages_id"), 'selected' => $instance["pages_id"] ) ); ?>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

function pages_recent_comments_init() {
	register_widget( 'pages_recent_comments' );
}
add_action( 'widgets_init', 'pages_recent_comments_init' );
// Tab
class zmTabs extends WP_Widget {
	function __construct() {
		parent::__construct(
			'zmtabs',
			__('Tab组合小工具'),
			array(
				'description' => __( '最新文章、热评文章、热门文章、最近留言' ),
			'classname' => 'widget_zm_tabs'
			)
		);
	}

	public function zm_get_defaults() {
		return array(
			'title'       => '',
			'tabs_category'   => 1,
			'tabs_date'     => 1,
			// Recent posts
			'recent_enable'   => 1,
			'recent_thumbs'   => 1,
			'recent_cat_id'   => '0',
			'recent_num'    => '5',
			// Popular posts
			'popular_enable'  => 1,
			'popular_thumbs'  => 1,
			'popular_cat_id'  => '0',
			'popular_time'    => '0',
			'popular_num'     => '5',
			// Recent comments
			'comments_enable'   => 1,
			'comments_avatars'  => 1,
			'comments_num'    => '5',
			'authornot'    => '1',
			// viewe
			'viewe_enable'     => 1,
			'viewe_thumbs'  => 1,
			'viewe_number'  => '5',
			'viewe_days'    => '90',
		);
	}


/*  Create tabs-nav
/* ------------------------------------ */
	private function _create_tabs($tabs,$count) {
		// Borrowed from Jermaine Maree, thanks mate!
		$titles = array(
			'recent'	=> __('最新文章', 'begin'),
			'popular'	=> __('热评文章', 'begin'),
			'viewe'		=> __('热门文章', 'begin'),
			'comments'	=> __('最近留言', 'begin')
		);
		$icons = array(
			'recent'   => 'be be-file',
			'popular'  => 'be be-favoriteoutline',
			'viewe'     => 'be be-eye',
			'comments' => 'be be-speechbubble'
		);

		$output = sprintf('<div class="zm-tabs-nav group tab-count-%s">', $count);
		foreach ( $tabs as $tab ) {
			$output .= sprintf('<span class="zm-tab tab-%1$s"><a href="javascript:"><i class="%3$s"></i><span>%4$s</span></a></span>',
				$tab,
				$tab . '-' . $this -> number,
				$icons[$tab],
				$titles[$tab]
			);
		}
		$output .= '</div>';
		return $output;
	}

/*  Widget
/* ------------------------------------ */
	public function widget($args, $instance) {
		extract( $args );
	$defaults = $this -> zm_get_defaults();

	$instance = wp_parse_args( (array) $instance, $defaults );

	$title = apply_filters('widget_title',$instance['title']);
	$title = empty( $title ) ? '' : $title;
		$output = $before_widget."\n";
		if ( ! empty( $title ) )
			$output .= $before_title.$title.$after_title;
		ob_start();

/*  Set tabs-nav order & output it
/* ------------------------------------ */
	$tabs = array();
	$count = 0;
	$order = array(
		'recent'	=> 1,
		'popular'	=> 2,
		'viewe'		=> 3,
		'comments'	=> 4
	);
	asort($order);
	foreach ( $order as $key => $value ) {
		if ( $instance[$key.'_enable'] ) {
			$tabs[] = $key;
			$count++;
		}
	}
	if ( $tabs && ($count > 1) ) { $output .= $this->_create_tabs($tabs,$count); }
?>

	<div class="zm-tabs-container">

		<?php if($instance['recent_enable']) { // Recent posts enabled? ?>
			<?php 
				global $post;
				if ( is_single() ) {
				$recent =  new WP_Query(array(
					'ignore_sticky_posts' => 1,
					'showposts' => $instance['recent_num'],
					'post__not_in' => array($post->ID),
					'cat' => $instance['recent_cat_id'],
				));
				} else {
				$recent =  new WP_Query(array(
					'ignore_sticky_posts' => 1,
					'showposts' => $instance['recent_num'],
					'cat' => $instance['recent_cat_id'],
				));
			} ?>
			<div class="new_cat">
				<ul id="tab-recent-<?php echo $this -> number ?>" class="zm-tab group <?php if($instance['recent_thumbs']) { echo 'thumbs-enabled'; } ?>" style="display:block;">
					<h4><?php _e( '最新文章', 'begin' ); ?></h4>
					<?php while ($recent->have_posts()): $recent->the_post(); ?>
					<li>
						<?php if($instance['recent_thumbs']) { ?>
							<span class="thumbnail">
								<?php zm_thumbnail(); ?>
							</span>
							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<span class="date"><?php the_time('m/d') ?></span>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
						<?php } else { ?>
							<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
						<?php } ?>
					</li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</ul><!--/.zm-tab-->
			</div>
		<?php } ?>

		<?php if($instance['popular_enable']) { // Popular posts enabled? ?>

			<?php
				$popular = new WP_Query( array(
					'post_type'				=> array( 'post' ),
					'showposts'				=> $instance['popular_num'],
					'cat'					=> $instance['popular_cat_id'],
					'ignore_sticky_posts'	=> true,
					'orderby'				=> 'comment_count',
					'order'					=> 'dsc',
					'date_query' => array(
						array(
							'after' => $instance['popular_time'],
						),
					),
				) );
			?>

			<div class="new_cat">
				<ul id="tab-popular-<?php echo $this -> number ?>" class="zm-tab group <?php if($instance['popular_thumbs']) { echo 'thumbs-enabled'; } ?>">
					<h4><?php _e( '热评文章', 'begin' ); ?></h4>
					<?php while ( $popular->have_posts() ): $popular->the_post(); ?>
					<li>
						<?php if($instance['popular_thumbs']) { ?>
							<span class="thumbnail">
								<?php zm_thumbnail(); ?>
							</span>
							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<span class="date"><?php the_time('m/d') ?></span>
							<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' ); ?></span>
						<?php } else { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
						<?php } ?>
					</li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</ul><!--/.zm-tab-->
			</div>
		<?php } ?>

		<?php if($instance['viewe_enable']) { // viewe enabled? ?>

			<div class="new_cat">
				<ul id="tab-viewe-<?php echo $this -> number ?>" class="zm-tab group">
					<h4><?php _e( '热门文章', 'begin' ); ?></h4>
					<?php if($instance['viewe_thumbs']) { ?>
						<?php if (function_exists('the_views')) { ?>
							<?php get_timespan_most_viewed_img('post',$instance["viewe_number"],$instance["viewe_days"], true, true); ?>
						<?php } else { ?>
							<li><a href="https://wordpress.org/plugins/wp-postviews/" rel="external nofollow" target="_blank">需要开启文章浏览统计</a></li>
						<?php } ?>
					<?php } else { ?>
						<?php if (function_exists('the_views')) { ?>
							<?php get_timespan_most_viewed('post',$instance["viewe_number"],$instance["viewe_days"], true, true); ?>
						<?php } else { ?>
							<li><a href="https://wordpress.org/plugins/wp-postviews/" rel="external nofollow" target="_blank">需要开启文章浏览统计</a></li>
						<?php } ?>
					<?php } ?>
					<?php wp_reset_query(); ?>
				</ul><!--/.zm-tab-->
			</div>

		<?php } ?>

		<?php if($instance['comments_enable']) { // Recent comments enabled? ?>

			<?php $comments = get_comments(array('number'=>$instance["comments_num"],'status'=>'approve','post_status'=>'publish')); ?>
			<div class="message-tab message-widget">
				<ul>
					<h4><?php _e( '最近留言', 'begin' ); ?></h4>
					<?php 
				
					$no_comments = false;
					$avatar_size = 64;
					$comments_query = new WP_Comment_Query();
					$comments = $comments_query->query( array_merge( array( 'number' => $instance["comments_num"], 'status' => 'approve', 'type' => 'comments', 'post_status' => 'publish', 'author__not_in' => explode(',',$instance["authornot"]) ) ) );
					if ( $comments ) : foreach ( $comments as $comment ) : ?>

					<li>
						<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="发表在：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
							<?php if($instance['comments_avatars']) : ?>
							<?php if (zm_get_option('cache_avatar')) { ?>
								<?php echo begin_avatar( $comment->comment_author_email, $avatar_size ); ?>
							<?php } else { ?>
								<?php echo get_avatar( $comment->comment_author_email, $avatar_size ); ?>
							<?php } ?>
							<?php endif; ?>
							<span class="comment_author"><strong><?php echo get_comment_author( $comment->comment_ID ); ?></strong></span>
							<?php echo convert_smilies($comment->comment_content); ?>
						</a>
					</li>

					<?php endforeach; else : ?>
						<li><?php _e('暂无留言', 'begin'); ?></li>
						<?php $no_comments = true;
					endif; ?>
				</ul>
			</div>
		<?php } ?>

	</div>

<?php
		$output .= ob_get_clean();
		$output .= $after_widget."\n";
		echo $output;
	}

/*  Widget update
/* ------------------------------------ */
	public function update($new,$old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		$instance['tabs_category'] = $new['tabs_category']?1:0;
		$instance['tabs_date'] = $new['tabs_date']?1:0;
	// Recent posts
		$instance['recent_enable'] = $new['recent_enable']?1:0;
		$instance['recent_thumbs'] = $new['recent_thumbs']?1:0;
		$instance['recent_cat_id'] = strip_tags($new['recent_cat_id']);
		$instance['recent_num'] = strip_tags($new['recent_num']);
	// Popular posts
		$instance['popular_enable'] = $new['popular_enable']?1:0;
		$instance['popular_thumbs'] = $new['popular_thumbs']?1:0;
		$instance['popular_cat_id'] = strip_tags($new['popular_cat_id']);
		$instance['popular_time'] = strip_tags($new['popular_time']);
		$instance['popular_num'] = strip_tags($new['popular_num']);
	// Recent comments
		$instance['comments_enable'] = $new['comments_enable']?1:0;
		$instance['comments_avatars'] = $new['comments_avatars']?1:0;
		$instance['comments_num'] = strip_tags($new['comments_num']);
		$instance['authornot'] = strip_tags($new['authornot']);
	// viewe
		$instance['viewe_enable'] = $new['viewe_enable']?1:0;
		$instance['viewe_thumbs'] = $new['viewe_thumbs']?1:0;
		$instance['viewe_number'] = strip_tags($new['viewe_number']);
		$instance['viewe_days'] = strip_tags($new['viewe_days']);
		return $instance;
	}

/*  Widget form
/* ------------------------------------ */
	public function form($instance) {
		// Default widget settings
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

	<style>
	.widget .widget-inside .zm-options-tabs .postform { width: 100%; }
	.widget .widget-inside .zm-options-tabs p { margin: 3px 0; }
	.widget .widget-inside .zm-options-tabs hr { margin: 20px 0 10px; }
	.widget .widget-inside .zm-options-tabs h4 { margin-bottom: 10px; }
	</style>

	<div class="zm-options-tabs">
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">标题：</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
		</p>

		<h4>最新文章</h4>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('recent_enable') ); ?>" name="<?php echo esc_attr( $this->get_field_name('recent_enable') ); ?>" <?php checked( (bool) $instance["recent_enable"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('recent_enable') ); ?>">显示最新文章</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('recent_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('recent_thumbs') ); ?>" <?php checked( (bool) $instance["recent_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('recent_thumbs') ); ?>">显示缩略图</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'recent_num' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'recent_num' ); ?>" name="<?php echo $this->get_field_name( 'recent_num' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['recent_num']; ?>" size="3" />
		</p>
		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("recent_cat_id") ); ?>">选择分类：</label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("recent_cat_id"), 'selected' => $instance["recent_cat_id"], 'show_option_all' => '全部分类', 'show_count' => true ) ); ?>
		</p>

		<h4>热评文章</h4>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('popular_enable') ); ?>" name="<?php echo esc_attr( $this->get_field_name('popular_enable') ); ?>" <?php checked( (bool) $instance["popular_enable"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('popular_enable') ); ?>">显示热评文章</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('popular_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('popular_thumbs') ); ?>" <?php checked( (bool) $instance["popular_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('popular_thumbs') ); ?>">显示缩略图</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'popular_num' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'popular_num' ); ?>" name="<?php echo $this->get_field_name( 'popular_num' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['popular_num']; ?>" size="3" />
		</p>
		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("popular_cat_id") ); ?>">选择分类：</label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("popular_cat_id"), 'selected' => $instance["popular_cat_id"], 'show_option_all' => '全部分类', 'show_count' => true ) ); ?>
		</p>
		<p style="padding-top: 0.3em;">
			<label style="width: 100%; display: inline-block;" for="<?php echo esc_attr( $this->get_field_id("popular_time") ); ?>">选择时间段：</label>
			<select style="width: 100%;" id="<?php echo esc_attr( $this->get_field_id("popular_time") ); ?>" name="<?php echo esc_attr( $this->get_field_name("popular_time") ); ?>">
				<option value="0"<?php selected( $instance["popular_time"], "0" ); ?>>全部</option>
				<option value="1 year ago"<?php selected( $instance["popular_time"], "1 year ago" ); ?>>一年内</option>
				<option value="1 month ago"<?php selected( $instance["popular_time"], "1 month ago" ); ?>>一月内</option>
				<option value="1 week ago"<?php selected( $instance["popular_time"], "1 week ago" ); ?>>一周内</option>
				<option value="1 day ago"<?php selected( $instance["popular_time"], "1 day ago" ); ?>>24小时内</option>
			</select>
		</p>

		<h4>热门文章</h4>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('viewe_enable') ); ?>" name="<?php echo esc_attr( $this->get_field_name('viewe_enable') ); ?>" <?php checked( (bool) $instance["viewe_enable"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('viewe_enable') ); ?>">显示热门文章</label>
		</p>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('viewe_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('viewe_thumbs') ); ?>" <?php checked( (bool) $instance["viewe_thumbs"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('viewe_thumbs') ); ?>">显示缩略图</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'viewe_number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'viewe_number' ); ?>" name="<?php echo $this->get_field_name( 'viewe_number' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['viewe_number']; ?>" size="3" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'viewe_days' ); ?>">时间限定（天）：</label>
			<input class="number-text-d" id="<?php echo $this->get_field_id( 'viewe_days' ); ?>" name="<?php echo $this->get_field_name( 'viewe_days' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['viewe_days']; ?>" size="3" />
		</p>

		<h4>最新留言</h4>

		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('comments_enable') ); ?>" name="<?php echo esc_attr( $this->get_field_name('comments_enable') ); ?>" <?php checked( (bool) $instance["comments_enable"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('comments_enable') ); ?>">显示最新留言</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('comments_avatars') ); ?>" name="<?php echo esc_attr( $this->get_field_name('comments_avatars') ); ?>" <?php checked( (bool) $instance["comments_avatars"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('comments_avatars') ); ?>">显示头像</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id("authornot") ); ?>">排除的用户ID：</label>
			<input id="<?php echo esc_attr( $this->get_field_id("authornot") ); ?>" name="<?php echo esc_attr( $this->get_field_name("authornot") ); ?>" type="text" value="<?php echo absint($instance["authornot"]); ?>" size='3' />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'comments_num' ); ?>">显示数量：</label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'comments_num' ); ?>" name="<?php echo $this->get_field_name( 'comments_num' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['comments_num']; ?>" size="3" />
		</p>
	</div>
<?php
}
}

function zm_register_widget_tabs() {
	register_widget( 'zmTabs' );
}
add_action( 'widgets_init', 'zm_register_widget_tabs' );

// 网站概况
class site_profile extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'site_profile',
			'description' => __( '网站概况' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('site_profile', '网站概况', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$time = strip_tags($instance['time']) ? absint( $instance['time'] ) : 2007-8-1;
?>

<div class="site-profile">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<li><?php _e( '文章', 'begin' ); ?><span><?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?></span></li>
		<li><?php _e( '分类', 'begin' ); ?><span><?php echo $count_categories = wp_count_terms('category'); ?></span></li>
		<li><?php _e( '标签', 'begin' ); ?><span><?php echo $count_tags = wp_count_terms('post_tag'); ?></span></li>
		<li><?php _e( '留言', 'begin' ); ?><span><?php $my_email = get_bloginfo ( 'admin_email' ); global $wpdb; echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments where comment_author_email!='$my_email'");?></span></li>
		<li><?php _e( '链接', 'begin' ); ?><span><?php global $wpdb; echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->links WHERE link_visible = 'Y'"); ?></span></li>
		<li><?php _e( '运行', 'begin' ); ?><span><?php echo floor((time()-strtotime($instance['time']))/86400); ?> 天</span></li>
		<li><?php _e( '浏览', 'begin' ); ?><span><?php echo all_view(); ?></span></li>
		<li><?php _e( '更新', 'begin' ); ?><span><?php global $wpdb; $last =$wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");$last = date('Y-n-j', strtotime($last[0]->MAX_m));echo $last; ?></span></li>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['time'] = strip_tags($new_instance['time']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '网站概况';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('time' => '2007-8-1'));
		$time = strip_tags($instance['time']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p><label for="<?php echo $this->get_field_id('time'); ?>">建站日期：</label>
	<input id="<?php echo $this->get_field_id( 'time' ); ?>" name="<?php echo $this->get_field_name( 'time' ); ?>" type="text" value="<?php echo $time; ?>" size="10" /> 格式：2007-8-1</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function site_profile_init() {
	register_widget( 'site_profile' );
}
add_action( 'widgets_init', 'site_profile_init' );

// 热门文章
class hot_post_img extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_post_img',
			'description' => __( '调用点击最多的文章，安装 wp-postviews 插件,并有统计数据' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_post_img', '热门文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<?php if($instance['show_thumbs']) { ?>
<div id="hot_post_widget" class="new_cat">
<?php } else { ?>
<div id="hot_post_widget">
<?php } ?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
	    <?php if( function_exists( 'the_views' ) ): ?>
		<?php if($instance['show_thumbs']) { ?>
			<?php get_timespan_most_viewed_img('post',$number,$days, true, true); ?>
		<?php } else { ?>
		    <?php get_timespan_most_viewed('post',$number,$days, true, true); ?>
		<?php } ?>
		<?php wp_reset_query(); ?>
	    <?php endif; ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热门文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>">时间限定（天）：</label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function hot_post_img_init() {
	register_widget( 'hot_post_img' );
}
add_action( 'widgets_init', 'hot_post_img_init' );

// 大家喜欢
class like_most_img extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'like_most_img',
			'description' => __( '调用点赞最多的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('like_most_img', '大家喜欢', $widget_ops);
	}
	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}


	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<?php if($instance['show_thumbs']) { ?>
<div id="like" class="new_cat">
<?php } else { ?>
<div id="like" class="like_most">
<?php } ?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php if($instance['show_thumbs']) { ?>
			<?php get_like_most_img('post',$number,$days, true, true); ?>
		<?php } else { ?>
			<?php get_like_most('post',$number,$days, true, true); ?>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['days'] = strip_tags($new_instance['days']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '大家喜欢';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p>
		 <label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	 </p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'days' ); ?>">时间限定（天）：</label>
		<input class="number-text-d" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="number" step="1" min="1" value="<?php echo $days; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
function like_most_img_init() {
	register_widget( 'like_most_img' );
}
add_action( 'widgets_init', 'like_most_img_init' );

// Ajax组合小工具
if ( !class_exists('ajax_widget') ) {
	class ajax_widget extends WP_Widget {
		function __construct() {
			// ajax functions
			add_action('wp_ajax_ajax_widget_content', array(&$this, 'ajax_ajax_widget_content'));
			add_action('wp_ajax_nopriv_ajax_widget_content', array(&$this, 'ajax_ajax_widget_content'));
			// css
			add_action('wp_enqueue_scripts', array(&$this, 'ajax_register_scripts'));
			$widget_ops = array('classname' => 'widget_ajax', 'description' => __('最新文章、热门文章、推荐文章、热门文章等', 'begin-tab'));
			$control_ops = array('width' => 300, 'height' => 350);
			parent::__construct('ajax_widget', __('Ajax组合小工具', 'begin-tab'), $widget_ops, $control_ops);
		}
	    function ajax_register_scripts() { 
			// JS 
			wp_register_script( 'ajax_widget', get_template_directory_uri() . "/js/ajax-tab.js", array('jquery') );
			wp_localize_script( 'ajax_widget', 'ajax',
				array( 'ajax_url' => admin_url( 'admin-ajax.php' ))
			);
	    }

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 
				'tabs' => array('recent' => 1, 'popular' => 1, 'hot' => 1, 'review' => 1, 'comments' => 1, 'recommend' => 1), 
				'tab_order' => array('recent' => 1, 'popular' => 2, 'hot' => 3, 'review' => 4, 'comments' => 5, 'recommend' => 6),
				'allow_pagination' => 1,
				'post_num' => '5', 
				'comment_num' => '12',
				'show_thumb' => '1', 
				'viewe_days' => '90',
				'authornot' => '1',
				'review_days' => '3',
				'like_days' => '90', 
			) );
			
			extract($instance);
			?>

			<div class="ajax_options_form">

		        <h4><?php _e('选择', 'begin-tab'); ?></h4>
		        
				<div class="ajax_select_tabs">
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_recent">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_recent" name="<?php echo $this->get_field_name("tabs"); ?>[recent]" value="1" <?php if (isset($tabs['recent'])) { checked( 1, $tabs['recent'], true ); } ?> />		
						<?php _e( '最新文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px" for="<?php echo $this->get_field_id("tabs"); ?>_popular">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_popular" name="<?php echo $this->get_field_name("tabs"); ?>[popular]" value="1" <?php if (isset($tabs['popular'])) { checked( 1, $tabs['popular'], true ); } ?> />
						<?php _e( '大家喜欢', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_hot">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_hot" name="<?php echo $this->get_field_name("tabs"); ?>[hot]" value="1" <?php if (isset($tabs['hot'])) { checked( 1, $tabs['hot'], true ); } ?> />
						<?php _e( '热门文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_review">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_review" name="<?php echo $this->get_field_name("tabs"); ?>[review]" value="1" <?php if (isset($tabs['review'])) { checked( 1, $tabs['review'], true ); } ?> />
						<?php _e( '热评文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_comments">
						<input type="checkbox" class="checkbox ajax_enable_comments" id="<?php echo $this->get_field_id("tabs"); ?>_comments" name="<?php echo $this->get_field_name("tabs"); ?>[comments]" value="1" <?php if (isset($tabs['comments'])) { checked( 1, $tabs['comments'], true ); } ?> />
						<?php _e( '最近留言', 'begin-tab'); ?>
					</label>
					<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_recommend">
						<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_recommend" name="<?php echo $this->get_field_name("tabs"); ?>[recommend]" value="1" <?php if (isset($tabs['recommend'])) { checked( 1, $tabs['recommend'], true ); } ?> />
						<?php _e( '推荐阅读', 'begin-tab'); ?>
					</label>
				</div>
				<div class="clear"></div>

				<h4 class="ajax_tab_order_header"><?php _e('顺序', 'begin-tab'); ?></h4>

				<div class="ajax_tab_order">
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_recent" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_recent" name="<?php echo $this->get_field_name('tab_order'); ?>[recent]" type="number" min="1" step="1" value="<?php echo $tab_order['recent']; ?>" style="width: 48px;" />
						<?php _e('最新文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_popular" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_popular" name="<?php echo $this->get_field_name('tab_order'); ?>[popular]" type="number" min="1" step="1" value="<?php echo $tab_order['popular']; ?>" style="width: 48px;" />
						<?php _e('大家喜欢', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_hot" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_hot" name="<?php echo $this->get_field_name('tab_order'); ?>[hot]" type="number" min="1" step="1" value="<?php echo $tab_order['hot']; ?>" style="width: 48px;" />
						<?php _e('热门文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_review" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_review" name="<?php echo $this->get_field_name('tab_order'); ?>[review]" type="number" min="1" step="1" value="<?php echo $tab_order['review']; ?>" style="width: 48px;" />
						<?php _e('热评文章', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_comments" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_comments" name="<?php echo $this->get_field_name('tab_order'); ?>[comments]" type="number" min="1" step="1" value="<?php echo $tab_order['comments']; ?>" style="width: 48px;" />
						<?php _e('最近留言', 'begin-tab'); ?>
					</label>
					<label class="alignleft" for="<?php echo $this->get_field_id('tab_order'); ?>_recommend" style="width: 50%;">
						<input id="<?php echo $this->get_field_id('tab_order'); ?>_recommend" name="<?php echo $this->get_field_name('tab_order'); ?>[recommend]" type="number" min="1" step="1" value="<?php echo $tab_order['recommend']; ?>" style="width: 48px;" />
						<?php _e('推荐阅读', 'begin-tab'); ?>
					</label>
				</div>
				<div class="clear"></div>

				<h4 class="ajax_advanced_options_header"><?php _e('选项', 'begin-tab'); ?></h4>

				<div class="ajax_advanced_options">
			        <p>
						<label for="<?php echo $this->get_field_id("allow_pagination"); ?>">
							<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("allow_pagination"); ?>" name="<?php echo $this->get_field_name("allow_pagination"); ?>" value="1" <?php if (isset($allow_pagination)) { checked( 1, $allow_pagination, true ); } ?> />
							<?php _e( '显示翻页', 'begin-tab'); ?>
						</label>
					</p>

						<p>
							<label for="<?php echo $this->get_field_id("show_thumb"); ?>">
								<input type="checkbox" class="checkbox ajax_show_thumbnails" id="<?php echo $this->get_field_id("show_thumb"); ?>" name="<?php echo $this->get_field_name("show_thumb"); ?>" value="1" <?php if (isset($show_thumb)) { checked( 1, $show_thumb, true ); } ?> />
								<?php _e( '显示缩略图', 'begin-tab'); ?>
							</label>
						</p>

					<div class="ajax_post_options">

						<p>
							<label for="<?php echo $this->get_field_id('post_num'); ?>"><?php _e('显示数量：', 'begin-tab'); ?>
								<input class="number-text" id="<?php echo $this->get_field_id('post_num'); ?>" name="<?php echo $this->get_field_name('post_num'); ?>" type="number" min="1" step="1" value="<?php echo $post_num; ?>" />
							</label>
						</p>

					    <p>
							<label for="<?php echo $this->get_field_id('comment_num'); ?>">
								<?php _e('评论显示数量：', 'begin-tab'); ?>
								<input class="number-text" type="number" min="1" step="1" id="<?php echo $this->get_field_id('comment_num'); ?>" name="<?php echo $this->get_field_name('comment_num'); ?>" value="<?php echo $comment_num; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('like_days'); ?>"><?php _e('大家喜欢时间限定（天）：', 'begin-tab'); ?>
								<input class="number-text-d" id="<?php echo $this->get_field_id('like_days'); ?>" name="<?php echo $this->get_field_name('like_days'); ?>" type="number" min="1" step="1" value="<?php echo $like_days; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('viewe_days'); ?>"><?php _e('热门文章时间限定（天）：', 'begin-tab'); ?>
								<input class="number-text-d" id="<?php echo $this->get_field_id('viewe_days'); ?>" name="<?php echo $this->get_field_name('viewe_days'); ?>" type="number" min="1" step="1" value="<?php echo $viewe_days; ?>" />
							</label>
						</p>
					
						<p>
							<label for="<?php echo $this->get_field_id('review_days'); ?>"><?php _e('热评文章时间限定（月）：', 'begin-tab'); ?>
								<input class="number-text-d" id="<?php echo $this->get_field_id('review_days'); ?>" name="<?php echo $this->get_field_name('review_days'); ?>" type="number" min="1" step="1" value="<?php echo $review_days; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('authornot'); ?>"><?php _e('评论排除的用户ID：', 'begin-tab'); ?>
								<br />
								<input id="<?php echo $this->get_field_id('authornot'); ?>" name="<?php echo $this->get_field_name('authornot'); ?>" type="text" value="<?php echo $authornot; ?>" />
							</label>
						</p>

						<p>
							<label for="<?php echo $this->get_field_id('pcat'); ?>"><?php _e('最新文章排除的分类ID，如：-1,-3：', 'begin-tab'); ?>
								<br />
								<input id="<?php echo $this->get_field_id('pcat'); ?>" name="<?php echo $this->get_field_name('pcat'); ?>" type="text" value="<?php echo $pcat; ?>" />
							</label>
						</p>
					</div>
				</div><!-- .ajax_advanced_options -->
			</div><!-- .ajax_options_form -->
			<?php 
		}
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['tabs'] = $new_instance['tabs'];
			$instance['tab_order'] = $new_instance['tab_order']; 
			$instance['allow_pagination'] = $new_instance['allow_pagination'];
			$instance['post_num'] = $new_instance['post_num'];
			$instance['comment_num'] =  $new_instance['comment_num'];
			$instance['viewe_days'] =  $new_instance['viewe_days'];
			$instance['review_days'] =  $new_instance['review_days'];
			$instance['like_days'] =  $new_instance['like_days'];
			$instance['show_thumb'] = $new_instance['show_thumb'];
			$instance['pcat'] = $new_instance['pcat'];
			$instance['authornot'] = $new_instance['authornot'];
			return $instance;
		}
		function widget( $args, $instance ) {
			extract($args);
			extract($instance);
			wp_enqueue_script('ajax_widget');
			wp_enqueue_style('ajax_widget');
			if (empty($tabs)) $tabs = array('recent' => 1, 'popular' => 1);
			$tabs_count = count($tabs);
			if ($tabs_count <= 1) {
				$tabs_count = 1;
			} elseif($tabs_count > 5) {
				$tabs_count = 6;
			}

			$available_tabs = array(
				'recent' => __('最新文章', 'begin'), 
				'popular' => __('大家喜欢', 'begin'), 
				'hot' => __('热门文章', 'begin'), 
				'review' => __('热评文章', 'begin'), 
				'comments' => __('最近留言', 'begin'),
				'recommend' => __('推荐阅读', 'begin'));
			array_multisort($tab_order, $available_tabs);
			?>

			<?php echo $before_widget; ?>
			<div class="ajax_widget_content" id="<?php echo $widget_id; ?>_content" data-widget-number="<?php echo esc_attr( $this->number ); ?>">
				<div class="ajax-tabs <?php echo "has-$tabs_count-"; ?>tabs">
					<?php foreach ($available_tabs as $tab => $label) { ?>
						<?php if (!empty($tabs[$tab])): ?>
							<span class="tab_title"><a href="#" title="<?php echo $label; ?>" id="<?php echo $tab; ?>-tab"></a></span>
						<?php endif; ?>
					<?php } ?> 
					<div class="clear"></div>
				</div>
				<!--end .tabs-->

				<div class="clear"></div>

				<div class="new_cat">
					<?php if (!empty($tabs['popular'])): ?>
						<div id="popular-tab-content" class="tab-content">
						</div> <!--end #popular-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['recent'])): ?>	
						<div id="recent-tab-content" class="tab-content">
						</div> <!--end #recent-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['recommend'])): ?>
						<div id="recommend-tab-content" class="tab-content">
							<ul></ul>
						</div> <!--end #recommend-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['hot'])): ?>
						<div id="hot-tab-content" class="tab-content">
							<ul></ul>
						</div> <!--end #tags-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['review'])): ?>
						<div id="review-tab-content" class="tab-content">
							<ul></ul>
						</div> <!--end #tags-tab-content-->
					<?php endif; ?>

					<?php if (!empty($tabs['comments'])): ?>
						<div id="comments-tab-content" class="tab-content">
							<ul></ul>
						</div> <!--end #comments-tab-content-->
					<?php endif; ?>


					<div class="clear"></div>
				</div> <!--end .inside -->

				<div class="clear"></div>
			</div><!--end #tabber -->
			<?php 
			// inline script 
			// to support multiple instances per page with different settings
			unset($instance['tabs'], $instance['tab_order']); // unset unneeded
			?>

			<script type="text/javascript">
				jQuery(function($) { 
					$('#<?php echo $widget_id; ?>_content').data('args', <?php echo json_encode($instance); ?>);
				});
			</script>

			<?php echo $after_widget; ?>
			<?php 
		}

		function ajax_ajax_widget_content() {
			$tab = $_POST['tab'];
			$args = $_POST['args'];
	    	$number = intval( $_POST['widget_number'] );
			$page = intval($_POST['page']);
			if ($page < 1)
				$page = 1;

			if ( !is_array( $args ) || empty( $args ) ) { // json_encode() failed
				$ajax_widgets = new ajax_widget();
				$settings = $ajax_widgets->get_settings();

				if ( isset( $settings[ $number ] ) ) {
					$args = $settings[ $number ];
				} else {
					die( __('出错了！', 'begin-tab') );
				}
			}

			// sanitize args
			$post_num = (empty($args['post_num']) ? 5 : intval($args['post_num']));
			if ($post_num > 20 || $post_num < 1) { // max 20 posts
				$post_num = 5;
			}
			$comment_num = (empty($args['comment_num']) ? 5 : intval($args['comment_num']));
			if ($comment_num > 20 || $comment_num < 1) {
				$comment_num = 5;
			}
			$viewe_days = (empty($args['viewe_days']) ? 90 : intval($args['viewe_days']));
			$review_days = (empty($args['review_days']) ? 3 : intval($args['review_days']));
			$like_days = (empty($args['like_days']) ? 90 : intval($args['like_days']));
			$show_thumb = !empty($args['show_thumb']);
			$pcat = strip_tags($args['pcat']);
			$authornot = (empty($args['authornot']) ? 1 : intval($args['authornot']));
			$allow_pagination = !empty($args['allow_pagination']);
	        
			/* ---------- Tab Contents ---------- */
			switch ($tab) { 

				/* ---------- Recent Posts ---------- */ 
				case "recent":
					?>
					<ul>
						<h4><?php _e( '最新文章', 'begin' ); ?></h4>
							<?php $recent = new WP_Query('posts_per_page='. $post_num .'&orderby=post_date&order=desc&post_status=publish&cat='.$pcat.'&paged='. $page); ?>
							<?php $last_page = $recent->max_num_pages; while ($recent->have_posts()) : $recent->the_post(); ?>
							<li>
								<?php if ( $show_thumb == 1 ) { ?>
									<span class="thumbnail">
										<?php zm_thumbnail_a(); ?>
									</span>

									<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
									<span class="date"><?php the_time('m/d') ?></span>
									<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
								<?php } else { ?>
									<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
								<?php } ?>
								<div class="clear"></div>
							</li>
						<?php endwhile; wp_reset_query(); ?>
					</ul>
	                <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>
					<?php 
				break;

				/* ---------- Popular Posts ---------- */
				case "popular":
					?>
					<ul> 
						<h4><?php _e( '大家喜欢', 'begin' ); ?></h4>
						<?php 
						$date_query=array(
							array(
								'column' => 'post_date',
								'before' => date('Y-m-d H:i',time()),
								'after' =>date('Y-m-d H:i',time()-3600*24*$viewe_days)
							)
						);
						$args=array(
						'meta_key' => 'zm_like',
						'orderby' => 'meta_value_num',
						'posts_per_page'=>$post_num,
						'date_query' => $like_days,
						'paged' => $page,
						'order' => 'DESC'
						);
						query_posts($args); while (have_posts()) : the_post();
						?>
						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php zm_thumbnail_a(); ?>
								</span>

								<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
								<span class="date"><?php the_time('m/d') ?></span>
								<span class="discuss"><i class="be be-thumbs-up-o"></i><?php zm_get_current_count(); ?></span>
							<?php } else { ?>
								<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
							<?php } ?>
							<div class="clear"></div>
						</li>
						<?php endwhile;wp_reset_query(); ?>
					</ul>

		            <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;

				/* ---------- hot ---------- */
				case "hot":
					?> 
					<ul> 
						<h4><?php _e( '热门文章', 'begin' ); ?></h4>
						<?php 
						$date_query=array(
							array(
								'column' => 'post_date',
								'before' => date('Y-m-d H:i',time()),
								'after' =>date('Y-m-d H:i',time()-3600*24*$viewe_days)
							)
						);
						$args=array(
						'meta_key' => 'views',
						'orderby' => 'meta_value_num',
						'post_status' => 'publish',
						'posts_per_page'=>$post_num,
						'date_query' => $date_query,
						'paged' => $page,
						'order' => 'DESC'
						);
						query_posts($args); while (have_posts()) : the_post();
						?>
						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php zm_thumbnail_a(); ?>
								</span>

							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<span class="date"><?php the_time('m/d') ?></span>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
							<?php } else { ?>
								<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
							<?php } ?>
							<div class="clear"></div>
						</li>
						<?php endwhile;wp_reset_query(); ?>
					</ul>

		            <div class="clear"></div>
					<?php if ($allow_pagination) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;

				/* ---------- Latest recommend ---------- */
				case "recommend":
					?> 
					<ul>
						<h4><?php _e( '推荐阅读', 'begin' ); ?></h4>
						<?php query_posts( array ( 'meta_key' => 'hot', 'showposts' => $post_num, 'ignore_sticky_posts' => 1, 'paged' => $page ) ); while ( have_posts() ) : the_post(); ?>
							<li>
								<?php if ( $show_thumb == 1 ) { ?>
									<span class="thumbnail">
										<?php zm_thumbnail_a(); ?>
									</span>

								<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
								<span class="date"><?php the_time('m/d') ?></span>
								<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
								<?php } else { ?>
									<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
								<?php } ?>
								<div class="clear"></div>
							</li>
						<?php endwhile;?>
						<?php wp_reset_query(); ?>
					</ul>

	                <div class="clear"></div>
					<?php if ($allow_pagination && !$no_comments) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;

				/* ---------- Latest comments ---------- */
				case "comments":
					?> 
					<div class="message-tab message-widget">
						<ul>
							<h4><?php _e( '最近留言', 'begin' ); ?></h4>
							<?php 
							$no_comments = false;
							$avatar_size = 64;
							$comment_length = 90;
							$comment_args = apply_filters(
								'wpt_comments_tab_args',
								array(
									'type' => 'comments',
									'status' => 'approve'
								)
							);
							$comments_total = new WP_Comment_Query();
							$comments_total_number = $comments_total->query( array_merge( array('count' => 1 ), $comment_args ) );
							$last_page = (int) ceil($comments_total_number / $comment_num);
							$comments_query = new WP_Comment_Query();
							$offset = ($page-1) * $comment_num;
							$comments = $comments_query->query( array_merge( array( 'number' => $comment_num, 'offset' => $offset, 'author__not_in' => explode( ',',$authornot ) ), $comment_args ) );
							if ( $comments ) : foreach ( $comments as $comment ) : ?>

							<li>
								<a href="<?php echo get_permalink($comment->comment_post_ID); ?>#anchor-comment-<?php echo $comment->comment_ID; ?>" title="发表在：<?php echo get_the_title($comment->comment_post_ID); ?>" rel="external nofollow">
									<?php if (zm_get_option('cache_avatar')) { ?>
										<?php echo begin_avatar( $comment->comment_author_email, $avatar_size ); ?>
									<?php } else { ?>
										<?php echo get_avatar( $comment->comment_author_email, $avatar_size ); ?>
									<?php } ?>
									<span class="comment_author"><strong><?php echo get_comment_author( $comment->comment_ID ); ?></strong></span>
									<?php echo convert_smilies($comment->comment_content); ?>
								</a>
							</li>

							<?php endforeach; else : ?>
								<li><?php _e('暂无留言', 'begin'); ?></li>
								<?php $no_comments = true;
							endif; ?>
						</ul>
					</div>
	                <div class="clear"></div>
					<?php if ($allow_pagination && !$no_comments) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>
					<?php 
				break;

				/* ---------- Latest review ---------- */
				case "review":
					?>

					<?php
						$review = new WP_Query( array(
							'post_type' => array( 'post' ),
							'showposts' => $post_num,
							'ignore_sticky_posts' => true,
							'orderby' => 'comment_count',
							'post_status' => 'publish',
							'order' => 'dsc',
							'paged' => $page,
							'date_query' => array(
								array(
									'after' => ''.$review_days. 'month ago',
								),
							),
						) );
					?>

					<ul>
						<h4><?php _e( '热评文章', 'begin' ); ?></h4>
						<?php while ( $review->have_posts() ): $review->the_post(); ?>
						<li>
							<?php if ( $show_thumb == 1 ) { ?>
								<span class="thumbnail">
									<?php zm_thumbnail_a(); ?>
								</span>

							<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
							<span class="date"><?php the_time('m/d') ?></span>
							<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' ); ?></span>
						<?php } else { ?>
							<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
						<?php } ?>
						<div class="clear"></div>
					</li>
						<?php endwhile;?>
						<?php wp_reset_query(); ?>
					</ul>

	                <div class="clear"></div>
					<?php if ($allow_pagination && !$no_comments) : ?>
						<?php $this->tab_pagination($page, $last_page); ?>
					<?php endif; ?>

					<?php 
				break;
			} 
			die(); // required to return a proper result
		}
		function tab_pagination($page, $last_page) {
			?>
			<div class="ajax-pagination">
				<div class="clear"></div>
				<?php if ($page > 1) : ?>
					<a href="#" class="previous"><span><i class="be be-arrowleft"></i><?php _e('上页', 'begin'); ?></span></a>
				<?php endif; ?>
				<?php if ($page != $last_page) : ?>
					<a href="#" class="next"><span><?php _e('下页', 'begin'); ?></span></a>
				<?php endif; ?>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<input type="hidden" class="page_num" name="page_num" value="<?php echo $page; ?>" />
			<?php 
		}
	}
}
add_action( 'widgets_init', 'ajax_widget_init' );
function ajax_widget_init() {
	register_widget( 'ajax_widget' );
}

// 今日更新
class mday_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'mday_post',
			'description' => __( '今日发表的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('mday_post', '今日更新', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="mday_post">
<?php } ?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
		$today = getdate();
		$args = array(
			'ignore_sticky_posts' => 1,
			'date_query' => array(
				array(
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
			),
			'posts_per_page' => $number,
		);
		$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail"><?php zm_thumbnail(); ?></span>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<span class="date"><?php the_time('m/d') ?></span>
				<span class="widget-cat"><i class="be be-folder"></i><?php zm_category(); ?></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<div class="clear"></div>
		</li>

		<?php endwhile;?>
		<?php wp_reset_query(); ?>
		<?php else : ?>
		<li>
			<span class="date"><?php echo $showtime=date("m/d");?></span>
			<span class="new-title-no">暂无更新</span>
		</li>
		<?php endif;?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '今日更新';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'mday_post_init' );
function mday_post_init() {
	register_widget( 'mday_post' );
}

// 本周更新
class week_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'week_post',
			'description' => __( '本周更新的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('week_post', '本周更新', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="mday_post">
<?php } ?>
	<ul>
		<?php
			$args = array(
				'ignore_sticky_posts' => 1,
				'date_query' => array(
					array(
						'year' => date( 'Y' ),
						'week' => date( 'W' ),
					),
				),
				'posts_per_page' => $number,
			);
			$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();?>
			<li>
				<?php if($instance['show_thumbs']) { ?>
					<span class="thumbnail"><?php zm_thumbnail(); ?></span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<span class="s-cat"><?php zm_category(); ?></span>
					<span class="date"><?php the_time('m/d') ?></span>
				<?php } else { ?>
					<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php } ?>
				<div class="clear"></div>
			</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
		<?php else : ?>
		<li>
			<span class="new-title-no">暂无更新</span>
		</li>
		<?php endif;?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '本周更新';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'week_post_init' );
function week_post_init() {
	register_widget( 'week_post' );
}

// 限定时间内的文章
class specified_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'specified_post',
			'description' => __( '调用限定时间内的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('specified_post', '限定时间文章', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<div class="new_cat">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			$args = array(
			'ignore_sticky_posts' => 1,
				'date_query' => array(
					array(
						'after'     =>  array(
							'year'  => $instance['from_y'],
							'month' => $instance['from_m'],
							'day'   => $instance['from_d'],
						),
						'before'    => array(
							'year'  => $instance['to_y'],
							'month' => $instance['to_m'],
							'day'   => $instance['to_d'],
						),
						'inclusive' => true,
					),
				),
				'posts_per_page' => $instance['numposts'],
				'cat' => $instance['sp_cat'],
			);
			$query = new WP_Query( $args );
		?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();?>
		<li>
			<span class="thumbnail"><?php zm_thumbnail(); ?></span>
			<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<span class="discuss"><?php comments_number( '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' ); ?></span>
			<span class="date"><?php the_time('m/d') ?></span>
			<div class="clear"></div>
		</li>

		<?php endwhile;?>
		<?php wp_reset_query(); ?>
		<?php else : ?>
		<li>
			<span class="new-title-no">暂无文章</span>
		</li>
		<?php endif;?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title_z'] = strip_tags($new_instance['title_z']);
	$instance['show_icon'] = strip_tags($new_instance['show_icon']);
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['sp_cat'] = $new_instance['sp_cat'];
	$instance['from_y'] = $new_instance['from_y'];
	$instance['from_m'] = $new_instance['from_m'];
	$instance['from_d'] = $new_instance['from_d'];
	$instance['to_y'] = $new_instance['to_y'];
	$instance['to_m'] = $new_instance['to_m'];
	$instance['to_d'] = $new_instance['to_d'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '限定时间文章',
		'numposts' => 5,
		'from_y' => 2017,
		'from_m' => 1,
		'from_d' => 2,
		'to_y' => 2017,
		'to_m' => 5,
		'to_d' => 31,
		'sp_cat' => 0)); ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
		</p>
		<h4 class="from_m_options_header"><?php _e('输入起止日期', 'begin-tab'); ?></h4>

		<p>
			<label for="<?php echo $this->get_field_id('from_y'); ?>" style="width: 33%;">从 
			<input id="<?php echo $this->get_field_id('from_y'); ?>" name="<?php echo $this->get_field_name('from_y'); ?>" type="text" value="<?php echo $instance['from_y']; ?>" size="3" /> 年 
			</label>
			<label for="<?php echo $this->get_field_id('from_m'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('from_m'); ?>" name="<?php echo $this->get_field_name('from_m'); ?>" type="text" value="<?php echo $instance['from_m']; ?>" size="3" /> 月 
			<label for="<?php echo $this->get_field_id('from_d'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('from_d'); ?>" name="<?php echo $this->get_field_name('from_d'); ?>" type="text" value="<?php echo $instance['from_d']; ?>" size="3" />日起
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('to_y'); ?>" style="width: 33%;">至 </label>
			<input id="<?php echo $this->get_field_id('to_y'); ?>" name="<?php echo $this->get_field_name('to_y'); ?>" type="text" value="<?php echo $instance['to_y']; ?>" size="3" /> 年 
			<label for="<?php echo $this->get_field_id('to_m'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('to_m'); ?>" name="<?php echo $this->get_field_name('to_m'); ?>" type="text" value="<?php echo $instance['to_m']; ?>" size="3" /> 月 
			<label for="<?php echo $this->get_field_id('to_d'); ?>" style="width: 33%;"></label>
			<input id="<?php echo $this->get_field_id('to_d'); ?>" name="<?php echo $this->get_field_name('to_d'); ?>" type="text" value="<?php echo $instance['to_d']; ?>" size="3" /> 日止
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sp_cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('sp_cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['sp_cat'])); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
<?php }
}
add_action( 'widgets_init', 'specified_post_init' );
function specified_post_init() {
	register_widget( 'specified_post' );
}

// 产品
class show_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'show_widget',
			'description' => __( '调用产品文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('show_widget', '最新产品', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'><span></span><span></span><span></span></span></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" rel="bookmark"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
<div class="picture img_widget">
	<?php
	    $args = array(
	        'post_type' => 'show',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'products',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php zm_thumbnail(); ?>
				<span class="show-t"></span>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
		$instance['hideTitle'] = isset($new_instance['hideTitle']);
		$instance['newWindow'] = isset($new_instance['newWindow']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['cat'] = $new_instance['cat'];
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新产品';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '4'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('titleUrl' => ''));
		$titleUrl = $instance['titleUrl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
		<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '选择分类', 'hide_empty'=>0, 'hierarchical'=>1,'taxonomy' => 'products', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 父子分类
function get_category_related_id($cat) {
	$this_category = get_category($cat);
	while($this_category->category_parent) {
		$this_category = get_category($this_category->category_parent);
	}
	return $this_category->term_id;
}

// 父子分类名称
class child_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'child_cat',
			'description' => __( '用于显示当前文章或者分类，同级分类' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('child_cat', '同级分类', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		if(get_category_children(get_category_related_id(the_category_ID(false)))!= "" ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
     	}
?>

<?php if(!is_page()) { ?>
<?php if(get_category_children(get_category_related_id(the_category_ID(false)))!= "" ) { ?>
	<div class="widget_categories related-cat">
		<?php
			echo '<ul class="cat_list">';
			echo wp_list_categories("child_of=".get_category_related_id(the_category_ID(false)). "&depth=0&hide_empty=0&use_desc_for_title=&hierarchical=0&title_li=&orderby=id&order=ASC");
			echo '</ul>';
		?>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php } ?>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			// $instance['author_url'] = $new_instance['author_url'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '父子分类';
		}
		global $wpdb;
		// $instance = wp_parse_args((array) $instance, array('author_url' => ''));
		// $author_url = $instance['author_url'];
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'child_cat_init' );
function child_cat_init() {
	register_widget( 'child_cat' );
}

// 同分类文章
class same_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'same_post',
			'description' => __( '调用相同分类的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('same_post', '同分类文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( is_single() ) {
			if ( is_single() ) : global $post; $categories = get_the_category(); foreach ($categories as $category) : 
			$title =  $category->name;
			endforeach; endif;
			wp_reset_query();
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		}
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$orderby = strip_tags($instance['orderby']) ? absint( $instance['orderby'] ) : ASC;
?>

<?php if ( is_single() ) { ?>
<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="post_cat">
<?php } ?>
	<ul>
		<?php
		if ( is_single() ) : global $post; $categories = get_the_category(); foreach ($categories as $category) : ?>
			<?php $posts = get_posts('numberposts='.$instance['number'].'&order='.$instance['orderby'].'&category='. $category->term_id); foreach($posts as $post) : ?>
			<?php if($instance['show_thumbs']) { ?>
			<li class="cat-title">
			<?php } else { ?>
			<li>
			<?php } ?>
				<?php if($instance['show_thumbs']) { ?>
					<span class="thumbnail">
						<?php zm_thumbnail(); ?>
					</span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<span class="date"><?php the_time('m/d') ?></span>
					<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
				<?php } else { ?>
					<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
				<?php } ?>
			</li>
			<?php endforeach; ?>
		<?php endforeach; endif; ?>
		<?php wp_reset_query(); ?>
	</ul>
	<div class="clear"></div>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['orderby'] = strip_tags($new_instance['orderby']);
			return $instance;
		}
	function form($instance) {
		global $wpdb;
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('orderby' => 'ASC'));
		$orderby = strip_tags($instance['orderby']);

?>
	<p>
		<label for="<?php echo $this->get_field_id('orderby'); ?>">文章排序：</label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
			<option value="ASC"<?php selected( $instance['orderby'], 'ASC' ); ?>>旧的在上面</option>
			<option value="DESC"<?php selected( $instance['orderby'], 'DESC' ); ?>>新的在上面</option>
		</select>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>

	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'same_post_init' );
function same_post_init() {
	register_widget( 'same_post' );
}

// 幻灯小工具
class slider_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'slider_post',
			'description' => __( '以幻灯形式调用指定的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('slider_post', '幻灯', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$postid = $instance['post_id'];
?>

<div id="slider-widge" class="slider-widge">
	<?php query_posts( array ( 'post__in' => explode(',',$postid), 'showposts' => $number, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>
	<div class="slider-widge-main">
		<?php zm_widge_thumbnail(); ?>
		<?php the_title( sprintf( '<div class="widge-caption"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></div>' ); ?>
	</div>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['post_id'] = strip_tags($new_instance['post_id']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '幻灯';
		}
		global $wpdb;
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id( 'post_id' ); ?>">输入文章ID：</label>
	<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['post_id'] ), ENT_QUOTES)); ?></textarea></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'slider_post_init' );
function slider_post_init() {
	register_widget( 'slider_post' );
}

// 同标签的文章
class tag_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'tag_post',
			'description' => __( '调用相同标签的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('tag_post', '同标签文章', $widget_ops);
	}

	public function zm_get_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}


	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$tag_id = strip_tags($instance['tag_id']);
?>

<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="tag_post">
<?php } ?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<ul>
		<?php $recent = new WP_Query( array( 'posts_per_page' =>$number, 'tag__in' => explode(',', $tag_id)) ); ?>
		<?php while($recent->have_posts()) : $recent->the_post(); ?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
			<?php } ?>
			<?php if($instance['show_thumbs']) { ?>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<?php if($instance['show_thumbs']) { ?>
				<span class="date"><?php the_time('m/d') ?></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
			<?php } ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['tag_id'] = strip_tags($new_instance['tag_id']);
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '同标签文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
		$tag_id = strip_tags($instance['tag_id']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
		</p>
	<p>
		<label for="<?php echo $this->get_field_id('tag_id'); ?>">输入调用的标签 ID：</label>
		<input class="widefat"  id="<?php echo $this->get_field_id( 'tag_id' ); ?>" name="<?php echo $this->get_field_name( 'tag_id' ); ?>" type="text" value="<?php echo $tag_id; ?>" size="15" /></p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="number-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'tag_post_init' );
function tag_post_init() {
	register_widget( 'tag_post' );
}

// 调用指定ID文章
class ids_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'ids_post',
			'description' => __( '调用指定ID的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('ids_post', '指定文章', $widget_ops);
	}

	public function zm_get_defaults() {
		return array(
			'show_thumbs'   => 1,
		);
	}


	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$id_post = strip_tags($instance['id_post']);
?>


<?php if($instance['show_thumbs']) { ?>
<div class="new_cat">
<?php } else { ?>
<div class="ids_post">
<?php } ?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php 
		$args = array(
			'ignore_sticky_posts' => 1,
			'post__in'   => explode(',', $id_post)
		);
		query_posts($args)
		 ?>
		<?php while (have_posts()) : the_post(); ?>
		<li>
			<?php if($instance['show_thumbs']) { ?>
				<span class="thumbnail">
					<?php zm_thumbnail(); ?>
				</span>
			<?php } ?>
			<?php if($instance['show_thumbs']) { ?>
				<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<?php } else { ?>
				<?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?>
			<?php } ?>
			<?php if($instance['show_thumbs']) { ?>
				<span class="date"><?php the_time('m/d') ?></span>
				<span class="widget-cat"><i class="be be-folder"></i><?php zm_category(); ?></span>
			<?php } ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['show_thumbs'] = $new_instance['show_thumbs']?1:0;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['id_post'] = strip_tags($new_instance['id_post']);
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_get_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '指定文章';
		}
		global $wpdb;
		$id_post = strip_tags($instance['id_post']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('id_post'); ?>">文章 ID：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'id_post' ); ?>" name="<?php echo $this->get_field_name( 'id_post' ); ?>" type="text" value="<?php echo $id_post; ?>" size="15" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_thumbs') ); ?>" <?php checked( (bool) $instance["show_thumbs"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_thumbs') ); ?>">显示缩略图</label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'ids_post_init' );
function ids_post_init() {
	register_widget( 'ids_post' );
}

// 折叠菜单小工具
class tree_menu extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'description' => __( '添加折叠树型菜单。' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'tree_menu', __('折叠菜单'), $widget_ops );
	}
	public function widget( $args, $instance ) {
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( !$nav_menu )
			return;

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo $args['before_widget'];
		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		$nav_menu_args = array(
			'fallback_cb' => '',
			'theme_location'	=> 'widget-tree',
			'menu_class'		=> 'tree-menu',
			'menu'        => $nav_menu
		);
		
		wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		return $instance;
	}

	public function form( $instance ) {
		global $wp_customize;
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus();
		?>
		<p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<?php
			if ( $wp_customize instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}
			?>
			<?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) ); ?>
		</p>
		<div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
							<?php echo esc_html( $menu->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php if ( $wp_customize instanceof WP_Customize_Manager ) : ?>
				<p class="edit-selected-nav-menu" style="<?php if ( ! $nav_menu ) { echo 'display: none;'; } ?>">
					<button type="button" class="button"><?php _e( 'Edit Menu' ) ?></button>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}
}

add_action( 'widgets_init', 'tree_menu_init' );
function tree_menu_init() {
	register_widget( 'tree_menu' );
}

// 分类模块
class t_img_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 't_img_cat',
			'description' => __( '显示全部分类或某个分类的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('t_img_cat', '分类模块', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_icon'   => 0,
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		$titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
		$newWindow = !empty($instance['newWindow']) ? true : false;
		echo $before_widget;
		if ($newWindow) $newWindow = "target='_blank'";
			if(!$hideTitle && $title) {
				if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title<span class='more-i'><span></span><span></span><span></span></span></a>";
			}
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<div class="w-img-cat">
	<?php if (zm_get_option('cat_icon') && $instance['show_icon']) { ?>
		<?php $q .= '&category__and='.$instance['cat']; query_posts($q);?>
		<h3 class="widget-title-cat-icon cat-w-icon"><a href="<?php echo $titleUrl; ?>" rel="bookmark"><i class="t-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php echo $instance['title_z']; ?><?php more_i(); ?></a></h3>
	<?php } ?>
		<?php 
			global $post;
			if ( is_single() ) {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => '1',
				'post__not_in' => array($post->ID),
				'category__and' => $instance['cat'],
			));
			} else {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => '1',
				'category__and' => $instance['cat']
			));
		} ?>
		<?php while ($q->have_posts()) : $q->the_post(); ?>
			<figure class="w-thumbnail"><?php zm_long_thumbnail(); ?></figure>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	<ul class="title-img-cat">
		<?php 
			global $post;
			if ( is_single() ) {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => $instance['numposts'],
				'post__not_in' => array($post->ID),
				'category__and' => $instance['cat'],
			));
			} else {
			$q =  new WP_Query(array(
				'ignore_sticky_posts' => 1,
				'showposts' => $instance['numposts'],
				'category__and' => $instance['cat']
			));
		} ?>
		<?php while ($q->have_posts()) : $q->the_post(); ?>
		<li><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance = array();
	$instance['show_icon'] = $new_instance['show_icon']?1:0;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['title_z'] = strip_tags($new_instance['title_z']);
	$instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
	$instance['hideTitle'] = isset($new_instance['hideTitle']);
	$instance['newWindow'] = isset($new_instance['newWindow']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$defaults = $this -> zm_defaults();
	$instance = wp_parse_args( (array) $instance, $defaults );
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类模块',
		'titleUrl' => '',
		'numposts' => 5,
		'cat' => 0));
		$titleUrl = $instance['titleUrl'];
		 ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" class="checkbox" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
			<label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'numposts' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input class="number-text" id="<?php echo $this->get_field_id( 'numposts' ); ?>" name="<?php echo $this->get_field_name( 'numposts' ); ?>" type="number" step="1" min="1" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '全部分类', 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_icon') ); ?>" <?php checked( (bool) $instance["show_icon"], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>">显示分类图标</label>
		</p>

<?php }
}

add_action( 'widgets_init', 't_img_cat_init' );
function t_img_cat_init() {
	register_widget( 't_img_cat' );
}

// 分类封面
class widget_cover extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_cover',
			'description' => __( '以图片封面形式显示分类' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_cover', '分类封面', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_tags'   => 0,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$postid = $instance['cat_id'];
?>

<div class="widget-cat-cover">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if($instance['show_tags']) { ?>
		<?php 
			$args=array( 'include' => $instance['cat_id'] );
			$tags = get_tags($args);
			foreach ($tags as $tag) { 
				$tagid = $tag->term_id; 
				query_posts("tag_id=$tagid");
		?>
		<div class="cover4x">
			<div class="cat-cover-main wow fadeInUp" data-wow-delay="0.3s">
				<div class="cat-cover-img">
					<?php if (zm_get_option('cat_icon')) { ?><i class="cover-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
					<?php if (zm_get_option('cat_cover_img')) { ?>
						<figure class="cover-img"><img src="<?php echo get_template_directory_uri().'/prune.php?src='.cat_cover_url().'&w='.zm_get_option('img_co_w').'&h='.zm_get_option('img_co_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" alt="<?php echo $tag->name; ?>"></figure>
					<?php } else { ?>
						<figure class="cover-img"><img src="<?php echo cat_cover_url(); ?>" alt="<?php echo $tag->name; ?>"></figure>
					<?php } ?>
					<a href="<?php echo get_tag_link($tagid);?>" rel="bookmark"><div class="cover-des-box"><?php echo the_archive_description( '<div class="cover-des">', '</div>' ); ?></div></a>
					<div class="clear"></div>
				</div>
				<a href="<?php echo get_tag_link($tagid);?>" rel="bookmark"><h4 class="cat-cover-title"><?php echo $tag->name; ?></h4></a>
			</div>
		</div>
	<?php } wp_reset_query(); ?>
	
	<?php } else { ?>

		<?php
			$args=array( 'include' => $instance['cat_id'] );
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
				query_posts( 'cat=' . $cat->cat_ID );
		?>
		<div class="cover4x">
			<div class="cat-cover-main wow fadeInUp" data-wow-delay="0.3s">
				<div class="cat-cover-img">
					<?php if (zm_get_option('cat_icon')) { ?><i class="cover-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php } ?>
					<?php if (zm_get_option('cat_cover_img')) { ?>
						<figure class="cover-img"><img src="<?php echo get_template_directory_uri().'/prune.php?src='.cat_cover_url().'&w='.zm_get_option('img_co_w').'&h='.zm_get_option('img_co_h').'&a='.zm_get_option('crop_top').'&zc=1'; ?>" alt="<?php echo $cat->cat_name; ?>"></figure>
					<?php } else { ?>
						<figure class="cover-img"><img src="<?php echo cat_cover_url(); ?>" alt="<?php echo $cat->cat_name; ?>"></figure>
					<?php } ?>
					<a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><div class="cover-des-box"><?php echo the_archive_description( '<div class="cover-des">', '</div>' ); ?></div></a>
					<div class="clear"></div>
				</div>
				<a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><h4 class="cat-cover-title"><?php echo $cat->cat_name; ?></h4></a>
			</div>
		</div>
		<?php } wp_reset_query(); ?>
	<?php } ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['show_tags'] = $new_instance['show_tags']?1:0;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['cat_id'] = strip_tags($new_instance['cat_id']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '分类封面';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_tags') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_tags') ); ?>" <?php checked( (bool) $instance["show_tags"], true ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id('show_tags') ); ?>">调用标签</label>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'cat_id' ); ?>">输入分类或标签ID：</label>
		<textarea style="height:80px;" class="widefat" id="<?php echo $this->get_field_id( 'cat_id' ); ?>" name="<?php echo $this->get_field_name( 'cat_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['cat_id'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
if (zm_get_option('cat_cover')) {
add_action( 'widgets_init', 'widget_cover_init' );
function widget_cover_init() {
	register_widget( 'widget_cover' );
}
}

// 图标分类目录
class widget_cat_icon extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_cat_icon',
			'description' => __( '有图标的分类目录' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_cat_icon', '图标目录。  ', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$e_cat = strip_tags($instance['e_cat']);
?>


<div class="widget_categories">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<ul>
		<?php
			$args=array(
				'exclude' => $e_cat,
				'hide_empty' => 0
			);
			$cats = get_categories($args);
			foreach ( $cats as $cat ) {
			query_posts( 'cat=' . $cat->cat_ID );
		?>
		<li><a href="<?php echo get_category_link($cat->cat_ID);?>" rel="bookmark"><i class="widget-icon <?php echo zm_taxonomy_icon_code(); ?>"></i><?php single_cat_title(); ?></a></li>
		<?php } ?>
		<?php wp_reset_query(); ?>
	</ul>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['e_cat'] = strip_tags($new_instance['e_cat']);
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '图标目录';
		}
		global $wpdb;
		$e_cat = strip_tags($instance['e_cat']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('e_cat'); ?>">输入排除的分类ID：</label>
		<textarea style="height:50px;" class="widefat" id="<?php echo $this->get_field_id( 'e_cat' ); ?>" name="<?php echo $this->get_field_name( 'e_cat' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['e_cat'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
if (zm_get_option('cat_icon')) {
add_action( 'widgets_init', 'widget_cat_icon_init' );
function widget_cat_icon_init() {
	register_widget( 'widget_cat_icon' );
}
}

// 折叠分类目录
class widget_tree_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_tree_cat',
			'description' => __( '以折叠目录树方式显示子分类目录' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_tree_cat', '折叠分类', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$e_cat = strip_tags($instance['e_cat']);
		$c = ! empty( $instance['count'] ) ? '1' : '0';
?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

<ul class="tree_categories">
	<?php 
		$args = array(
		'exclude'       => $e_cat,
		'include'       => '',
		'hide_empty'    => 0,
		'show_count'    => $c,
		'use_desc_for_title' => 0, 
		'title_li'      =>  ''
	);
		wp_list_categories( $args );
	 ?>
</ul>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['about_back'] = $new_instance['about_back'];
		$instance['e_cat'] = strip_tags($new_instance['e_cat']);
		$instance['count'] = ! empty( $new_instance['count'] ) ? 1 : 0;
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '折叠分类';
		}
		global $wpdb;
		$e_cat = strip_tags($instance['e_cat']);
		$count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Show post counts' ); ?></label><br />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('e_cat'); ?>">输入排除的分类ID：</label>
		<textarea style="height:50px;" class="widefat" id="<?php echo $this->get_field_id( 'e_cat' ); ?>" name="<?php echo $this->get_field_name( 'e_cat' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['e_cat'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'widget_tree_cat_init' );
function widget_tree_cat_init() {
	register_widget( 'widget_tree_cat' );
}

// 公告
class widget_notice extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_notice',
			'description' => __( '显示公告' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_notice', '网站公告', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 2;
?>

<div class="zm-notice">

	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>
	<?php if ( $instance[ 'notice_back' ]  ) { ?>
		<div class="list-img-box"><img src="<?php echo $instance['notice_back']; ?>" alt="notice"></div>
	<?php } ?>
	<div class="clear"></div>
	<?php if ( $instance[ 'notice_back' ]  ) { ?>
	<div class="notice-main notice-main-img">
		<?php } else { ?>
	<div class="notice-main">
	<?php } ?>
		<ul class="list">
			<?php
				$args = array(
					'post_type' => 'bulletin',
					'showposts' => $number, 
					'tax_query' => array(
						array(
							'taxonomy' => 'notice',
							'terms' => $instance['cat']
						),
					)
				);
		 	?>
			<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<?php the_title( sprintf( '<li><a href="%s" rel="bookmark"><i class="be be-volumedown"></i> ', esc_url( get_permalink() ) ), '</a></li>' ); ?>
			<?php endwhile;?>
			<?php wp_reset_query(); ?>
		</ul>
	</div>
	<script>$(document).ready(function() {$(".zm-notice").textSlider({line: 1, speed: 1000, timer: 6000});})</script>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['hideTitle'] = isset($new_instance['hideTitle']);
			$instance['notice_back'] = $new_instance['notice_back'];
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '网站公告';
		}
	global $wpdb;
	$instance = wp_parse_args((array) $instance, array('number' => '2'));
	$number = strip_tags($instance['number']);
		$instance = wp_parse_args((array) $instance, array('notice_back' => 'https://s2.ax1x.com/2019/06/02/VGMChq.jpg'));
		$notice_back = $instance['notice_back'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('notice_back'); ?>">背景图片：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'notice_back' ); ?>" name="<?php echo $this->get_field_name( 'notice_back' ); ?>" type="text" value="<?php echo $notice_back; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => '选择分类', 'hide_empty'=>0, 'hierarchical'=>1,'taxonomy' => 'notice', 'selected'=>$instance['cat'])); ?></label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

// 专题
class widget_special extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_special',
			'description' => __( '调用专题封面' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_special', '专题封面', $widget_ops);
	}

	public function zm_defaults() {
		return array(
			'show_tags'   => 0,
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$postid = $instance['pages_id'];
?>

<div class="widget-cat-cover">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<?php 
		$posts = get_posts( array( 'post_type' => 'any', 'include' => $instance['pages_id']) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post );
	?>
	<div class="cover4x">
		<div class="cat-cover-main wow fadeInUp" data-wow-delay="0.3s">
			<div class="cat-cover-img">
				<a href="<?php echo get_permalink($post->ID); ?>" rel="bookmark">
					<div class="special-mark">专题</div>
					<figure class="cover-img">
							<?php 
								$image = get_post_meta($post->ID, 'thumbnail', true);
								echo '<img src=';
								if (zm_get_option('special_thumbnail')) {
									echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_sp_w').'&h='.zm_get_option('img_sp_h').'&a='.zm_get_option('crop_top').'&zc=1';
								} else {
									echo $image;
								}
								echo ' alt="'.$post->post_title .'" />'; 
							?>
					</figure>
					<div class="cover-des-box"><div class="cover-des"><?php $description = get_post_meta($post->ID, 'description', true);{echo $description;} ?></div></div>
				</a>
				<div class="clear"></div>
			</div>
			<a href="<?php echo get_permalink($post->ID); ?>" rel="bookmark"><h4 class="cat-cover-title"><?php echo get_the_title($post->ID); ?></h4></a>
		</div>
	</div>
	<?php endforeach; endif; ?>
	<?php wp_reset_query(); ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title_z'] = strip_tags($new_instance['title_z']);
			$instance['show_icon'] = strip_tags($new_instance['show_icon']);
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['pages_id'] = strip_tags($new_instance['pages_id']);
			return $instance;
		}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '专题封面';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'pages_id' ); ?>">输入专题页面ID：</label>
		<textarea style="height:80px;" class="widefat" id="<?php echo $this->get_field_id( 'pages_id' ); ?>" name="<?php echo $this->get_field_name( 'pages_id' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['pages_id'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}

add_action( 'widgets_init', 'widget_special_init' );
function widget_special_init() {
	register_widget( 'widget_special' );
}

// 最近浏览的文章
class recently_viewed extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'recently_viewed',
			'description' => __( '最近浏览的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('recently_viewed', '最近浏览的文章', $widget_ops);
	}

	public function zm_defaults() {
		return array(
		);
	}

	function widget($args, $instance) {
		extract($args);
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<div class="post_cat">
		<div id="recently-viewed"></div>
		<div class="clear"></div>
		<?php wp_enqueue_script( 'recently-viewed', get_template_directory_uri() . '/js/recently-viewed.js', array(), version, false ); ?>
	</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	function form($instance) {
		$defaults = $this -> zm_defaults();
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最近浏览的文章';
		}
		global $wpdb;
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'recently_viewed_init' );
function recently_viewed_init() {
	register_widget( 'recently_viewed' );
}

// 多条件筛选
class widget_filter extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_filter',
			'description' => __( '多条件筛选' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_filter', '条件筛选', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>

<div class="widget-filter">
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

	<div class="filter-box">
		<div class="filter-t"><i class="be be-sort"></i><span><?php echo zm_get_option('filter_t'); ?></span></div>
			<?php if (zm_get_option('filters_hidden')) { ?><div class="filter-box-main filter-box-main-h"><?php } else { ?><div class="filter-box-main"><?php } ?>
			<?php require get_template_directory() . '/inc/filter-core.php'; ?>
			<div class="clear"></div>
		</div>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
	} else {
		$title = '条件筛选';
	}
	global $wpdb;

?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'widget_filter_init' );
function widget_filter_init() {
	register_widget( 'widget_filter' );
}

// 单栏分类
class widget_color_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_color_cat',
			'description' => __( '单栏分类目录' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('widget_color_cat', '单栏分类', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$e_cat = strip_tags($instance['e_cat']);
?>
	<?php if($instance['show_icon']) { ?>
		<h3 class="widget-title-cat-icon cat-w-icon"><i class="t-icon <?php echo $instance['show_icon']; ?>"></i><?php echo $instance['title_z']; ?></h3>
	<?php } ?>

<ul class="color-cat">
	<?php 
		$args = array(
		'exclude'       => $e_cat,
		'include'       => '',
		'hide_empty' => 0,
		'hierarchical'    => false,
		'title_li'      =>  ''
	);
		wp_list_categories( $args );
	 ?>
</ul>
<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		$instance = $old_instance;
		$instance = array();
		$instance['title_z'] = strip_tags($new_instance['title_z']);
		$instance['show_icon'] = strip_tags($new_instance['show_icon']);
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['about_back'] = $new_instance['about_back'];
		$instance['e_cat'] = strip_tags($new_instance['e_cat']);
		return $instance;
	}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '单栏分类';
		}
		global $wpdb;
		$e_cat = strip_tags($instance['e_cat']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('title_z'); ?>">图标标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_z'); ?>" name="<?php echo $this->get_field_name('title_z'); ?>" type="text" value="<?php echo $instance['title_z']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('show_icon'); ?>">图标代码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id('show_icon'); ?>" name="<?php echo $this->get_field_name('show_icon'); ?>" type="text" value="<?php echo $instance['show_icon']; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('e_cat'); ?>">输入排除的分类ID：</label>
		<textarea style="height:50px;" class="widefat" id="<?php echo $this->get_field_id( 'e_cat' ); ?>" name="<?php echo $this->get_field_name( 'e_cat' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['e_cat'] ), ENT_QUOTES)); ?></textarea>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', 'widget_color_cat_init' );
function widget_color_cat_init() {
	register_widget( 'widget_color_cat' );
}

// 分类法
if (zm_get_option('no_gallery')) {
add_action( 'widgets_init', 'img_widget_init' );
function img_widget_init() {
	register_widget( 'img_widget' );
}
}
if (zm_get_option('no_videos')) {
add_action( 'widgets_init', 'video_widget_init' );
function video_widget_init() {
	register_widget( 'video_widget' );
}
}
if (zm_get_option('no_tao')) {
add_action( 'widgets_init', 'tao_widget_init' );
function tao_widget_init() {
	register_widget( 'tao_widget' );
}
}
if (zm_get_option('no_products')) {
add_action( 'widgets_init', 'show_widget_init' );
function show_widget_init() {
	register_widget( 'show_widget' );
}
}
if (zm_get_option('no_bulletin')) {
add_action( 'widgets_init', 'widget_notice_init' );
function widget_notice_init() {
	register_widget( 'widget_notice' );
}
}