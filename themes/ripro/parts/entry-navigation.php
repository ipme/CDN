<?php
  $next_post = get_next_post();
  $previous_post = get_previous_post();
?>

<?php if ( ! empty( $next_post ) ) : ?>
  <div class="entry-navigation">
    <nav class="article-nav">
        <span class="article-nav-prev"><?php previous_post_link('上一篇<br>%link'); ?></span>
        <span class="article-nav-next"><?php next_post_link('下一篇<br>%link'); ?></span>
    </nav>
    
  </div>
<?php endif; ?>