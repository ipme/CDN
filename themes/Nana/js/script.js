$(document).ready(function(a){a(".collapseButton").click(function(){a(this).parent().parent().find(".xContent").slideToggle("fast")})})
$(document).ready(function(){
// 移动端输入页码跳转	
$('#current-page-selector').keypress(function(event){
	var page_input = $(this).val();
	var page_max = $(this).attr('max');
	if(event.keyCode == '13')
	{
		if (!(/(^[1-9]\d*$)/.test(page_input))) { 
　　　　　　alert('请输入正整数页码！'); 
　　　　　　return false; 
　　　　}
		if(page_input ==''){
			alert('请输入正整数页码！');
			return false;
		}
		if(page_input<1 || page_input>Number(page_max)){
			alert('请输入1至' + page_max +'之间的正整数页码！');
		return false;
		}
		page_links = $('.nav-links a').eq(2).attr('href');//从页码列表中获取任意一个链接,此处获取第二个链接
		go_link = page_links.replace(/\/page\/([0-9]{1,})/g, '/page/'+page_input); //将页码数字替换
		location.href = go_link; //跳转	
	}
});	
// 搜索
$(".nav-search").click(function(){
	$("#main-search").slideToggle(500);
});
	
// 侧边栏文章切换
    $('#top_post').find('.top_post_item').hide();
	$('#top_post').find('.' + $('#top_post_filter').find('li').eq(0).attr('id')).show();
    $('#top_post_filter').on('mouseover', 'li', function(){
		$('#top_post_filter').find('li').removeClass('top_post_filter_active');
		$(this).addClass('top_post_filter_active');
		$('#top_post').find('a').hide();
		$('#top_post').find('.' + $(this).attr('id')).show();
    })
	
// 最新文章
	$(".clr").mouseover(function () {
        $(this).addClass('hov');
        }).mouseleave(function () {
            $(this).removeClass('hov');
    });
	
// 去边线
$(".message-widget li:last, .message-page li:last, .hot_commend li:last, .random-page li:last, .search-page li:last, .my-comment li:last").css("border","none");

// 表情
$('.smiley').click(function () {
	$('.smiley-box').animate({
		opacity: 'toggle',
		left: '50px'
	}, 1000).animate({
		left: '10px'
	}, 'fast');
	return false;
});

// 文字展开
$(".showmore span").click(function(e){
	$(this).html(["▼显示", "▲隐藏"][this.hutia^=1]);
	$(this.parentNode.parentNode).next().slideToggle();
	e.preventDefault();
});

	
// 滚屏
$('.tools_top').click(function () {
    $('html,body').animate({
        scrollTop: '0px'
    }, 800);
});
$('.tools_comments').click(function () {
    $('html,body').animate({
        scrollTop: $('.comments-area').offset().top
    }, 800);
});
	
// 图片数量
var i = $('#gallery img').size();
$('.myimg').html(' ' + i + ' 张图片');

});
// 文字滚动
(function($){$.fn.textSlider=function(settings){settings=jQuery.extend({speed:"normal",line:2,timer:1000},settings);return this.each(function(){$.fn.textSlider.scllor($(this),settings)})};$.fn.textSlider.scllor=function($this,settings){var ul=$("ul:eq(0)",$this);var timerID;var li=ul.children();var _btnUp=$(".up:eq(0)",$this);var _btnDown=$(".down:eq(0)",$this);var liHight=$(li[0]).height();var upHeight=0-settings.line*liHight;var scrollUp=function(){_btnUp.unbind("click",scrollUp);ul.animate({marginTop:upHeight},settings.speed,function(){for(i=0;i<settings.line;i++){ul.find("li:first").appendTo(ul)}ul.css({marginTop:0});_btnUp.bind("click",scrollUp)})};var scrollDown=function(){_btnDown.unbind("click",scrollDown);ul.css({marginTop:upHeight});for(i=0;i<settings.line;i++){ul.find("li:last").prependTo(ul)}ul.animate({marginTop:0},settings.speed,function(){_btnDown.bind("click",scrollDown)})};var autoPlay=function(){timerID=window.setInterval(scrollUp,settings.timer)};var autoStop=function(){window.clearInterval(timerID)};ul.hover(autoStop,autoPlay).mouseout();_btnUp.css("cursor","pointer").click(scrollUp);_btnUp.hover(autoStop,autoPlay);_btnDown.css("cursor","pointer").click(scrollDown);_btnDown.hover(autoStop,autoPlay)}})(jQuery);

// 表情
function grin(a){var d;a=" "+a+" ";if(document.getElementById("comment")&&document.getElementById("comment").type=="textarea"){d=document.getElementById("comment")}else{return false}if(document.selection){d.focus();sel=document.selection.createRange();sel.text=a;d.focus()}else{if(d.selectionStart||d.selectionStart=="0"){var c=d.selectionStart;var b=d.selectionEnd;var e=b;d.value=d.value.substring(0,c)+a+d.value.substring(b,d.value.length);e+=a.length;d.focus();d.selectionStart=e;d.selectionEnd=e}else{d.value+=a;d.focus()}}};

// 弹窗
(function(a){a.fn.extend({leanModal:function(d){var e={top:100,overlay:0.5,closeButton:null};var c=a("<div id='overlay'></div>");a("body").append(c);d=a.extend(e,d);return this.each(function(){var f=d;a(this).click(function(j){var i=a(this).attr("href");a("#overlay").click(function(){b(i)});a(f.closeButton).click(function(){b(i)});var h=a(i).outerHeight();var g=a(i).outerWidth();a("#overlay").css({"display":"block",opacity:0});a("#overlay").fadeTo(200,f.overlay);a(i).css({"display":"block","position":"fixed","opacity":0,"z-index":11000,"left":50+"%","margin-left":-(g/2)+"px","top":f.top+"px"});a(i).fadeTo(200,1);j.preventDefault()})});function b(f){a("#overlay").fadeOut(200);a(f).css({"display":"none"})}}})})(jQuery);

// 点赞
$.fn.postLike = function() {
	if (jQuery(this).hasClass('done')) {
		return false;
	} else {
		$(this).addClass('done');
		var id = $(this).data("id"),
		action = $(this).data('action'),
		rateHolder = jQuery(this).children('.count');
		var ajax_data = {
			action: "ality_ding",
			um_id: id,
			um_action: action
		};
		$.post(wpl_ajax_url, ajax_data,
		function(data) {
			jQuery(rateHolder).html(data);
		});
		return false;
	}
};
$(document).on("click", ".favorite",
function() {
	$(this).postLike();
});
