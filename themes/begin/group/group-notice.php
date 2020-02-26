<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('group_bulletin')) { ?>
<div class="g-row <?php if (zm_get_option('bg_0')) { ?>g-line<?php } ?> group-notice sort" name="<?php echo zm_get_option('group_bulletin_s'); ?>">
	<div class="g-col ">
		<div class="section-box">
			<div id="group-scrolldiv">
					<div class="noticetext">
					<div class="noticeico"><i class="be be-volumedown"></i></div>
					<ul>
						<?php 
							$args = array('tax_query' => array( array('taxonomy' => 'notice', 'field' => 'id', 'terms' => explode(',',zm_get_option('group_bulletin_id') ))), 'posts_per_page' => zm_get_option('group_bulletin_n'));
							query_posts($args); while ( have_posts() ) : the_post();
						?>
						<?php the_title( sprintf( '<li class="scrolltext-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
						<?php endwhile; ?>
						<?php wp_reset_query(); ?>
					</ul>
				</div>
			</div>
			<script type="text/javascript">$(document).ready(function() {$("#group-scrolldiv").textSlider({line: 1, speed: 300, timer: 6000});})</script>
		</div>
	</div>
</div>
<?php } ?>