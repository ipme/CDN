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
		 <th style="width: 20%;"><label for="post_title">标题</label></th>
		<td><input type="text" name="post_title" id="post_title" value="" size="30" tabindex="30" style="width: 80%;" /></td>
	</tr>
	<tr>
		<th style="width: 20%;"><label for="btn">序号</label></th>
		<td><input type="text" name="wp[btn]" id="btn" value="" size="30" tabindex="30" style="width: 80%;" /></td>
	</tr>

</table>
	
<div class="submitbox">
	<div id="wp-link-cancel">
		<a class="submitdelete" href="javascript:window.parent.tinyMCE.activeEditor.windowManager.close();">取消</a>
	</div>
	<div id="wp-link-update">
		<input type="submit" value="插入" class="button button-primary" id="wp-link-submit" name="wp-link-submit">
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
	html = '[start_tab][wptab number="' + btn + '" title="' + title + '"  ]' + '编辑添加内容' + '[/wptab][end_tab]';
	window.parent.tinyMCE.activeEditor.execCommand('mceInsertContent', 0, html);
	window.parent.tinyMCE.activeEditor.windowManager.close()
}
</script>
</body>
</html>