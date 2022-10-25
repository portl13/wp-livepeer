<?php

/**
 * Hook it into Wordpress
 */
add_action('admin_menu', 'livepeerwp_admin_pages'); 

/**
 * Place all the add_menu_page functions in here
 */
function livepeerwp_admin_pages(){

  add_menu_page( 'Livepeer WP', 'Livepeer WP', 'manage_options', 'livepeer-wp', 'livepeerwp_start_page' );
  add_submenu_page( 'livepeer-wp', 'Options', 'Livepeer Options', 'read', 'livepeer-options', 'livepeerwp_options_page' );
}


function livepeerwp_start_page(){
  ob_start(); include dirname(__DIR__) . '/partial/start.php'; $template = ob_get_clean();

  echo $template;
}
/**
 * Admin page function
 */
function livepeerwp_options_page(){

  $message = NULL;

  $options = array();

  if ( !current_user_can( 'manage_options' ) )  {
  
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );  
  }

  if( isset( $_POST['publish'] ) ){
    flush_rewrite_rules();
    $recording = false;
    if( isset($_POST['livepeer_stream_recording']) ){
      $recording = isset($_POST['livepeer_stream_recording']);
    }
    if( isset($_POST['livepeer_stream_name']) ){
      $stream_created = livepeer_portl_get_or_create_stream($_POST['livepeer_stream_name'], $recording);
    }
    update_option( 'livepeer_wp_options', $_POST );
  }
  
  $options = get_option( 'livepeer_wp_options' );

  //$user_meta = get_user_meta(get_current_user_id(), '_stream_cofig', true);
  $global_stream_config = get_option('_stream_config');

  ob_start(); include dirname(__DIR__) . '/partial/admin.php'; $template = ob_get_clean();

  echo $template;
}

add_action( 'admin_enqueue_scripts', 'livepeer_include_js' );
function livepeer_include_js() {
  
  // I recommend to add additional conditions here
  // because we probably do not need the scripts on every admin page, right?
  if( !isset( $_GET['page'] ) ) return;
  if( $_GET['page'] != 'livepeer-options' ) return;

  // WordPress media uploader scripts
  if ( ! did_action( 'wp_enqueue_media' ) ) {
    wp_enqueue_media();
  }
  // our custom JS
  wp_enqueue_script( 
    'uploader', 
    plugin_dir_url(__DIR__) . '/assets/uploader.js',
    array( 'jquery' )
  );
}