<?php
$current_user = wp_get_current_user();
$status = isset($_GET['fep_type']) ? $_GET['fep_type'] : 'publish';
$paged = isset($_GET['fep_page']) ? $_GET['fep_page'] : 1;
$per_page = (isset($fep_misc['posts_per_page']) && is_numeric($fep_misc['posts_per_page'])) ? $fep_misc['posts_per_page'] : 10;
$author_posts = new WP_Query(array('posts_per_page' => $per_page, 'paged' => $paged, 'orderby' => 'DESC', 'author' => $current_user->ID, 'post_status' => $status));
$old_exist = ($paged * $per_page) < $author_posts->found_posts;
$new_exist = $paged > 1;
?>
<div id="fep-posts">
	<div id="fep-message"></div>
	<ul class="fep-posts-tab">
		<li><a <?php if ($status == 'publish'): ?>class="active"<?php endif; ?> href="?fep_type=publish">已发表</a></li>
		<li><a <?php if ($status == 'pending'): ?>class="active"<?php endif; ?> href="?fep_type=pending">待审核</a></li>
	</ul>
	<div id="fep-post-table-container">
		<?php if (!$author_posts->have_posts()): ?>
			没有文章
		<?php else: ?>
			<?php printf(__('共 %s 文章', 'frontend-publishing'), $author_posts->found_posts); ?>
		<?php endif; ?>
		<table>
			<?php while ($author_posts->have_posts()) : $author_posts->the_post(); $postid = get_the_ID(); ?>
				<tr id="fep-row-<?= $postid ?>" class="fep-row">
					<td class="fep-title"><i class="be be-arrowright"></i> <?php the_title(); ?></td>
					<?php if ($status == 'publish'): ?>
						<td class="fep-fixed-td"><a href="<?php the_permalink(); ?>" target="_blank">查看</a>
						</td>
					<?php endif; ?>
					<td class="fep-fixed-td"><a
							href="?fep_action=edit&fep_id=<?= $postid; ?><?= (isset($_SERVER['QUERY_STRING']) ? '&' . $_SERVER['QUERY_STRING'] : '') ?>">编辑</a>
					</td>
					<td class="post-delete fep-fixed-td">
						<a href="#">删除</a>
						<input type="hidden" class="post-id" value="<?= $postid ?>">
					</td>
				</tr>
			<?php endwhile; ?>
		</table>
		<?php wp_nonce_field('fepnonce_delete_action', 'fepnonce_delete'); ?>
		<div class="fep-nav">
			<?php if ($new_exist): ?>
				<a class="fep-nav-link fep-nav-link-left" href="?fep_type=<?= $status ?>&fep_page=<?= ($paged - 1) ?>"><i class="be be-arrowleft"></i></a>
			<?php endif; ?>
			<?php if ($old_exist): ?>
				<a class="fep-nav-link fep-nav-link-right" href="?fep_type=<?= $status ?>&fep_page=<?= ($paged + 1) ?>"><i class="be be-arrowright"></i></a>
			<?php endif; ?>
			<div style="clear:both;"></div>
		</div>
		<?php wp_reset_query(); wp_reset_postdata(); ?>
	</div>
</div>