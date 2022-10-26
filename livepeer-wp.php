<?php
/**
 * Plugin Name:       Livepeer WP
 * Description:       This is the alpha version of the Livepeer Wordpress plugin
 * Version:           1.2.1
 * Requires at least: 6.0
 * Requires PHP:      7.2
 * Author:            PORTL
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       livepeer-wp
 * Domain Path:       /languages
 */

include 'inc/hooks.php';
include 'inc/helpers.php';
include 'inc/admin.php';
include 'inc/shortcodes.php';

/**
 * Activate the plugin.
 */
function livepeer_wp_activate() { 
  
  $check_page_exist = get_page_by_title('player', 'OBJECT', 'page');
  // Check if the page already exists
  if(empty($check_page_exist)) {
      $page_id = wp_insert_post(
          array(
          'comment_status' => 'close',
          'ping_status'    => 'close',
          'post_author'    => 1,
          'post_title'     => ucwords('player'),
          'post_name'      => strtolower(str_replace(' ', '-', trim('player'))),
          'post_status'    => 'publish',
          'post_content'   => '[livepeer_player]',
          'post_type'      => 'page'
          )
      );

      update_option('livepeer_channel_page_id', $page_id);
  }
}
register_activation_hook( __FILE__, 'livepeer_wp_activate' );