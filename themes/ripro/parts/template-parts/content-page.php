<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
  <?php get_template_part( 'parts/single-top' ); ?>

  <div class="container">
    <div class="entry-wrapper">
      <div class="entry-content u-text-format u-clearfix">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</article>
