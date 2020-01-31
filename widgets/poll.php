<?php

namespace ElementorExpressAddOns\Widgets;
//namespace ElementorExpressAddOns;
//use ElementorExpressAddOns;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Widgets;
//use ElementorExpressAddOns\Control_Select2;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Elementor widget for Post Rating.
 *
 * @since 1.0.0
 */
class Poll extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'eeao-poll';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'EEAO Poll', 'elementor-express-add-ons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-radio';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'elementor-express-add-ons' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'elementor-express-add-ons' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Poll Widget Settings', 'elementor-express-add-ons' ),
			]
		);

		$this->add_control(
			"widget_title",
			[
				'label'      => __( 'Poll Title', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

		$this->add_widget_id_settings();
	}

	protected function add_widget_id_settings(){
		global $post;
		$post_id = $post->ID;
		// Get the page settings manager
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

		// Get the settings model for current post
		$page_settings_model = $page_settings_manager->get_model( $post_id );

		// Retrieve the color we added before
		$widget_id = $page_settings_model->get_settings( 'eeao_widget_id' );

		if( is_numeric( $widget_id )){
			$widget_id_default = $widget_id;
		} else {
			$widget_id_default = eeao_create_widget_id( $post_id );
		}

		$this->start_controls_section(
			'section_widget_id',
			[
				'label' => __( 'Widget ID', 'elementor-express-add-ons' ),
			]
		);

		$this->add_control(
			'eeao_widget_id',
			[
				'label'   => __( 'EEAO Widget ID', 'plugin-domain' ),
				'type'    => 'selectwidgetid',
				'default' => $widget_id_default,
				'options' => [ 1 => 1 , 2 => 2, 3 => 3],
				'description' => __( 'If you want to add a Second widget with separate rating, create a new Widget ID', 'elementor-express-add-ons' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		global $post;
		$post_id = $post->ID;
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'none' );
		$original_settings = $settings;
		$settings                      = eeao_convert_setting_key_to_camel_case( $settings );
		$widget_id = $original_settings['eeao_widget_id'];
		$settings['widgetType'] = 'poll';
		$settings_json                 = htmlspecialchars( json_encode( $settings ), ENT_QUOTES, 'UTF-8' );;
		$poll_fall_back_html = '<div class="poll-fallback-html">'.$original_settings['widget_title'].'</div>';
		echo '<div
			class="eeao-widget-data"	
          id="eeao-poll-' . $post_id . '-widget-'.$widget_id.'"
          data-eeao-post-id="' . $post_id . '"
          data-eeao-widget-id="'.$widget_id.'"
          data-eeao-widget-settings="' . $settings_json . '"
        > 
        <div>' . $poll_fall_back_html . '</div>
        </div>
        ';
//		echo '<aside id="widget" class="widget-sidebar">
//    This Element is not controlled by our Vue-App, but we can create a <portal-target> there dynamically.
//  </aside>';
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
	}
}
