<?php if (get_option('ygj_hdp_tp1') || get_option('ygj_hdp_tp2') || get_option('ygj_hdp_tp3')) { ?>
<div id="slideshow"  class="wow fadeInUp" data-wow-delay="0.3s">
<ul id="slider" class="rslides" >
<?php if (get_option('ygj_hdp_tp1')) { ?>
	<li> 
		<a target="_blank" href="<?php echo stripslashes(get_option('ygj_hdp_lj1')); ?>">
            <img src="<?php echo stripslashes(get_option('ygj_hdp_tp1')); ?>" alt="<?php echo stripslashes(get_option('ygj_hdp_bt1')); ?>" >
		</a>
		<?php if (get_option('ygj_hdp_bt1')) { ?>
        <p class="slider-caption"><?php echo stripslashes(get_option('ygj_hdp_bt1')); ?></p>
		<?php }?>
	</li>
<?php }if (get_option('ygj_hdp_tp2')) { ?>
	<li>
		<a target="_blank" href="<?php echo stripslashes(get_option('ygj_hdp_lj2')); ?>">
            <img src="<?php echo stripslashes(get_option('ygj_hdp_tp2')); ?>" alt="<?php echo stripslashes(get_option('ygj_hdp_bt2')); ?>" >
		</a>
		<?php if (get_option('ygj_hdp_bt2')) { ?>
        <p class="slider-caption"><?php echo stripslashes(get_option('ygj_hdp_bt2')); ?></p>
		<?php }?>
	</li>
<?php }if (get_option('ygj_hdp_tp3')) { ?>
	<li>
		<a target="_blank" href="<?php echo stripslashes(get_option('ygj_hdp_lj3')); ?>">
            <img src="<?php echo stripslashes(get_option('ygj_hdp_tp3')); ?>" alt="<?php echo stripslashes(get_option('ygj_hdp_bt3')); ?>" >
		</a>
		<?php if (get_option('ygj_hdp_bt3')) { ?>
        <p class="slider-caption"><?php echo stripslashes(get_option('ygj_hdp_bt3')); ?></p>
		<?php }?>
	</li>
	<?php }  ?>
</ul>
</div>
<?php }?>