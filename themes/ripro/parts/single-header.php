
<div class="cao_entry_header">
<?php edit_post_link('[编辑]'); ?>
<?php
  if ( ! is_page() ) {
    cao_entry_header( array( 'tag' => 'h1', 'link' => false) );
  } else {
  	cao_entry_header( array( 'tag' => 'h1') );
  }

 
  get_template_part( 'parts/entry-subheading' );
?>
</div>