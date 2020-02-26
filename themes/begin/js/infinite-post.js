$(document).ready(function() {
	var ias = $.ias({
		container: "#main",
		item: "article",
		pagination: "#nav-below",
		next: "#nav-below .nav-previous a",
	});
	ias.extension(new IASTriggerExtension({
		text: '<i class="be be-circledown"></i><span>更多</span>',
		offset: 0,
	}));
	ias.extension(new IASSpinnerExtension());
	ias.extension(new IASNoneLeftExtension({
		text: '已是最后',
	}));
	ias.on('rendered',
	function(items) {
		$("img").lazyload({
			effect: "fadeIn",
			failure_limit: 70
		});
	})
});