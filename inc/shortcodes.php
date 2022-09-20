<?php

add_shortcode( 'livepeer_viewer', 'livepeerwp_viewer_shortcode' );
function livepeerwp_viewer_shortcode( $atts = array(), $content = '' ) {

  $atts = shortcode_atts( array(
    'id' => 'value',
  ), $atts, 'livepeer_viewer' );

  if( get_current_user_id() ){
    
    $stream_created = portl_get_or_create_stream();
    $options = get_option('livepeer_wp_options');

    ob_start();
    include dirname(__DIR__).'/partial/viewer.php';
    return ob_get_clean();

  }


  // do shortcode actions here
}

add_shortcode( 'livepeer_player', 'livepeerwp_player_shortcode' );
function livepeerwp_player_shortcode( $atts = array(), $content = '' ) {

  $atts = shortcode_atts( array(
    'id' => 'value',
  ), $atts, 'livepeer_player' );

  global $post;
  // TODO
  // This is psuedo for featured image on event page
  //$image = wp_get_attachment_image_url();

  if( $user_id = get_current_user_id() ){
    $user_meta = get_user_meta($user_id, '_stream_cofig',true);
    $stream_created = portl_get_or_create_stream();
    $options = get_option('livepeer_wp_options');
    ob_start();
    include dirname(__DIR__).'/partial/player.php';
    return ob_get_clean();

  }


  // do shortcode actions here
}

add_shortcode( 'livepeer_event_date', 'livepeer_eventdate_shortcode' );  
function livepeer_eventdate_shortcode( $atts = array(), $content = '' ) {
  $atts = shortcode_atts( array(
    'id' => 'value',
  ), $atts, 'livepeer_event_date' );  

    global $post;
    $date = get_post_meta($post->ID, '_livepeer_datepicker', true);
    return $date;

  // do shortcode actions here
}
