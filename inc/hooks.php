<?php

add_action('init', 'custom_rewrite_tag', 10, 0);

function custom_rewrite_tag() {

  add_rewrite_tag('%channel_id%', '([^/]+)/?$');
}

add_action( 'init',  function() {

    $user_id = get_current_user_id();

    $user_meta = get_user_meta($user_id, '_stream_cofig', true);

    $options = get_option('livepeer_wp_options');

    add_rewrite_rule( '^channel/'.sanitize_title($options['livepeer_stream_name']), 'index.php?pagename=player&channel_id='.$user_meta->playbackId, 'top' );
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
  $user_meta = get_user_meta($user_id, '_stream_cofig', true);

  wp_register_script('livepeer-script', plugin_dir_url(__DIR__) . '/assets/livepeer-script.js', array(), null, true);
  wp_localize_script( 'livepeer-script', 'livepeer_jsobject',
    array( 
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'api_token' => $options['LIVEPEER_API_TOKEN'],
      'stream_id' => $user_meta->id,
    )
  );
  wp_enqueue_script('livepeer-script');

});


add_action('admin_enqueue_scripts', 'livepeer_event_datepicker');
function livepeer_event_datepicker(){
  global $post;
  if( $post->post_type == 'livepeer-events' ){

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
add_action('add_meta_boxes', 'add_event_meta');
function add_event_meta()
{
  add_meta_box(
      'livepeer_event_date', // $id
      'Event Date', // $title
      'display_date_picker', // $callback
      array('livepeer-events'), // $page
      'normal', // $context
      'high'); // $priority
}
function display_date_picker($post){
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

add_action( 'save_post', 'wporg_save_postdata' );
function wporg_save_postdata( $post_id ) {
    // HERO BANNER
    if ( array_key_exists( 'livepeer_datepicker', $_POST ) ) 
        update_post_meta($post_id, '_livepeer_datepicker', $_POST['livepeer_datepicker'] );

}
