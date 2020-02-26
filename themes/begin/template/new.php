<?php if (zm_get_option('news_ico')) { ?>
<?php
	if ( ! defined( 'ABSPATH' ) ) { exit; }
	$t1=$post->post_date;
	$t2=date("Y-m-d H:i:s");
	$t3=zm_get_option('new_n');
	$diff=(strtotime($t2)-strtotime($t1))/3600;
	if($diff<$t3){ echo'<span class="new-icon"><i class="be be-new"></i></span>'; }
?>
<?php } ?>