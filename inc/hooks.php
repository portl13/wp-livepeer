<?php

add_action('init', 'livepeer_rewrite_tag', 10, 0);

function livepeer_rewrite_tag() {

  add_rewrite_tag('%channel_id%', '([^/]+)/?$');
}

add_action( 'init',  function() {

  global $wp_rewrite;

    //$user_meta = get_user_meta($user_id, '_stream_cofig', true);
    $global_stream_config = get_option('_stream_config');

    if( !$global_stream_config ) return;

    $options = get_option('livepeer_wp_options');

    add_rewrite_rule( '^channel/'.sanitize_title($global_stream_config->name), 'index.php?pagename=player&channel_id='.$global_stream_config->playbackId, 'top' );
});

add_filter('query_vars', function($vars) {

    $vars[] = "channel_id";

    return $vars;
});

add_action( 'init', function() {

  $labels = array(
    'name'               => __( 'Livepeer Events', 'livepeer-wp' ),
    'singular_name'      => __( 'Livepeer Event', 'livepeer-wp' ),
    'add_new'            => _x( 'Add New Livepeer Event', 'livepeer-wp', 'livepeer-wp' ),
    'add_new_item'       => __( 'Add New Livepeer Event', 'livepeer-wp' ),
    'edit_item'          => __( 'Edit Livepeer Event', 'livepeer-wp' ),
    'new_item'           => __( 'New Livepeer Event', 'livepeer-wp' ),
    'view_item'          => __( 'View Livepeer Event', 'livepeer-wp' ),
    'search_items'       => __( 'Search Livepeer Events', 'livepeer-wp' ),
    'not_found'          => __( 'No Livepeer Events found', 'livepeer-wp' ),
    'not_found_in_trash' => __( 'No Livepeer Events found in Trash', 'livepeer-wp' ),
    'parent_item_colon'  => __( 'Parent Livepeer Event:', 'livepeer-wp' ),
    'menu_name'          => __( 'Livepeer Events', 'livepeer-wp' ),
  );

  $args = array(
    'labels'              => $labels,
    'hierarchical'        => false,
    'description'         => 'description',
    'taxonomies'          => array(),
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => null,
    'menu_icon'           => null,
    'show_in_nav_menus'   => true,
    'publicly_queryable'  => true,
    'exclude_from_search' => false,
    'has_archive'         => true,
    'query_var'           => true,
    'can_export'          => true,
    'rewrite'             => true,
    'capability_type'     => 'post',
    'supports'            => array(
      'title',
      'editor',
      'author',
      'thumbnail',
      'excerpt',
      'custom-fields',
      'trackbacks',
      'comments',
      'revisions',
      'page-attributes',
      'post-formats',
    ),
  );

  register_post_type( 'livepeer-events', $args );
});

add_action('wp_enqueue_scripts', function(){
  $options = get_option('livepeer_wp_options');
  $user_id = get_current_user_id();
  //$user_meta = get_user_meta($user_id, '_stream_cofig', true);
  $global_stream_config = get_option('_stream_config');

  if( !$global_stream_config ) return;

  wp_enqueue_script('video-js', 'https://vjs.zencdn.net/7.2.3/video.js', [], null, true);
  wp_enqueue_script('video-js-contrib', 'https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.js', [], null, true);
  wp_register_script('livepeer-script', plugin_dir_url(__DIR__) . 'assets/livepeer-script.js', array(), null, true);
  wp_localize_script( 'livepeer-script', 'livepeer_jsobject',
    array( 
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'api_token' => $options['LIVEPEER_API_TOKEN'],
      'stream_id' => $global_stream_config->id,
    )
  );
  wp_enqueue_script('livepeer-script');

});


add_action('admin_enqueue_scripts', 'livepeer_event_datepicker');
function livepeer_event_datepicker(){
  global $post;
  if( is_object($post) && $post->post_type == 'livepeer-events' ){

    wp_enqueue_script( 'jquery-ui-datepicker-init',
      plugins_url( 'jquery-ui-datepicker-init.js', __FILE__ ),
      array( 'jquery', 'jquery-ui-datepicker' ),
      '1.00' );

    wp_enqueue_style( 'jquery-ui',
      'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
      array(),
      '1.00' );

    wp_enqueue_script(
      'timepicker', 
      plugin_dir_url(__DIR__) . 'assets/timepicker.js', 
      array('jquery-ui-datepicker-init'));

    wp_enqueue_style( 'timepicker-css',
      plugin_dir_url(__DIR__) . 'assets/timepicker.css',
      array(),
      '1.00' );
  }
}

// PAGE META



add_action('add_meta_boxes', 'livepeer_add_event_meta');
function livepeer_add_event_meta()
{

  add_meta_box(
      'livepeer_event_date', // $id
      'Event Date', // $title
      'livepeer_display_date_picker', // $callback
      array('livepeer-events'), // $page
      'normal', // $context
      'high'); // $priority
}
function livepeer_display_date_picker($post){
    $options = get_post_meta($post->ID, '_livepeer_datepicker', true); 
    ?>
      <p>Use the Shortcode <strong>[livepeer_event_date]</strong> anywhere in your post or page builder to display the event date on your flyer.</p>
      <label for="livepeer_datepicker">Event Date</label>
      <input id="livepeer_datepicker" name="livepeer_datepicker" placeholder="mm/dd/yyyy" type="text" style="width: 400px;" value="<?php echo esc_attr($options); ?>">
      <script>
        jQuery(document).ready(function($){
          $('#livepeer_datepicker').datetimepicker({
            timeFormat: "hh:mm tt"
          });
        });
      </script>
    <?php
}


add_action('edit_form_after_title', function() {
  global $post;
  if( $post->post_type == 'livepeer-events' )
  ?><p style="font-size: 1.3em; padding: 4px 8px; border: 1px solid #444; background-color: #e1e1e1; border-radius: 3px;">To include your live stream on your event page add <strong>[livepeer_player]</strong> anywhere on the event page.</p><?php
});

add_action( 'save_post', 'livepeer_save_postdata' );
function livepeer_save_postdata( $post_id ) {
    // HERO BANNER
    if ( array_key_exists( 'livepeer_datepicker', $_POST ) ) 
        update_post_meta($post_id, '_livepeer_datepicker', $_POST['livepeer_datepicker'] );

}

add_action( 'wp_ajax_livepeer_delete_stream', 'livepeer_delete_stream');
add_action( 'wp_ajax_nopriv_livepeer_delete_stream', 'livepeer_delete_stream');
function livepeer_delete_stream(){
  livepeer_portl_delete_stream();
}

add_action('init', 'livepeer_reset_permalinks');
function livepeer_reset_permalinks(){
  
  if( get_option('livepeer_needs_reset') ){
    //Set permlinks on theme activate
    $current_setting = get_option('permalink_structure');

    // Save permalinks to a custom setting, force create of rules file
    global $wp_rewrite;
    update_option("rewrite_rules", FALSE);
    $wp_rewrite->set_permalink_structure($current_setting);
    $wp_rewrite->flush_rules(true);

    delete_option('livepeer_needs_reset');
  }
}