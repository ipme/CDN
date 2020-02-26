(function($) {
	tinymce.create('tinymce.plugins.list_code_plugin', {
		init: function(editor, url) {
			editor.addButton('list_code_plugin', {
				title: "添加一个图文模块", // 提示文字
				icon: 'tableleftheader',
				cmd: 'wp_command' // 点击时执行的方法
			});

			editor.addCommand('wp_command', function() {
				editor.windowManager.open(
					{
						title: "添加一个图文模块", // 对话框的标题
						file: url + '/add-list.php', // 对话框内容的HTML文件
						width: 500, // 对话框宽度
						height: 400, // 对话框高度
						inline: 1 // 使用弹出对话框
					}
				);
			});
		}
	});
	tinymce.PluginManager.add('list_code_plugin', tinymce.plugins.list_code_plugin);
})(jQuery);