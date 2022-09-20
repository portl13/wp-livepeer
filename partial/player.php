<?php $channel_id = get_query_var('channel_id');?>
<style>
#fluidMedia {
  position: relative;
  padding-bottom: 56.25%; /* proportion value to aspect ratio 16:9 (9 / 16 = 0.5625 or 56.25%) */
  padding-top: 30px;
  height: 0;
  overflow: hidden;
  width: 100% !important;
  max-width: 80% !important;
  margin: 0 auto;
}

#fluidMedia video {
  position: absolute;
  top: 0; 
  left: 0;
  width: 100% !important;
  height: 100%;
}
</style>

 <link href="https://vjs.zencdn.net/7.2.3/video-js.css" rel="stylesheet">

<?php 
  // TODO
  //if(!isset($image)){ // event page psuedo

    $image = plugin_dir_url(__DIR__) . 'assets/img/livepeer-banner.jpg';

    if( $image_id = get_post_thumbnail_id() ){
      $image = wp_get_attachment_image_url( $image_id, 'full' );
    } else if( $options['rudr_img'] ){
      $image = wp_get_attachment_image_url( $options['rudr_img'], 'full' );
    }
  //} // event page psuedo
?>

                        
<!-- HTML -->
<div id="fluidMedia">
  <div id="cover-image" style="display: none;">
      <img src="<?php echo esc_url( $image ) ?>" alt="<?php echo $options['livepeer_stream_name']?> Offline Banner" />
  </div>
  <div id="video-container" style="display: none;">
    <video id='hls-example' class="video-js vjs-default-skin" poster="<?php echo $options['rudr_img']?>" controls> 
      <source src="https://livepeercdn.com/hls/<?php echo $channel_id; ?>/index.m3u8" type="application/x-mpegURL">
    </video>
  </div>
</div>


<!-- JS code -->
<!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
<script src="https://vjs.zencdn.net/7.2.3/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.js"></script>