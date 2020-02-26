(function() {
	tinymce.PluginManager.add('begin_mce_button', function( editor, url ) {
		editor.addButton( 'begin_mce_button', {
			text: false,
			icon: 'editimage',
			title : '短代码',
			type: 'menubutton',
			menu: [
					{
					text: '内容保护',
					menu: [
						{
							text: '密码保护',
							icon: 'lock',
							onclick: function() {
								editor.insertContent('[password key=密码]加密的内容' + '[/password]');
							}
						},

						{
							text: '微信验证',
							icon: 'unlock',
							onclick: function() {
								editor.insertContent('[wechat key=验证码 reply=回复关键字]隐藏的内容' + '[/wechat]');
							}
						},

						{
							text: '回复可见',
							icon: 'bubble',
							onclick: function() {
								editor.insertContent('[reply]隐藏的内容' + '[/reply]');
							}
						},

						{
							text: '登录可见',
							icon: 'user',
							onclick: function() {
								editor.insertContent('[login]隐藏的内容' + '[/login]');
							}
						},
					]
				},

				{
					text: '链接按钮',
					menu: [
						{
							text: '下载按钮',
							icon: 'nonbreaking',
							onclick: function() {
								editor.insertContent('[url href=' + '下载链接地址]按钮名称[/url]');
							}
						},

						{
							text: '链接按钮',
							icon: 'newtab',
							onclick: function() {
								editor.insertContent('[link href=' + '链接地址]按钮名称[/link]');
							}
						},

						{
							text: '直达按钮',
							icon: 'newtab',
							onclick: function() {
								editor.insertContent('[go]' + '');
							}
						},
					]
				},

				{
					text: '综合功能',
					menu: [
						{
							text: '添加相册',
							icon: 'image',
							onclick: function() {
								editor.insertContent('[img]插入图片' + '[/img]');
							}
						},

						{
							text: '添加宽图',
							icon: 'image',
							onclick: function() {
								editor.insertContent('[full_img]添加图片' + '[/full_img]');
							}
						},

						{
							text: '隐藏图片',
							icon: 'image',
							onclick: function() {
								editor.insertContent('[hide_img]添加图片' + '[/hide_img]');
							}
						},

						{
							text: '两栏文字',
							icon: 'tabledeletecol',
							onclick: function() {
								editor.insertContent('[two_column]文字' + '[/two_column]');
							}
						},

						{
							text: '同标签文章',
							icon: 'anchor',
							onclick: function() {
								editor.insertContent('[tags_post title=小标题 n=篇数 ids=标签ID]' + '');
							}
						},

						{
							text: '文字折叠',
							icon: 'pluscircle',
							onclick: function() {
								editor.insertContent('[s][p]<p>隐藏的文字</p>' + '<p>[/p]</p>');
							}
						},

						{
							text: 'fieldset标签',
							icon: 'template',
							onclick: function() {
								editor.insertContent('<fieldset><legend>我是标题</legend>这里是内容</fieldset>' + '');
							}
						},

						{
							text: 'iframe标签',
							icon: 'template',
							onclick: function() {
								editor.insertContent('[iframe src="网址"' + ']');
							}
						},

						{
							text: '插入广告',
							icon: 'upload',
							onclick: function() {
								editor.insertContent('[ad]' + '');
							}
						},

						{
							text: 'MP4视频',
							icon: 'media',
							onclick: function() {
								editor.insertContent('[video width="宽" height="高" mp4=' + '视频链接地址][/video]');
							}
						}
					]
				},

				{
					text: '彩色背景',
					menu: [
						{
							text: '绿色框',
							icon: 'fill',
							onclick: function() {
								editor.insertContent('[mark_a]文字[/mark_a]');
							}
						},

						{
							text: '红色框',
							icon: 'fill',
							onclick: function() {
								editor.insertContent('[mark_b]文字[/mark_b]');
							}
						},

						{
							text: '灰色框',
							icon: 'fill',
							onclick: function() {
								editor.insertContent('[mark_c]文字[/mark_c]');
							}
						},

						{
							text: '黄色框',
							icon: 'fill',
							onclick: function() {
								editor.insertContent('[mark_d]文字[/mark_d]');
							}
						},

						{
							text: '蓝色框',
							icon: 'fill',
							onclick: function() {
								editor.insertContent('[mark_e]文字[/mark_e]');
							}
						}
					]
				}
			]
		});
	});
})();