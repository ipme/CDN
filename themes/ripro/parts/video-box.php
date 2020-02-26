<?php
  global $post;
  $video_url = _get_post_video_url();
  $logo_regular = _cao( 'site_logo');
  $video_poster_meta = get_post_meta($post->ID, 'video_poster_url', true);
  $video_poster_url = ($video_poster_meta) ? $video_poster_meta : _get_post_timthumb_src() ;
  $xgplayer  = get_template_directory_uri() . '/assets/js/plugins/xgplayer.js';
  //获取弹幕
  $is_video_danmu = get_post_meta($post->ID, 'is_video_danmu', true);
  $dmarray = array();
  if ($is_video_danmu) {
    $dmstyle = array('color' => "#ff9500", 'fontSize' => "15px",'border' => "solid 1px #ff9500",'borderRadius' => "50px",'padding' => "1px 11px",'backgroundColor' => "rgba(255, 255, 255, 0.1)",'margin-top' => "20px",);
    array_push($dmarray, array('duration' => 2000, 'id' => "1", 'start' => 1000, 'txt' => '当前位置：'.trim(wp_title('', false)), 'mode' => 'scroll','style' => $dmstyle, ));
    array_push($dmarray, array('duration' => 5000, 'id' => "2", 'start' => 3000, 'txt' => _get_excerpt(32, '...'), 'mode' => 'scroll','style' => $dmstyle, ));
    $comments = get_comments('status=approve&number=20&order=desc');
    foreach($comments as $key =>$comment) :
      array_push($dmarray, array('duration' => mt_rand(5000,25000), 'id' => $key+2, 'start' => $key*mt_rand(1000,12000), 'txt' => get_comment_author().'：'.$comment->comment_content, 'mode' => 'scroll','style' => $dmstyle, ));
    endforeach;
  }
?>
<?php if ($video_url): ?>
<div id="ripro-mse">
  <div id="mse-video"></div>
</div>
<script src="<?php echo esc_url($xgplayer); ?>" charset="utf-8"></script>
<script type="text/javascript">
  let player = new Player({
    "id": "mse-video",
    "url": '<?php echo $video_url; ?>',
    "playsinline": true,
    "whitelist": [""],
    "enterLogo": {"url": "<?php echo esc_url( $logo_regular ); ?>","width": 180,"height": 50},
    "enterBg": {"color": "rgba(0,0,0,0.87)"},
    "enterTips": {
        "background": "linear-gradient(to right, rgba(0,0,0,0.87), #3D96FD, rgba(86,195,248), #3D96FD, rgba(0,0,0,0.87))"
    },
    "keyShortcut": "on",
    "closeVideoClick": false,
    "playbackRate": [0.5,1,1.5,2,2.5],
    "fluid": true,
    "volume": 0.8,
    "poster": "<?php echo esc_url($video_poster_url); ?>",
    "danmu": {
        "comments": <?php echo json_encode($dmarray); ?>,
        "area": { "start": 0,"end": 1},
        "closeDefaultBtn": false,
        "defaultOff": false,
        "panel": true,
    },
  });
</script>
<?php endif;?>