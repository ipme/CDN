<?php
include "../../../wp-load.php";
$id=$_GET['id'];
$title = get_post($id)->post_title;
$down_demo=get_post_meta($id, 'down_demo', true);
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title><?php echo $title;?> | 演示</title>
<meta name="keywords" content="<?php echo $title;?>" />
<meta name="description" content="<?php echo $title;?>演示" />
<meta name="robots" content="noindex,follow">
<link rel="stylesheet" href="css/fonts/fonts.css" />
<link rel="stylesheet" href="css/down.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
var calcHeight = function() {
	var headerDimensions = $('#switch').height();
	$('#preview-frame').height($(window).height() - headerDimensions);
}
$(window).resize(function() {
	calcHeight();
	}).load(function() {
    calcHeight();
});

// 预览
$(document).ready(function () {
	$('.monitor').addClass('preview');

	$(".monitor").click(function () {
		$("#by").css("overflow-y", "hidden");
		$('#iframe-wrap').removeClass().addClass('full-width');
		$('.tablet,.tablet-h,.monitor,.mobile,.mobile-h').removeClass('preview');
		$(this).addClass('preview');
		return false;
	});

	$(".tablet").click(function () {
		$("#by").css("overflow-y", "auto");
		$('#iframe-wrap').removeClass().addClass('tablet-width');
		$('.tablet,.tablet-h,.monitor,.mobile,.mobile-h').removeClass('preview');
		$(this).addClass('preview');
		return false;
	});

	$(".tablet-h").click(function () {
		$("#by").css("overflow-y", "auto");
		$('#iframe-wrap').removeClass().addClass('tablet-h-width');
		$('.tablet,.icon-mobile,.monitor,.mobile,.mobile-h').removeClass('preview');
		$(this).addClass('preview');
		return false;
	});

	$(".mobile").click(function () {
		$("#by").css("overflow-y", "auto");
		$('#iframe-wrap').removeClass().addClass('mobile-width');
		$('.tablet,.tablet-h,.monitor,.mobile,.mobile-h').removeClass('preview');
		$(this).addClass('preview');
		return false;
	});

	$(".mobile-h").click(function () {
		$("#by").css("overflow-y", "auto");
		$('#iframe-wrap').removeClass().addClass('mobile-width-h');
		$('.tablet,.tablet-h,.monitor,.mobile,.mobile-h').removeClass('preview');
		$(this).addClass('preview');
		return false;
	});
});
</script>

</head>
<body id="by" style="overflow-y: hidden;">
<div id="switch">
	<div class="switch-center">
		<ul class="switch-close"><li><a title="关闭演示" href="javascript:close();"><i class="be be-cross"></i></a></li></ul>
		<ul class="switch-link">
			<li><a target="_blank" title="访问演示链接" href="<?php echo $down_demo;?>"><i class="be be-link"></i>链接</a></li>
			<li><a target="_blank" title="下载该资源" href="<?php echo dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]); ?>/down.php?id=<?php echo $id;?>" ><i class="be be-download"></i>下载</a></li>
		</ul>
		<ul class="switch-ico">
			<li><a href="javascript:" title="手机横向"><div class="mobile-h"><i class="be be-mobile-h"></i></div></a></li>
			<li><a href="javascript:" title="手机竖向"><div class="mobile"><i class="be be-mobile"></i></div></a></li>
			<li><a href="javascript:" title="平板横向"><div class="tablet-h"><i class="be be-tablet-h"></i></div></a></li>
			<li><a href="javascript:" title="平板竖向"><div class="tablet"><i class="be be-tablet"></i></div></a></li>
			<li><a href="javascript:" title="电脑全屏"><div class="monitor preview"><i class="be be-display"></i></div></a></li>
		</ul>
		<div class="clear"></div>
	</div>
</div>

<div class="full-width" id="iframe-wrap">
	<script type="text/javascript">document.write("<iframe id=\"preview-frame\" src=\"<?php echo $down_demo;?>\" name=\"preview-frame\" frameborder=\"0\" noresize=\"noresize\"></iframe>");</script>
</div>
</body>
</html>