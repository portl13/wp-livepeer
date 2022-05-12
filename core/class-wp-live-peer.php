<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This is the main class that is responsible for registering
 * the core functions, including the files and setting up all features. 
 * 
 * To add a new class, here's what you need to do: 
 * 1. Add your new class within the following folder: core/includes/classes
 * 2. Create a new variable you want to assign the class to (as e.g. public $helpers)
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new Wp_Live_Peer_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'Wp_Live_Peer' ) ) :

	/**
	 * Main Wp_Live_Peer Class.
	 *
	 * @package		WPLP
	 * @subpackage	Classes/Wp_Live_Peer
	 * @since		.5
	 * @author		Portl
	 */
	final class Wp_Live_Peer {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	.5
		 * @var		object|Wp_Live_Peer
		 */
		private static $instance;

		/**
		 * WPLP helpers object.
		 *
		 * @access	public
		 * @since	.5
		 * @var		object|Wp_Live_Peer_Helpers
		 */
		public $helpers;

		/**
		 * WPLP settings object.
		 *
		 * @access	public
		 * @since	.5
		 * @var		object|Wp_Live_Peer_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	.5
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'wp-live-peer' ), '.5' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	.5
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'wp-live-peer' ), '.5' );
		}

		/**
		 * Main Wp_Live_Peer Instance.
		 *
		 * Insures that only one instance of Wp_Live_Peer exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		.5
		 * @static
		 * @return		object|Wp_Live_Peer	The one true Wp_Live_Peer
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Wp_Live_Peer ) ) {
				self::$instance					= new Wp_Live_Peer;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Wp_Live_Peer_Helpers();
				self::$instance->settings		= new Wp_Live_Peer_Settings();

				//Fire the plugin logic
				new Wp_Live_Peer_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'WPLP/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   .5
		 * @return  void
		 */
		private function includes() {
			require_once WPLP_PLUGIN_DIR . 'core/includes/classes/class-wp-live-peer-helpers.php';
			require_once WPLP_PLUGIN_DIR . 'core/includes/classes/class-wp-live-peer-settings.php';

			require_once WPLP_PLUGIN_DIR . 'core/includes/classes/class-wp-live-peer-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   .5
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   .5
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'wp-live-peer', FALSE, dirname( plugin_basename( WPLP_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.