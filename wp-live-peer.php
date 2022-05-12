<?php
/**
 * WP Live Peer
 *
 * @package       WPLP
 * @author        Portl
 * @license       gplv2
 * @version       .5
 *
 * @wordpress-plugin
 * Plugin Name:   WP Live Peer
 * Plugin URI:    https://github.com/portl13/wp-livepeer
 * Description:   Wordpress plugin to enable WP users to live stream using the Livepeer API 
 * Version:       .5
 * Author:        Portl
 * Author URI:    https://portl.com
 * Text Domain:   wp-live-peer
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Live Peer. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function WPLP() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'WPLP_NAME',			'WP Live Peer' );

// Plugin version
define( 'WPLP_VERSION',		'.5' );

// Plugin Root File
define( 'WPLP_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'WPLP_PLUGIN_BASE',	plugin_basename( WPLP_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'WPLP_PLUGIN_DIR',	plugin_dir_path( WPLP_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'WPLP_PLUGIN_URL',	plugin_dir_url( WPLP_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once WPLP_PLUGIN_DIR . 'core/class-wp-live-peer.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Portl
 * @since   .5
 * @return  object|Wp_Live_Peer
 */
function WPLP() {
	return Wp_Live_Peer::instance();
}

WPLP();
