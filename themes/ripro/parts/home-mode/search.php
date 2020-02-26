<?php
$mode_search = _cao('mode_search');
$image = $mode_search['bgimg'];
$categories = get_categories( array('hide_empty' => 0) );//获取所有分类
$home_search_mod = _cao('home_search_mod');
?>
<div class="section pt-0 pb-0">
	<div class="row">
		<div class="home-filter--content lazyload" data-bg="<?php echo esc_url( @$home_search_mod['bg'] ); ?>">
			<div class="container">
				<h3 class="focusbox-title"><?php echo $title = ($home_search_mod['title']) ? $home_search_mod['title'] : '搜索本站精品资源' ;?></h3>
				<p class="focusbox-desc"><?php echo $desc = ($home_search_mod['desc']) ? $home_search_mod['desc'] : '本站所有资源均为高质量资源，各种姿势下载。' ;?></p>
			    <form class="mb-0" method="get" autocomplete="off" action="<?php echo home_url(); ?>">
			        <div class="form-box search-properties">
			            <div class="row">
			                <div class="col-xs-12 col-sm-6 col-md-9">
			                    <div class="form-group mb-0">
			                        <input type="text" class="home_search_input" name="s" placeholder="输入关键词搜索...">
			                    </div>
			                </div>
			                <div class="col-xs-12 col-sm-6 col-md-3">
			                    <input type="submit" value="搜索"  class="btn btn--block">
			                </div>
			            </div>
			            <div class="home-search-results"></div>
			        </div>
			    </form>
			</div>
		</div>
	</div>
</div>