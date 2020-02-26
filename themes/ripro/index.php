<?php

/**
 * RiPro是一个优秀的主题，首页拖拽布局，高级筛选，自带会员生态系统，超全支付接口，你喜欢的样子我都有！
 * 正版唯一购买地址，全自动授权下载使用：https://vip.ylit.cc/
 * 作者唯一QQ：200933220 （油条）
 * 承蒙您对本主题的喜爱，我们愿向小三一样，做大哥的女人，做大哥网站中最想日的一个。
 * 能理解使用盗版的人，但是不能接受传播盗版，本身主题没几个钱，主题自有支付体系和会员体系，盗版风险太高，鬼知道那些人乱动什么代码，无利不起早。
 * 开发者不易，感谢支持，更好的更用心的等你来调教
 */
get_header();
?>
<div class="content-area">
	<main class="site-main">
	<?php $module_home = _cao('home_mode');
		if (!$module_home) {
		    echo '<h2 style=" text-align: center; margin: 0 auto; padding: 60px; ">请前往后台-主题设置-设置首页模块！</h2>';
		}
		// var_dump($module_home['enabled']);
		if ($module_home) {
		    foreach ($module_home['enabled'] as $key => $value) {
		        @get_template_part('parts/home-mode/'.$key);
		    }
		}
		get_template_part('parts/home-mode/banner')
	?>
	</main>
</div>
<?php get_footer(); ?>
