<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div id="scrolldiv">
	<div class="scrolltext">
		<ul>
			<?php 
				$args = array('tax_query' => array( array('taxonomy' => 'notice', 'field' => 'id', 'terms' => explode(',',zm_get_option('bulletin_id') ))), 'posts_per_page' => zm_get_option('bulletin_n'));
				query_posts($args); while ( have_posts() ) : the_post();
			?>
			<?php the_title( sprintf( '<li class="scrolltext-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
			<?php endwhile; ?>
			<?php wp_reset_query(); ?>
		</ul>
	</div>
</div>
<script type="text/javascript">$(document).ready(function() {$("#scrolldiv").textSlider({line: 1, speed: 300, timer: 6000});})</script>