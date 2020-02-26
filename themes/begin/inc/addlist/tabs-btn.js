(function($) {
	tinymce.create('tinymce.plugins.tabs_code_plugin', {
		init: function(editor, url) {
			editor.addButton('tabs_code_plugin', {
				title: "添加Tabs模块",
				icon: 'template',
				cmd: 'tabs_code_plugin'
			});

			editor.addCommand('tabs_code_plugin', function() {
				editor.windowManager.open(
					{
						title: "添加一个Tabs模块",
						file: url + '/add-tabs.php',
						width: 350,
						height: 160,
						inline: 1
					}
				);
			});
		}
	});
	tinymce.PluginManager.add('tabs_code_plugin', tinymce.plugins.tabs_code_plugin);
})(jQuery);