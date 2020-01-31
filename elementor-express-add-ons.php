<?php
/**
 * Plugin Name: Elementor Express Add Ons
 * Description: Dynamic Add ons for Elementor.
 * Plugin URI:  https://wpexpressplugins.com
 * Version:     1.0
 * Author:      WP Express Plugins
 * Author URI:  https://wpexpressplugins.com
 * Text Domain: elementor-express-add-ons
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
/**
 * Main Elementor rating widget Class
 *
 * The init class that runs the Hello World plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.2.0
 */
define( 'ELEMENTOR_EXPRESS_ADD_ONS__FILE__', __FILE__ );

require( 'helpers.php' );


final class Elementor_Rating_Post {
	/**
	 * Plugin Version
	 *
	 * @since 1.2.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.2.0';
	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.2.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );
		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		// Create DB

		add_action( 'init', [ $this, 'create_plugin_tables' ] );
		//register_activation_hook( ELEMENTOR_EXPRESS_ADD_ONS__FILE__, [ $this, 'create_plugin_tables' ] );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'elementor-express-add-ons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );

			return;
		}
		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );

			return;
		}
		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );

			return;
		}
		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'plugin.php' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-express-add-ons' ),
			'<strong>' . esc_html__( 'Elementor Post Rating Widget', 'elementor-express-add-ons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-express-add-ons' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-express-add-ons' ),
			'<strong>' . esc_html__( 'Elementor Rating Widget', 'elementor-express-add-ons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-express-add-ons' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-express-add-ons' ),
			'<strong>' . esc_html__( 'Elementor Post Rating Widget', 'elementor-express-add-ons' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-express-add-ons' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Create DB table to store ratings on plugin activation
	 */
	function create_plugin_tables() {
		$this->create_widget_ids_table();
		$this->create_rating_widget_table();

	}

	function create_rating_widget_table() {
		global $table_prefix, $wpdb, $charset_collate;
		$table_name      = 'eeao_post_ratings';
		$wp_track_table  = $table_prefix . "$table_name";
		$charset_collate = $wpdb->get_charset_collate();
		#Check to see if the table exists already, if not, then create it

		if ( $wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table ) {

			$sql = "CREATE TABLE `" . $wp_track_table . "` ( ";
			$sql .= "  `id`  int(11) NOT NULL auto_increment, ";
			$sql .= "  `post_id` INT NOT NULL, ";
			$sql .= "  `widget_id` int(11) NOT NULL DEFAULT '1', ";
			$sql .= "  `ip` VARCHAR(15) NOT NULL, ";
			$sql .= "  `rating` DECIMAL( 3, 2 ) NOT NULL, ";
			$sql .= "  PRIMARY KEY (`id`) ";
			$sql .= ") $charset_collate ; ";
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	function create_widget_ids_table() {
		global $table_prefix, $wpdb, $charset_collate;
		$table_name      = 'eeao_widget_ids';
		$wp_track_table  = $table_prefix . "$table_name";
		$charset_collate = $wpdb->get_charset_collate();
		#Check to see if the table exists already, if not, then create it

		if ( $wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table ) {

			$sql = "CREATE TABLE `" . $wp_track_table . "` ( ";
			$sql .= "  `id`  int(11) NOT NULL auto_increment, ";
			$sql .= "  `post_id` INT NOT NULL, ";
			$sql .= "  `title` text NOT NULL, ";
			$sql .= "  PRIMARY KEY (`id`) ";
			$sql .= ") $charset_collate ; ";
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

}

// Instantiate Elementor_Rating_Post.
new Elementor_Rating_Post();
