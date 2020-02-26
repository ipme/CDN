<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if ( get_post_meta($post->ID, 'postauthor', true) ) : ?>
<div class="authorbio wow fadeInUp ms" data-wow-delay="0.3s">
	<?php if (zm_get_option('cache_avatar')) { ?>
	<?php echo begin_avatar( get_the_author_meta('email'), '64' ); ?>
	<?php } else { ?>
	<?php echo get_avatar( get_the_author_meta('email'), '64' ); ?>
	<?php } ?>
	<ul class="spostinfo">
		<?php $author = get_post_meta($post->ID, 'postauthor', true); ?>
		<?php $aurl = get_post_meta($post->ID, 'authorurl', true); ?>
		<li><strong><?php _e( '版权声明：', 'begin' ); ?></strong>本文由 <a target="_blank" rel="nofollow" href="<?php echo $aurl ?>" ><b><?php echo $author ?></b></a> 投稿，于<?php time_ago( $time_type ='posts' ); ?>发表</li>
		<li class="reprinted"><strong><?php _e( '转载注明：', 'begin' ); ?></strong><?php the_permalink() ?></li>
	</ul>
	<div class="clear"></div>
</div>
<?php else: ?>
<div class="authorbio wow fadeInUp ms" data-wow-delay="0.3s">
	<?php if (zm_get_option('copyright_avatar')) { ?>
		<?php if (zm_get_option('cache_avatar')) { ?>
		<?php echo begin_avatar( get_the_author_meta('email'), '64' ); ?>
		<?php } else { ?>
		<?php echo get_avatar( get_the_author_meta('email'), '64' ); ?>
		<?php } ?>
	<?php } ?>
	<ul class="spostinfo">
		<?php $copy = get_post_meta($post->ID, 'copyright', true); ?>
		<?php if ( get_post_meta($post->ID, 'from', true) ) : ?>
			<?php $original = get_post_meta($post->ID, 'from', true); ?>
			<li>
				<strong><?php _e( '版权声明：', 'begin' ); ?></strong><?php _e( '本文源自', 'begin' ); ?>
				<?php if ( get_post_meta($post->ID, 'copyright', true) ) : ?>
				<a href="<?php echo $copy ?>" rel="nofollow" target="_blank"><?php echo $original ?></a>，
			<?php else: ?>
				<?php echo $original ?>，
			<?php endif; ?>
			<?php the_author_posts_link(); ?> <?php _e( '整理', 'begin' ); ?><?php _e( '发表于', 'begin' ); ?><?php time_ago( $time_type ='posts' ); ?>
			</li>

			<li class="reprinted"><strong><?php _e( '转载注明：', 'begin' ); ?></strong><?php the_permalink() ?></li>
		<?php else: ?>
			<?php if ( zm_get_option('copyright_statement') == '' ) { ?>
				<li><strong><?php _e( '版权声明：', 'begin' ); ?></strong> <?php the_author_posts_link(); ?> <?php _e( '发表于', 'begin' ); ?> <?php time_ago( $time_type ='posts' ); ?></li>
			<?php } else { ?>
				<li class="reprinted"><?php echo zm_get_option('copyright_statement')?></a></li>
			<?php } ?>

			<?php if ( zm_get_option('copyright_indicate') == '' ) { ?>
				<li class="reprinted"><strong><?php _e( '转载注明：', 'begin' ); ?></strong><?php the_permalink() ?></li>
			<?php } else { ?>
				<li class="reprinted"><?php echo zm_get_option('copyright_indicate')?></a></li>
			<?php } ?>
		<?php endif; ?>
	</ul>
	<div class="clear"></div>
</div>
<?php endif; ?>