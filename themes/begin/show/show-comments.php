<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php if ( comments_open() || get_comments_number() ) : ?>
<div class="g-row">
	<div class="g-col">
		<div class="section-box">
			<p class="my-comments">给我留言</p>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="g-row">
	<div class="g-col">
		<div class="section-box">
			<?php comments_template( '', true ); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php endif; ?>
<?php endwhile; ?>