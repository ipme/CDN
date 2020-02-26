<script type="text/javascript">
/* <![CDATA[ */
    function grin(tag) {
    	var myField;
    	tag = ' ' + tag + ' ';
        if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
    		myField = document.getElementById('comment');
    	} else {
    		return false;
    	}
    	if (document.selection) {
    		myField.focus();
    		sel = document.selection.createRange();
    		sel.text = tag;
    		myField.focus();
    	}
    	else if (myField.selectionStart || myField.selectionStart == '0') {
    		var startPos = myField.selectionStart;
    		var endPos = myField.selectionEnd;
    		var cursorPos = endPos;
    		myField.value = myField.value.substring(0, startPos)
    					  + tag
    					  + myField.value.substring(endPos, myField.value.length);
    		cursorPos += tag.length;
    		myField.focus();
    		myField.selectionStart = cursorPos;
    		myField.selectionEnd = cursorPos;
    	}
    	else {
    		myField.value += tag;
    		myField.focus();
    	}
    }
/* ]]> */
</script>
<a href="javascript:grin('[呵呵]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/1.gif" alt="" title="呵呵" /></a><a href="javascript:grin('[嘻嘻]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/2.gif" alt="" title="嘻嘻" /></a><a href="javascript:grin('[哈哈]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/3.gif" alt="" title="哈哈" /></a><a href="javascript:grin('[偷笑]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/4.gif" alt="" title="偷笑" /></a><a href="javascript:grin('[挖鼻屎]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/5.gif" alt="" title="挖鼻屎" /></a><a href="javascript:grin('[互粉]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/6.gif" alt="" title="互粉" /></a><a href="javascript:grin('[吃惊]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/7.gif" alt="" title="吃惊" /></a><a href="javascript:grin('[疑问]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/8.gif" alt="" title="疑问" /></a><a href="javascript:grin('[怒火]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/9.gif" alt="" title="怒火" /></a><a href="javascript:grin('[睡觉]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/10.gif" alt="" title="睡觉" /></a><a href="javascript:grin('[鼓掌]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/11.gif" alt="" title="鼓掌" /></a><a href="javascript:grin('[抓狂]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/12.gif" alt="" title="抓狂" /></a><a href="javascript:grin('[黑线]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/13.gif" alt="" title="黑线" /></a><a href="javascript:grin('[阴险]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/14.gif" alt="" title="阴险" /></a><a href="javascript:grin('[懒得理你]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/15.gif" alt="" title="懒得理你" /></a><a href="javascript:grin('[嘘]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/16.gif" alt="" title="嘘" /></a><a href="javascript:grin('[亲亲]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/17.gif" alt="" title="亲亲" /></a><a href="javascript:grin('[可怜]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/18.gif" alt="" title="可怜" /></a><a href="javascript:grin('[害羞]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/19.gif" alt="" title="害羞" /></a><a href="javascript:grin('[思考]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/20.gif" alt="" title="思考" /></a><a href="javascript:grin('[失望]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/21.gif" alt="" title="失望" /></a><a href="javascript:grin('[挤眼]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/22.gif" alt="" title="挤眼" /></a><a href="javascript:grin('[委屈]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/23.gif" alt="" title="委屈" /></a><a href="javascript:grin('[太开心]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/24.gif" alt="" title="太开心" /></a><a href="javascript:grin('[哈欠]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/25.gif" alt="" title="哈欠" /></a><a href="javascript:grin('[晕]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/26.gif" alt="" title="晕" /></a><a href="javascript:grin('[泪]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/27.gif" alt="" title="泪" /></a><a href="javascript:grin('[困]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/28.gif" alt="" title="困" /></a><a href="javascript:grin('[悲伤]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/29.gif" alt="" title="悲伤" /></a><a href="javascript:grin('[衰]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/30.gif" alt="" title="衰" /></a><a href="javascript:grin('[围观]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/31.gif" alt="" title="围观" /></a><a href="javascript:grin('[给力]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/32.gif" alt="" title="给力" /></a><a href="javascript:grin('[囧]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/33.gif" alt="" title="囧" /></a><a href="javascript:grin('[威武]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/34.gif" alt="" title="威武" /></a><a href="javascript:grin('[OK]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/35.gif" alt="" title="OK" /></a><a href="javascript:grin('[赞]')"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/smilies/36.gif" alt="" title="赞" /></a><br />