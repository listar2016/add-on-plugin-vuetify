<?php

namespace ElementorExpressAddOns\Widgets;

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
class Live_Data_Table extends Widget_Base {
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
		return 'eeao-live-data-table';
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
		return __( 'EEAO Live Data Table', 'elementor-express-add-ons' );
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
		return 'eicon-table';
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
				'label' => __( 'Live Data Table Widget Settings', 'elementor-express-add-ons' ),
			]
		);

		$this->add_control(
			"widget_title",
			[
				'label' => __( 'Table Title', 'elementor-express-add-ons' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$post_types = eeao_get_post_type_list();

		//var_dump( $post_types );
		$this->add_control(
			'post_type',
			[
				'label'   => __( 'Post Type', 'elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $post_types,
			]
		);
		$fields        = [];
		$groups        = acf_get_field_groups( array( 'post_type' => 'post' ) );
		$field_options = [];
		foreach ( $groups as $group ) {
			$fields = acf_get_fields( $group['key'] );
			foreach ( $fields as $field ) {
				$fields[]                       = $field;
				$field_options[ $field['name'] ] = $field['label'];
			}
		}


		$this->add_control(
			'eeao_live_data_table_fields',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => [
					[
						'name'        => 'eeao_live_data_table_field',
						'label'       => esc_html__( 'Column Field', 'elementor-express-add-ons' ),
						'default'     => '',
						'type'        => Controls_Manager::SELECT,
						'options'     => $field_options,
						'label_block' => false,
					],

				],
				'default'     => [],
				'title_field' => '{{eeao_live_data_table_field}}',
			]
		);

		//die();

		$this->end_controls_section();

		$this->add_widget_id_settings();
	}

	protected function add_widget_id_settings() {
		global $post;
		$post_id = $post->ID;
		// Get the page settings manager
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

		// Get the settings model for current post
		$page_settings_model = $page_settings_manager->get_model( $post_id );

		// Retrieve the color we added before
		$widget_id = $page_settings_model->get_settings( 'eeao_widget_id' );

		if ( is_numeric( $widget_id ) ) {
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
				'label'       => __( 'EEAO Widget ID', 'plugin-domain' ),
				'type'        => 'selectwidgetid',
				'default'     => $widget_id_default,
				'options'     => [ 1 => 1, 2 => 2, 3 => 3 ],
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
		$post_id  = $post->ID;
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'none' );
		$headers = [];

		foreach ( $settings['eeao_live_data_table_fields'] as $field ) {
			$header_info             = [];
			$header_info['_id']      = $field['_id'];
			$header_info['align']    = 'left';
			$header_info['sortable'] = 'yes';
			$header_info['value'] = $header_info['text'] = $field['eeao_live_data_table_field'];

			$headers[] = $header_info;
		}

		$itemsInfo = [];
		$items = [];

		$posts = get_posts( [
			'post_type'      => 'post',
			'posts_per_page' => - 1,
		] );
		foreach ( $posts as $loopPost ) {
			$itemObj             = [];
			$item = [];
			$itemObj['post_obj'] = $loopPost;
			foreach ( $headers as $field ) {
				//var_dump( $field['text']);
				//		var_dump( get_field_object( $field['text'] ) );
				$item[$field['value']] = get_field_object( $field['text'] , $loopPost->ID )['value'];
				$itemObj[$field['value']]['field_acf_details'] = get_field_object( $field['text'] , $loopPost->ID );
				$itemObj[$field['value']]['value'] = get_field_object( $field['text'] , $loopPost->ID );
			}
			if( !empty( $item[$field['value']] ) ){
				$items[] = $item;
				$itemsInfo[] = $itemObj;
			}
		}

		$headers[] = [ 'align' => 'left', 'sortable' => 'no', 'value' => 'action', 'text' => 'Action'];

//		var_dump( $items );
//		die();

		//$item_info['value'] = get_field();
		$settings['eeao_live_data_table_headers'] = $headers;
		$settings['eeao_live_data_table_items'] = $items;
		$original_settings                        = $settings;
		$settings                                 = eeao_convert_setting_key_to_camel_case( $settings );
		$widget_id                                = $original_settings['eeao_widget_id'];
		$settings['widgetType']                   = 'live-data-table';
		$settings_json                            = htmlspecialchars( json_encode( $settings ), ENT_QUOTES, 'UTF-8' );;
		$poll_fall_back_html = '<div class="live-data-table-fallback-html">' . $original_settings['widget_title'] . '</div>';
		echo '<div
			class="eeao-widget-data"	
          id="eeao-live-data-table-' . $post_id . '-widget-' . $widget_id . '"
          data-eeao-post-id="' . $post_id . '"
          data-eeao-widget-id="' . $widget_id . '"
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
