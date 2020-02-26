jQuery().ready(function() {
	var $=jQuery;
	var commentformavatar='#commentform div .avatar';
	var i = 0, got = -1, len = document.getElementsByTagName('script').length;
	while ( i <= len && got == -1){
		var js_url = document.getElementsByTagName('script')[i].src,
			got = js_url.indexOf('getqqinfo.js');
		i++ ;
	}
	getqqinfo_url = js_url.replace('getqqinfo.js','qq-info.php'),
	//alert(getqqinfo_url);

	$("#qq").blur(function(){
		if($('#qq').val()!=""){
			//$('#loging').html('<a>正在获取QQ信息</a>');
			$.ajax({
				url: getqqinfo_url,
				type: "POST",
				data: {
					"type":"qq",
					"qq": $('#qq').val()
				},
				dataType: "json",
				success: function(qqinfo) {
					if(qqinfo.status==0){
						var unixTimestamp = new Date((Math.round(new Date().getTime()/1000)+30000000) * 1000);
						commonTime = unixTimestamp.toLocaleString();
						//alert(commonTime);
						//$('#loging').html("");
						$('#author').val(qqinfo.name);
						$('#email').val(qqinfo.email);
						$('#url').val(qqinfo.url);
						$(commentformavatar).attr("src",qqinfo.avatar);
						$.cookie('cookiehash', qqinfo.cookiehash, { expires: unixTimestamp});
						$.cookie('comment_author_qq_'+qqinfo.cookiehash, $('#qq').val(), { expires: unixTimestamp});
					}else{
						//$('#loging').html("");
						alert(qqinfo.message);
					}
				}
			});
		}else{
			document.getElementById("author").value=('');
		}
	})

	$("#email").blur(function(){
		if($('#email').val()!=""){
		}else{
			document.getElementById("email").value=('');
		}
	})
});