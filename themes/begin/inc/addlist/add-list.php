<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<title>图文模块</title>
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
<link rel='stylesheet' href='list-box.css' type='text/css' media='all' />
</head>	
<body>
<form onsubmit="InsertValue();return false;" id="form-table">

<div id="wplist_tips"></div>

<table class="form-table">
	<tr>
		 <th style="width: 20%;"><label for="post_title">输入标题</label></th>
		<td><input type="text" name="post_title" id="post_title" value="" size="30" tabindex="30" style="width: 80%;" /></td>
	</tr>
	<tr>
		<th style="width: 20%;"><label for="wp_list_link">链接地址</label></th>
		<td><div id="get_alimama" style="max-width:510px;overflow:hidden;"></div><input type="text" name="wp[mm_link]" id="wp_list_link" value="" size="30" tabindex="30" style="width: 80%;" placeholder="链接地址" /></td>
	</tr>
	<tr>
		<th style="width: 20%;"><label for="imageURL">图片链接</label></th>
		<td><input type="text" name="wp[image]" id="imageURL" value="" size="30" tabindex="30" style="width: 80%;" /></td>
	</tr>
	<tr>
		<th style="width: 20%;"><label for="btn">按钮名称</label></th>
		<td><input type="text" name="wp[btn]" id="btn" value="" size="30" tabindex="30" style="width: 80%;" /></td>
	</tr>
	<tr>
		<th style="width: 20%;"><label for="wp_price">相关信息</label></th>
		<td>
			<input type="text" name="wp[price]" id="wp_price" value="" size="30" tabindex="30" style="width: 25%;" placeholder="比如现价格" />
			<label for="wp_old_price">可选信息: </label><input type="text" name="wp[old_price]" id="wp_old_price" value="" size="30" tabindex="30" style="width: 25%;" placeholder="比如原价格" />
		</td>
	</tr>
	<tr>
		<th style="width: 20%;"><label for="post_content">简介说明</label></th>
		<td><textarea id="wp_content" rows="5" name="wp_content" style="width: 80%;"></textarea></td>
	</tr>
</table>
	
<div class="submitbox">
	<div id="wp-link-cancel">
		<a class="submitdelete" href="javascript:window.parent.tinyMCE.activeEditor.windowManager.close();">取消</a>
	</div>
	<div id="wp-link-update">
		<input type="submit" value="添加" class="button button-primary" id="wp-link-submit" name="wp-link-submit">
	</div>
</div>

</form>

<script type="text/javascript">
function getId(e) {
	return document.getElementById(e)
}
function InsertValue() { 
	var title = getId("post_title").value;
	var btn = getId("btn").value;
	var content = getId("wp_content").value;
	var wp_list_link = getId("wp_list_link").value;
	var imageURL = getId("imageURL").value;
	var wp_price = getId("wp_price").value;
	var wp_old_price = getId("wp_old_price").value;
	html = '[wplist title="' + title + '" link="' + wp_list_link + '" img="' + imageURL + '" price="' + wp_price + '" oprice="' + wp_old_price + '" btn="' + btn + '"  ]' + content + '[/wplist]';
	window.parent.tinyMCE.activeEditor.execCommand('mceInsertContent', 0, html);
	window.parent.tinyMCE.activeEditor.windowManager.close()
}
</script>
</body>
</html>