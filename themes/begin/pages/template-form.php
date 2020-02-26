<?php
/*
Template Name: 提交信息
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php get_header(); ?>
<?php submit_form(); ?>
<div class="form-main">
<div id="primary" class="content-area tougao-area">
	<main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php if ( get_post_meta($post->ID, 'header_img', true) ) { ?>
					<div class="entry-title-clear"></div>
				<?php } else { ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php } ?>
			</header><!-- .entry-header -->
			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
					<?php if ( current_user_can('level_0') ){ ?>
	
					<form id="tou-form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<div class="basic-info">
							<p class="tou-label tou-title">
								<label for="tou-post-title">标题</label>
								<input type="text" value="" name="tougao_title" id="tou-post-title" placeholder="必填，不得超过100个字符" required/>
							</p>
							<div class="basic-box">
								<p class="tou-label tou-info">
									<label for="tou-post-author">昵称：</label>
									<input type="text" value="" name="tougao_authorname" id="tou-post-author" placeholder="必填，不得超过20个字符" required/>
								</p>
								<p class="tou-label tou-info">
									<label for="tou-post-email">邮件：</label>
									<input type="text" value="" name="tougao_authoremail" id="tou-post-email"  placeholder="必填，必须符合Email格式" required/>
								</p>
								<p class="tou-label tou-info">
									<label for="tou-post-web">网站：</label>
									<input type="text" value="" name="tougao_authorblog" id="tou-post-web" placeholder="选填"/>
								</p>
								<p class="tou-label tou-info">
									<label for="tou-post-web">电话：</label>
									<input type="text" value="" name="tougao_authorphone" id="tou-post-web" placeholder="选填"/>
								</p>
								<p class="tou-label tou-info">
									<label for="tou-post-web">QQ：</label>
									<input type="text" value="" name="tougao_authorqq" id="tou-post-web" placeholder="选填"/>
								</p>
								<p class="tou-label tou-info">
									<label for="tou-post-web">备注：</label>
									<input type="text" value="" name="tougao_authorremarks" id="tou-post-web" placeholder="选填"/>
								</p>
							</div>
							<div class="clear"></div>
						</div>
						<div class="basic-info">
							<p class="tou-label">
								<label>内容：</label>
							</p>
							<p class="tou-prompt">必须填写，且不得少于10个字符</p>
						</div>
						<div class="post-area">
							<?php
								$post = false;
								$content = '';
								$editor_id = 'tou-content';
								$settings = array(
									'textarea_rows' => 10
								);
								wp_editor( $content, $editor_id, $settings );
							?>
						</div>
						<div class="basic-info">
							<p class="tou-label">
								<label>选择分类：</label>
							</p>
							<p class="tou-label post-label-cat">
								<?php 
									$notcat = explode(',',zm_get_option('form_front_cat'));
									wp_dropdown_categories(array('hierarchical' => true,  'exclude' => $notcat));
								?>
							</p>
							<p class="tou-label">
								<label>关键字：</label>
							</p>
							<p class="post-label-cat">
								<input type="text" value="" name="tougao_tags" placeholder="选填"/>
							</p>
						</div>
						<p>
							<input type="hidden" value="send" name="tougao_form" />
							<input id="submit" name="submit" type="submit" value="提 交" />
						</p>
					</form>
					<div class="clear"></div>
					<?php } else { ?>
					<p>提示：您需要登录，才能发表信息！</p>
					<?php } ?>
				</div>
			</div>
		</article>
	<?php endwhile; ?>
	</main>
</div>
</div>

<?php get_footer(); ?>