<?php

namespace ElementorExpressAddOns;
//use ElementorExpressAddOns\Controls;
/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class ElementorPostRatingPlugin {
	static $MDI_CSS = 'assets/libs/mdi/css/materialdesignicons.min.css';
	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {

		wp_enqueue_script( 'elementor-express-add-ons', plugins_url( '/assets/widgets/js/elementor-express-add-ons.js', __FILE__ ), [ 'jquery' ], false, true );
		//wp_register_script( 'elementor-express-post-rating', false );

		wp_localize_script( 'elementor-express-add-ons', 'postRatingSettings', [
			'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'       => wp_create_nonce( 'vw_post_rating' ),
		] );

		wp_enqueue_style( 'elementor-express-add-ons-mdi', plugins_url(self::$MDI_CSS, __FILE__) );
		wp_enqueue_style( 'elementor-express-add-ons', plugins_url( '/assets/widgets/css/elementor-express-add-ons.css?time=' . time(), __FILE__ ) );

	}
	public function editor_scripts(){
		wp_enqueue_script( 'elementor-express-add-ons-editor-rating', plugins_url( '/assets/editor/ratings/js/common.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_enqueue_style( 'elementor-express-add-ons', plugins_url( '/assets/editor/ratings/css/style.css', __FILE__ ) );
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/widgets/post-rating.php' );
		require_once( __DIR__ . '/widgets/poll.php' );
		require_once( __DIR__ . '/widgets/live-data-table.php' );
	}

	private function includes() {
		require_once( __DIR__ . '/includes/controls/custom-text.php' );
		require_once( __DIR__ . '/includes/controls/select-widget-id.php' );
		require_once( __DIR__ . '/includes/controls/statistics-ordering.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();
		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Rating() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Poll() );
		// Without this include code will break on frontend
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if (is_plugin_active('advanced-custom-fields/acf.php')) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Live_Data_Table() );
		}
	}
	public function register_ajax_actions( $ajax ) {
		$ajax->register_ajax_action( 'eeao_post_rating_reset', [ $this, 'post_rating_reset' ] );
	}
	/**
	 * IP Address of user
	 * @return mixed
	 */
	public function get_ip() {
		return ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] : ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] ) );
	}

	/**
	 * save rating in DB
	 *
	 * @param $post_id
	 * @param $rating
	 *
	 * @return false|int
	 */
	public function insert_rating( $post_id, $widget_id, $rating ) {
		if ( ! is_numeric( $post_id ) || ! is_numeric( $widget_id )) {
			return;
		}
		global $wpdb, $table_prefix;
		$response = $wpdb->insert( $table_prefix . 'eeao_post_ratings', [
			'post_id' => $post_id,
			'widget_id' => $widget_id,
			'ip'      => $this->get_ip(),
			'rating'  => number_format( $rating, 2 )
		] );

		return $response;
	}

	/*
	 * delete ratings by postID
	 */
	public function delete_ratings( $post_id, $widget_id ) {
		if ( ! is_numeric( $post_id ) || ! is_numeric( $widget_id ) ) {
			return;
		}
		global $wpdb, $table_prefix;
		$sql    = "delete from " . $table_prefix . "eeao_post_ratings where post_id=$post_id and widget_id=$widget_id";
		$result = $wpdb->query( $sql );

		return $result;
	}

	/**
	 * user already rated this post
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function already_rated_post( $post_id, $widget_id ) {
		if ( ! is_numeric( $post_id )  || ! is_numeric( $widget_id ) ) {
			return;
		}
		global $wpdb, $table_prefix;
		$ip      = $this->get_ip();
		$sql     = "select * from " . $table_prefix . "eeao_post_ratings where post_id=$post_id and widget_id=$widget_id and ip='" . $ip . "' limit 1";
		$results = $wpdb->get_results( $sql );
		if ( count( $results ) ) {
			//return true;
		}

		return false;
	}

	public function send_error( $error = 'Error' ) {
		$response = [ 'success' => false, 'message' => $error ];
		echo json_encode( $response );
	}

	/*
	* user can delete ratings
	*/
	public function user_can_reset_ratings() {
		$user          = wp_get_current_user();
		$allowed_roles = [ 'administrator' ];
		if ( array_intersect( $allowed_roles, $user->roles ) ) {
			return true;
		}

		return false;
	}

	/*
	 * reset ratings
	 */
	public function post_rating_reset($data) {
		$response = [];
		if(!$this->user_can_reset_ratings()){
			$response = [ 'success' => false, 'message' => 'You do not have permission to reset ratings' ];
		}else{
			$post_id = (int) $data['editor_post_id'];
			$widget_id = $data['widget_id'] ? (int) $data['widget_id']: 1; 
			if ( is_numeric( $this->delete_ratings( $post_id, $widget_id ) ) ) {
				$response = [ 'success' => true, 'message' => 'Ratings deleted for post ID: ' . $post_id ];
			} else {
				$response = [ 'success' => false, 'message' => 'Error resetting ratings' ];
			}
		}
		return $response;
	}

	/**
	 * update rating from wp-ajax POST call values
	 */
	public function post_rating_update() {
		$nonce = $_SERVER['HTTP_X_WP_NONCE'];
		if ( ! wp_verify_nonce( $nonce, 'vw_post_rating' ) ) {
			$this->send_error( 'Nonce Update Error' );
			die();
		}
		$rating  = (double) $_POST['rating'];
		$post_id = (int) $_POST['post_id'];
		$widget_id = (int) $_POST['widget_id'];

		if ( ! $this->already_rated_post( $post_id, $widget_id ) ) {
			$insert_response = $this->insert_rating( $post_id, $widget_id, $rating );
			if ( $insert_response ) {
				$response = [ 'success' => true, 'message' => 'Rating updated' ];
			} else {
				$response = [ 'success' => false, 'message' => 'DB Insert Error' ];
			}
		} else {
			$response = [ 'success' => false, 'message' => 'Already rated' ];
		}
		$rating_data = eeao_get_average_rating( $post_id, $widget_id );
		$rating_data['statistics']  = eeao_get_rating_statistics_result( $post_id, $widget_id );
		$response['ratings']       = $rating_data;

		echo json_encode( $response );
		die();
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {
		// Register widget scripts

		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		//Register Widgets
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'add_mdi_icons_tab' ] );

		// Admin ajax endpoint

		add_action( 'wp_ajax_nopriv_vw_post_rating_update', [ $this, 'post_rating_update' ] );
		add_action( 'wp_ajax_vw_post_rating_update', [ $this, 'post_rating_update' ] );
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
		if ( is_admin() ) {
			add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
		}

	}
	
	public function add_mdi_icons_tab( $tabs = array() ) {
		$mdi_icons = include_once __DIR__ . '/includes/mdi-icons.php';
		$tabs['mdi-icons'] = array(
			'name'          => 'material-design-icons',
			'label'         => esc_html__( 'Material Design Icons', 'elementor-express-add-ons' ),
			'labelIcon'     => 'mdi mdi-star',
			'prefix'        => 'mdi-',
			'displayPrefix' => 'mdi',
			'url'           => plugins_url(self::$MDI_CSS, __FILE__),
			'icons'         => $mdi_icons,
			'ver'           => '1.1.1',
		);
		return $tabs;
	}
	public function register_controls() {

	    $this->includes();
		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register_control( 'selectwidgetid', new Controls\Control_Select_Widget_Id() );
		$controls_manager->register_control( 'custom-text', new Controls\Control_Custom_Text() );
		$controls_manager->register_control( 'statistics-ordering', new Controls\Control_Statistics_Ordering() );

	}

	public function plugin_menu() {
		//create new top-level menu
		$menu = add_menu_page( 'Elementor Post Rating', 'Elementor Post Rating', 'administrator', 'post-rating-widget-settings', [
			$this,
			'plugin_settings_page'
		], 'https://api.iconify.design/mdi-star.svg' );

		$submenu = add_submenu_page( 'post-rating-widget-settings', 'Statistics', 'post-rating-widget-statistics', 'administrator', 'post-rating-widget-statistics', [
			$this,
			'stats_page'
		] );

		//add_action( 'admin_print_styles-' . $menu, [ $this, 'widget_scripts' ] );
		//add_action( 'admin_print_styles-' . $submenu, 'admin_custom_css' );

		add_action( 'admin_print_scripts-' . $menu, [ $this, 'widget_scripts' ] );
		add_action( 'admin_print_scripts-' . $submenu, [ $this, 'widget_scripts' ] );

		//call register settings function
		add_action( 'admin_init', [ $this, 'save_plugin_settings' ] );

	}

	public function plugin_settings_page() {
		// Set class property
		$this->options = get_option( 'my_option_name' );
		?>
        <div class="wrap">
            <h1>My Settings</h1>
            <form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'my_option_group' );
				do_settings_sections( 'my-setting-admin' );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	public function stats_page() {
		?>
        <div class="wrap">
            <div id="admin-app"></div>
        </div>
		<?php
	}

	public function save_plugin_settings() {
		register_setting(
			'my_option_group', // Option group
			'my_option_name', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'My Custom Settings', // Title
			array( $this, 'print_section_info' ), // Callback
			'my-setting-admin' // Page
		);

		add_settings_field(
			'id_number', // ID
			'ID Number', // Title
			array( $this, 'id_number_callback' ), // Callback
			'my-setting-admin', // Page
			'setting_section_id' // Section
		);

		add_settings_field(
			'title',
			'Title',
			array( $this, 'title_callback' ),
			'my-setting-admin',
			'setting_section_id'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();
		if ( isset( $input['id_number'] ) ) {
			$new_input['id_number'] = absint( $input['id_number'] );
		}

		if ( isset( $input['title'] ) ) {
			$new_input['title'] = sanitize_text_field( $input['title'] );
		}

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {
		print 'Enter your settings below:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function id_number_callback() {
		printf(
			'<input type="text" id="id_number" name="my_option_name[id_number]" value="%s" />',
			isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number'] ) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function title_callback() {
		printf(
			'<input type="text" id="title" name="my_option_name[title]" value="%s" />',
			isset( $this->options['title'] ) ? esc_attr( $this->options['title'] ) : ''
		);
	}

}

// Instantiate Plugin Class
ElementorPostRatingPlugin::instance();
