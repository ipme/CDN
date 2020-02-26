<?php if (get_option('ygj_home') == '杂志布局') {get_template_part('cms');} 
else if(get_option('ygj_home') == '图片布局'){get_template_part('grid');}
else { get_template_part('blog'); } ?>