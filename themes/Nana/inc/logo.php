<hgroup class="logo-site">
	<<?php if(is_home()){echo 'h1';}else{echo 'div';} ?> class="site-title">
		<a href="<?php echo esc_url( home_url() ); ?>/" title="<?php bloginfo('name'); echo stripslashes(get_option('ygj_lianjiefu')); bloginfo('description'); ?>">
			<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/logo.png" width="220" height="50" alt="<?php bloginfo('name'); echo stripslashes(get_option('ygj_lianjiefu')); bloginfo('description'); ?>" title="<?php bloginfo('name'); echo stripslashes(get_option('ygj_lianjiefu')); bloginfo('description'); ?>">
			<span><?php bloginfo('name'); ?></span>
		</a>
	</<?php if(is_home()){echo 'h1';}else{echo 'div';} ?>>
</hgroup><!-- .logo-site -->