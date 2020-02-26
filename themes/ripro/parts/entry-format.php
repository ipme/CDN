<?php 
$format = get_post_format();
$post_id =get_the_ID();
$is_post_favcss = (is_get_post_fav($post_id)) ? ' ok' : '';
if (_cao('is_nav_myfav','1') && is_site_shop_open()) {
  echo '<div class="entry-star"><a href="javascript:;" title="收藏文章" etap="star" data-postid="'.$post_id.'" class="ripro-star'.$is_post_favcss.'"><i class="fa fa-star-o"></i></a></div>';
}

if (_get_post_shop_hide()) {
  echo '<div class="entry-format"><i class="fa fa-lock"></i></div>';
}elseif (_get_post_video_url()) {
  echo '<div class="entry-format"><i class="mdi mdi-youtube-play"></i></div>';
} else{
  switch ( $format ) {
    case 'video' : ?>
      <div class="entry-format">
        <i class="mdi mdi-youtube-play"></i>
      </div>
      <?php break;
    case 'gallery' : ?>
      <div class="entry-format">
        <i class="mdi mdi-image-multiple"></i>
      </div>
      <?php break;
    case 'audio' : ?>
      <div class="entry-format">
        <i class="mdi mdi-music"></i>
      </div>
      <?php break;
  }
}

