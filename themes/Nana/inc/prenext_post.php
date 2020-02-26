<div class="post-navigation">
	<?php  
	$categories = get_the_category();  
	$categoryIDS = array();  
	foreach ($categories as $category) {  
		array_push($categoryIDS, $category->term_id);  
	}  
	$categoryIDS = implode(",", $categoryIDS); ?>  
<div class="post-previous">
<?php if (get_previous_post($categoryIDS)) { previous_post_link('%link','<span>PREVIOUS:</span>%title',true,'');} else { echo "<span>PREVIOUS:</span><a href='JavaScript:void(0)'>已经是最后一篇了</a>";} ?> 
</div>
<div class="post-next">
<?php if (get_next_post($categoryIDS)) { next_post_link('%link','<span>NEXT:</span>%title',true,'');} else { echo "<span>NEXT:</span><a href='JavaScript:void(0)'>已经是最新一篇了</a>";} ?> 
</div>
</div>
<nav class="nav-single-c"> 	
	<nav class="navigation post-navigation" role="navigation">		
		<h2 class="screen-reader-text">文章导航</h2>		
		<div class="nav-links">			
			<div class="nav-previous">				
				<?php previous_post_link('%link','<span class="meta-nav-r" aria-hidden="true"><i class="fas fa-angle-left"></i></span>',true,'') ?>	
			</div>			
			<div class="nav-next">
				<?php next_post_link('%link','<span class="meta-nav-l" aria-hidden="true"><i class="fas fa-angle-right"></i></span> ',true,'') ?>
			</div>
		</div>	
	</nav>
</nav>