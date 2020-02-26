<div class="entry-footer">
  <?php if (_cao('grid_is_time',true)) : ?>
  <a href="<?php echo esc_url( get_the_permalink() ); ?>">
    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo '<i class="fa fa-clock-o"></i> '._get_post_time();?></time>
  </a>
  <?php endif; ?>
  <?php if (_cao('grid_is_views',true)) : ?>
  <a href="<?php echo esc_url( get_the_permalink() ); ?>"><span><?php echo '<i class="fa fa-eye"></i> '._get_post_views();?></span></a>
  <?php endif; ?>
  <?php if (_cao('grid_is_coments',false)) : ?>
  <a href="<?php echo esc_url( get_the_permalink() ); ?>"><span><?php echo _get_post_comments();?></span></a>
  <?php endif; ?>
  <?php if (is_site_shop_open()) : ?>
    <?php if ((_get_post_shop_status() || _get_post_shop_hide()) && _cao('grid_is_price',true)) : 
    	$post_price = _get_post_price();
    	$post_price =($post_price) ? $post_price : 'FREE' ;
    ?>
    	<a href="<?php echo esc_url( get_the_permalink() ); ?>"><span style="color: #fd721f"><?php echo '<i class="'._cao('site_money_icon').'"></i> '.$post_price;?></span></a>
    <?php endif; ?>
  <?php endif; ?>

</div>