<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('blank') && ! wp_is_mobile() && is_home() ) { ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
	$('#content a').attr({target: "_blank"});
	});
</script>
<?php } ?>