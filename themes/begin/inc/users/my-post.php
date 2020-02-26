<div class="my-post">
	<table cellspacing="0" cellpadding="0" border="0">
		<thead>
			<tr>
				<td width="800"><?php _e( '标题', 'begin' ); ?></td>
				<td width="120"><?php _e( '日期', 'begin' ); ?></td>
				<td class="fj" width="100"><?php _e( '浏览', 'begin' ); ?></td>
				<td class="fj" width="120"><?php _e( '分类', 'begin' ); ?></td>
				<td class="fj" width="100"><?php _e( '评论', 'begin' ); ?></td>
				<td class="fj" width="80"><?php _e( '状态', 'begin' ); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php
			$userinfo=get_userdata(get_current_user_id());
			$user_id= $userinfo->ID;
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$args = array(
				'post_type' => array('post','video','picture','bulletin','tao'),
				'author' => $user_id,
				'posts_per_page' =>'20',
				'post_status' => array('publish', 'pending'),
				'orderby' => 'date',
				'paged' => $paged
			);
			query_posts($args);
			if(have_posts()) : while (have_posts()) : the_post();
				switch(get_post(get_the_ID())->post_status){
					case 'publish':
					$status='' . sprintf(__( '通过', 'begin' )) . '';
					break;
					case 'pending':
					$status='<span>' . sprintf(__( '待审', 'begin' )) . '</span>';
					break;
				}
			?>
			<tr>
				<td><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></td>
				<td class="tc"><?php the_time( 'Y-m-d' ) ?></td>
				<td class="tc fj"><?php if( function_exists( 'the_views' ) ) { print ''; the_views(); print '';  } ?></td>
				<td class="tc fj"><?php echo get_the_term_list(get_the_ID(), array('category','videos','gallery','notice','taobao'), '', ', ', ''); ?></td>
				<td class="tc fj"><?php echo get_post(get_the_ID())->comment_count?></td>
				<td class="tc fj"><?php echo $status?></td>
			</tr>
			<?php endwhile; endif;?>

		</tbody>
	</table>
</div>
<?php begin_pagenav(); ?>
<div class="clear"></div>