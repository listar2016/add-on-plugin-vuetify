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
class Post_Rating extends Widget_Base {

	static $STAR_SIZE_DEFAULT = 30;

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
		return 'eeao-post-rating';
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
		return __( 'EEAO Post Rating', 'elementor-express-add-ons' );
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
		return 'eicon-rating';
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
		return [ 'elementor-express-post-rating' ];
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

		$show_rating_widget_conditions = [
			'terms' => [
				[
					'name'  => 'show_rating_widget',
					'value' => 'yes',
				],
			]
		];

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Widget Settings', 'elementor-express-add-ons' ),
			]
		);

		$this->add_control(
			'show_rating_widget',
			[
				'label'   => __( 'Show Rating Widget', 'elementor-express-add-ons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
		$this->add_control(
			'show_rating_text',
			[
				'label'      => __( 'Show Rating Text', 'elementor-express-add-ons' ),
				'default'    => 'yes',
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_widget_conditions

			]
		);
		$this->add_control(
			'rating_statistics',
			[
				'label'   => __( 'Show Rating Statistics', 'elementor-express-add-ons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'star_color',
			[
				'label'      => __( 'Star Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'default'    => eeao_default_star_color(),
				'conditions' => $show_rating_widget_conditions
			]
		);

		$this->add_control(
			'inactive_star_color',
			[
				'label'      => __( 'Inactive Star Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'default'    => eeao_default_inactive_star_color(),
				'conditions' => $show_rating_widget_conditions
			]
		);
		$this->add_control(
			'hr',
			[
				'type'       => \Elementor\Controls_Manager::DIVIDER,
				'conditions' => $show_rating_widget_conditions
			]
		);
		$this->add_control(
			'full_icon',
			[
				'label'      => __( 'Icon', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::ICONS,
				'multiple'   => false,
				'default'    => [
					'value' => 'mdi mdi-star'
				],
				'conditions' => $show_rating_widget_conditions
			]
		);

		$this->add_control(
			'icon_border_heading',
			[
				'label'      => __( 'Icon Border', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => $show_rating_widget_conditions
			]
		);
		$show_icon_border_conditions = [
			'terms' => [
				[
					'name'  => 'icon_border',
					'value' => 'yes',
				],
			]
		];
		$this->add_control(
			'icon_border',
			[
				'label'      => __( 'Show Border', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_widget_conditions
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label'      => __( 'Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'default'    => '#b7b7b7',
				'conditions' => $show_icon_border_conditions
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label'      => __( 'Width', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'size' ],
				'range'      => [
					'size' => [
						'min'  => 1,
						'max'  => 10,
						'step' => 1
					],
				],
				'default'    => [
					'unit' => 'size',
					'size' => 1,
				],
				'conditions' => $show_icon_border_conditions
			]
		);
		$this->add_control(
			'hr1',
			[
				'type'       => \Elementor\Controls_Manager::DIVIDER,
				'conditions' => $show_rating_widget_conditions
			]
		);

		$this->add_control(
			'readonly',
			[
				'label'      => __( 'Readonly', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_widget_conditions
			]
		);
		$this->add_control(
			'star_padding',
			[
				'label'      => __( 'Space between stars', 'elementor-express-add-ons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'size' ],
				'range' => [
					'size' => [
						'min' => 0,
						'max' => 12,
						'step' => 1
					],
				],
				'default' => [
					'unit' => 'size',
					'size' => 2,
				],
			]
		);
		$this->add_control(
			'dense',
			[
				'label'      => __( 'Dense', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SWITCHER,
				'default'    => 'yes',
				'conditions' => $show_rating_widget_conditions
			]
		);
		$this->add_control(
			'half_increments',
			[
				'label'      => __( 'Half increments', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SWITCHER,
				'default'    => 'yes',
				'conditions' => $show_rating_widget_conditions
			]
		);
		$this->add_control(
			'star_size',
			[
				'label'      => __( 'Star Size', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'size' ],
				'range'      => [
					'size' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 0.5
					],
				],
				'default'    => [
					'unit' => 'size',
					'size' => self::$STAR_SIZE_DEFAULT,
				],
				'conditions' => $show_rating_widget_conditions
			]
		);
		$this->add_control(
			'show_icon_rank_on_hover',
			[
				'label'      => __( 'Show Icon Rank On Hover', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SWITCHER,
				'default'    => 'yes',
				'conditions' => $show_rating_widget_conditions
			]
		);
		$show_icon_rank_conditions = [
			'terms' => [
				$show_rating_widget_conditions['terms'][0],
				[
					'name'  => 'show_icon_rank_on_hover',
					'value' => 'yes',
				],
			]
		];
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'       => 'icon_rank_text_typography',
				'label'      => __( 'Icon Rank Text Typography', 'elementor-express-add-ons' ),
				'scheme'     => \Elementor\Scheme_Typography::TYPOGRAPHY_3,
				'selector'   => '{{WRAPPER}} .icon-rank-text',
				'conditions' => $show_icon_rank_conditions
			]
		);
		$this->add_control(
			'icon_rank_text_color',
			[
				'label'      => __( 'Icon Rank Text Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'scheme'     => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_3,
				],
				'selectors'  => [
					'{{WRAPPER}} .icon-rank-text' => 'color: {{VALUE}};',
				],
				'conditions' => $show_icon_rank_conditions
			]
		);
		$this->add_control(
			'hr2',
			[
				'type'       => \Elementor\Controls_Manager::DIVIDER,
				'conditions' => $show_rating_widget_conditions
			]
		);

		$this->add_control(
			'rtl',
			[
				'label'      => __( 'RTL', 'elementor-express-add-ons' ),
				'default'    => is_rtl() ? 'yes' : '',
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_widget_conditions
			]
		);

		$this->end_controls_section();
		$this->show_rating_text_settings( $show_rating_widget_conditions );

		$this->show_rating_statistics_settings();
		$this->show_loader_settings( $show_rating_widget_conditions );
		$this->show_data_settings();

	}

	protected function show_rating_text_settings( $show_rating_widget_conditions ) {

		$show_rating_text_conditions = [
			'terms' => [
				$show_rating_widget_conditions['terms'][0],
				[
					'name'  => 'show_rating_text',
					'value' => 'yes'
				],
			],
		];
		$this->start_controls_section(
			'rating_text_section',
			[
				'label'      => __( 'Rating Text Settings', 'elementor-express-add-ons' ),
				'tab'        => \Elementor\Controls_Manager::TAB_CONTENT,
				'conditions' => $show_rating_text_conditions
			]
		);

		$this->add_control(
			'rating_text_position',
			[
				'label'      => __( 'Rating Text Position', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::SELECT,
				'default'    => 'after',
				'options'    => [
					'after'  => __( 'Inline after widget', 'elementor-express-add-ons' ),
					'before' => __( 'Inline before widget', 'elementor-express-add-ons' ),
					'below'  => __( 'Below widget', 'elementor-express-add-ons' ),
					'above'  => __( 'Above widget', 'elementor-express-add-ons' ),

				],
				'conditions' => $show_rating_text_conditions
			]
		);

		$this->add_control(
			'rating_string',
			[
				'label'       => __( 'Rating Text', 'elementor-express-add-ons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => __( 'Rated {{ratings_average}} by {{ratings_count}} Readers', 'elementor-express-add-ons' ),
				'placeholder' => __( 'Rated {{ratings_average}} by {{ratings_count}} Readers', 'elementor-express-add-ons' ),
				'description' => __( 'Rating text should contain {{ratings_average}} and {{ratings_count}} in order to show Rating average and Ratings count', 'elementor-express-add-ons' ),
				'conditions'  => $show_rating_text_conditions
			]
		);

		$this->add_control(
			'no_ratings_string',
			[
				'label'       => __( 'No Rating Text', 'elementor-express-add-ons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => eeao_no_ratings_yet(),
				'placeholder' => eeao_no_ratings_yet(),
				'description' => __( 'This string will display when no ratings for post', 'elementor-express-add-ons' ),
				'conditions'  => $show_rating_text_conditions
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'       => 'content_typography',
				'label'      => __( 'Typography', 'elementor-express-add-ons' ),
				'scheme'     => \Elementor\Scheme_Typography::TYPOGRAPHY_3,
				'selector'   => '{{WRAPPER}} .post-rating-text',
				'conditions' => $show_rating_text_conditions
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'      => __( 'Text Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'scheme'     => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_3,
				],
				'selectors'  => [
					'{{WRAPPER}} .post-rating-text' => 'color: {{VALUE}};',
				],
				'conditions' => $show_rating_text_conditions
			]
		);

		$this->end_controls_section();
	}

	protected function show_rating_statistics_settings() {

		$show_rating_statistics_conditions = [
			'terms' => [
				[
					'name'  => 'rating_statistics',
					'value' => 'yes',
				]
			]
		];
		$this->start_controls_section(
			'rating_statistics_section',
			[
				'label'      => __( 'Rating Statistics Settings', 'elementor-express-add-ons' ),
				'tab'        => \Elementor\Controls_Manager::TAB_CONTENT,
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'rating_statistics_display',
			[
				'label'   => __( 'Show rating statistics', 'elementor-express-add-ons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'   => __( 'Do not show', 'elementor-express-add-ons' ),
					'on_hover'   => __( 'Show below', 'elementor-express-add-ons' ),
					'on_below' => __( 'Show above', 'elementor-express-add-ons' ),
				]
			]
		);

		$on_hover_conditions = [
			'terms' => [
				[
					'name'  => 'rating_statistics_display',
					'value' => 'on_hover',
				],
			]
		];
		$this->add_control(
			'popover_close_delay',
			[
				'label'      => __( 'Popover Close Delay', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::NUMBER,
				'min'        => 1,
				'max'        => 100,
				'step'       => 1,
				'default'    => 2,
				'conditions' => $on_hover_conditions
			]
		);
		$this->add_control(
			'popover_triangle',
			[
				'label'      => __( 'Popover Triangle', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SWITCHER,
				'default'    => 'yes',
				'conditions' => $on_hover_conditions
			]
		);
		$this->add_control(
			'popover_position',
			[
				'label'      => __( 'Popover Position', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::SELECT,
				'default'    => 'top',
				'options'    => [
					'top'    => __( 'Top', 'elementor-express-add-ons' ),
					'bottom' => __( 'Bottom', 'elementor-express-add-ons' ),
				],
				'conditions' => $on_hover_conditions
			]
		);
		$this->add_control(
			'popover_nudge_left',
			[
				'label'      => __( 'Popover Nudge Left', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 700,
						'step' => 1,
					]
				],
				'default'    => [
					'unit' => 'px',
					'size' => 90,
				],
				'conditions' => $on_hover_conditions
			]
		);
		$this->add_control(
			'popover_show_rank_stars',
			[
				'label'      => __( 'Show Rank Stars On Popover', 'elementor-express-add-ons' ),
				'default'    => 'no',
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $on_hover_conditions
			]
		);

		$this->add_control(
			'progress_color',
			[
				'label'      => __( 'Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'default'    => eeao_default_star_color(),
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_bg_color',
			[
				'label'      => __( 'Background Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'default'    => eeao_default_inactive_star_color(),
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_width',
			[
				'label'      => __( 'Width', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 150,
						'max'  => 300,
						'step' => 10,
					]
				],
				'default'    => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors'  => [
					'{{WRAPPER}} .vrw-star-progress' => 'width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_height',
			[
				'label'      => __( 'Height', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'height' ],
				'range'      => [
					'height' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1
					],
				],
				'default'    => [
					'unit' => 'height',
					'size' => 20,
				],
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_opacity',
			[
				'label'      => __( 'Opacity', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'opacity' ],
				'range'      => [
					'opacity' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1
					],
				],
				'default'    => [
					'unit' => 'opacity',
					'size' => 0.3,
				],
				'conditions' => $show_rating_statistics_conditions
			]
		);

		$this->add_control(
			'progress_rounded',
			[
				'label'      => __( 'Rounded', 'elementor-express-add-ons' ),
				'default'    => 'no',
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_striped',
			[
				'label'      => __( 'Striped', 'elementor-express-add-ons' ),
				'default'    => 'no',
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_rank_stars_sep',
			[
				'label'      => __( '', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_show_rank_stars',
			[
				'label'      => __( 'Show Rank Stars', 'elementor-express-add-ons' ),
				'default'    => 'no',
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$rank_stars_size_conditions = [
			'terms' => [
				$show_rating_statistics_conditions,
				[
					'name'  => 'progress_show_rank_stars',
					'value' => 'yes',
				],
			]
		];
		$this->add_control(
			'progress_star_size',
			[
				'label'      => __( 'Rank Star Size', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'size' ],
				'range'      => [
					'size' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 0.5
					],
				],
				'default'    => [
					'unit' => 'size',
					'size' => 25
				],
				'conditions' => $rank_stars_size_conditions
			]
		);
		$show_percentage_typo_conditions = [
			'terms' => [
				$show_rating_statistics_conditions,
				[
					'name'  => 'progress_show_votes_percentage',
					'value' => 'yes',
				],
			]
		];
		$this->add_control(
			'progress_show_votes_percentage',
			[
				'label'      => __( 'Show Voting Percentage', 'elementor-express-add-ons' ),
				'default'    => 'yes',
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'       => 'progress_percentage_typography',
				'label'      => __( 'Voting Percentage Typography', 'elementor-express-add-ons' ),
				'scheme'     => \Elementor\Scheme_Typography::TYPOGRAPHY_3,
				'selector'   => '{{WRAPPER}} .vrw-star-progress-right-percentage-text',
				'conditions' => $show_percentage_typo_conditions
			]
		);
		$this->add_control(
			'progress_percentage_text_color',
			[
				'label'      => __( 'Voting Percentage Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'scheme'     => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_3,
				],
				'selectors'  => [
					'{{WRAPPER}} .vrw-star-progress-right-percentage-text' => 'color: {{VALUE}};',
				],
				'conditions' => $show_percentage_typo_conditions
			]
		);

		$this->add_control(
			'progress_padding',
			[
				'label'      => __( 'Padding', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					]
				],
				'default'    => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .progress-col' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_margin',
			[
				'label'      => __( 'Margin', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					]
				],
				'default'    => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors'  => [
					'{{WRAPPER}} .progress-col' => 'margin: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			"progress_column_ordering",
			[
				'label'      => __( 'Ordering', 'elementor-express-add-ons' ),
				'type'       => \ElementorExpressAddOns\Controls\Control_Statistics_Ordering::$TYPE,
				'options'    => [
					'RankText'        => __( 'Rank Text', 'elementor-express-add-ons' ),
					'RankStar'        => __( 'Rank Star', 'elementor-express-add-ons' ),
					'ProgressBar'     => __( 'Progress Bar', 'elementor-express-add-ons' ),
					'VotesText'       => __( 'Votes Count', 'elementor-express-add-ons' ),
					'VotesPercentage' => __( 'Votes Percentage', 'elementor-express-add-ons' ),
				],
				'default'    => [
					'RankText'        => 1,
					'RankStar'        => 2,
					'ProgressBar'     => 3,
					'VotesText'       => 4,
					'VotesPercentage' => 5
				],
				'conditions' => $show_rating_statistics_conditions
			]
		);


		$this->add_control(
			'progress_content_typography',
			[
				'label'      => __( 'Rank Text', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_show_rank_text',
			[
				'label'      => __( 'Show Rank Text', 'elementor-express-add-ons' ),
				'default'    => 'yes',
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$show_rank_text_conditions = [
			'terms' => [
				$show_rating_statistics_conditions,
				[
					'name'  => 'progress_show_rank_text',
					'value' => 'yes'
				],
			],
		];
		$this->add_control(
			'progress_rank_text_width',
			[
				'label'      => __( 'Rank Text Min Width', 'elementor-express-add-ons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 400,
						'step' => 1,
					]
				],
				'default'    => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .vrw-star-progress-left-text' => 'width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => $show_rank_text_conditions
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'       => 'progress_left_content_typography',
				'label'      => __( 'Typography', 'elementor-express-add-ons' ),
				'scheme'     => \Elementor\Scheme_Typography::TYPOGRAPHY_3,
				'selector'   => '{{WRAPPER}} .vrw-star-progress-left-text',
				'conditions' => $show_rank_text_conditions
			]
		);
		$this->add_control(
			'progress_left_text_color',
			[
				'label'      => __( 'Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'scheme'     => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_3,
				],
				'selectors'  => [
					'{{WRAPPER}} .vrw-star-progress-left-text' => 'color: {{VALUE}};',
				],
				'conditions' => $show_rank_text_conditions
			]
		);
		for ( $star = 5; $star > 0; $star -- ) {
			$star_text    = ( $star > 1 ) ? "Stars" : "Star";
			$heading_text = "{$star} {$star_text}";
			$this->add_control(
				"progress_rank_text_heading_{$star}",
				[
					'label'      => __( $heading_text, 'elementor-express-add-ons' ),
					'type'       => \Elementor\Controls_Manager::HEADING,
					'separator'  => 'none',
					'conditions' => $show_rank_text_conditions
				]
			);
			$this->add_control(
				"progress_rank_text_{$star}_before",
				[
					'label'      => __( 'Before', 'elementor-express-add-ons' ),
					'type'       => \ElementorExpressAddOns\Controls\Control_Custom_Text::$TYPE,
					'maxlength'  => 100,
					'conditions' => $show_rank_text_conditions
				]
			);
			$this->add_control(
				"progress_rank_text_{$star}_after",
				[
					'label'      => __( 'After', 'elementor-express-add-ons' ),
					'type'       => \ElementorExpressAddOns\Controls\Control_Custom_Text::$TYPE,
					'maxlength'  => 100,
					'default'    => $star_text,
					'conditions' => $show_rank_text_conditions
				]
			);
		}

		$this->add_control(
			'progress_right_content_typography',
			[
				'label'      => __( 'Votes Number Text', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$this->add_control(
			'progress_show_votes_number_text',
			[
				'label'      => __( 'Show Votes Number Text', 'elementor-express-add-ons' ),
				'default'    => 'yes',
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => $show_rating_statistics_conditions
			]
		);
		$show_vote_text_conditions = [
			'terms' => [
				$show_rating_statistics_conditions,
				[
					'name'  => 'progress_show_votes_number_text',
					'value' => 'yes',
				],
			],
		];

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'       => 'progress_right_content_typography',
				'label'      => __( 'Typography', 'elementor-express-add-ons' ),
				'scheme'     => \Elementor\Scheme_Typography::TYPOGRAPHY_3,
				'selector'   => '{{WRAPPER}} .vrw-star-progress-right-text',
				'conditions' => $show_vote_text_conditions
			]
		);
		$this->add_control(
			'progress_right_text_color',
			[
				'label'      => __( 'Color', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::COLOR,
				'scheme'     => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_3,
				],
				'selectors'  => [
					'{{WRAPPER}} .vrw-star-progress-right-text' => 'color: {{VALUE}};',
				],
				'conditions' => $show_vote_text_conditions
			]
		);
		for ( $star = 5; $star > 0; $star -- ) {
			$star_text    = ( $star > 1 ) ? "stars" : "star";
			$vote_text    = "votes";
			$heading_text = "{$star} {$star_text}";
			$this->add_control(
				"progress_votes_text_heading_{$star}",
				[
					'label'      => __( $heading_text, 'elementor-express-add-ons' ),
					'type'       => \Elementor\Controls_Manager::HEADING,
					'separator'  => 'none',
					'conditions' => $show_vote_text_conditions
				]
			);
			$this->add_control(
				"progress_votes_text_{$star}_before",
				[
					'label'      => __( 'Before', 'elementor-express-add-ons' ),
					'type'       => \ElementorExpressAddOns\Controls\Control_Custom_Text::$TYPE,
					'maxlength'  => 100,
					'conditions' => $show_vote_text_conditions
				]
			);
			$this->add_control(
				"progress_votes_text_{$star}_after",
				[
					'label'      => __( 'After', 'elementor-express-add-ons' ),
					'type'       => \ElementorExpressAddOns\Controls\Control_Custom_Text::$TYPE,
					'maxlength'  => 100,
					'default'    => "",
					'conditions' => $show_vote_text_conditions
				]
			);
		}
		$this->end_controls_section();
	}

	protected function show_loader_settings( $show_loader_conditions ) {

		$this->start_controls_section(
			'loader_section',
			[
				'label' => __( 'Loader Settings', 'elementor-express-add-ons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
//				'conditions' => $show_loader_conditions
			]
		);

		$this->add_control(
			'loader_color',
			[
				'label'   => __( 'Loader Color', 'elementor-express-add-ons' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffd055'
			]
		);
		$this->add_control(
			'loader_bg_color',
			[
				'label'   => __( 'Loader Background Color', 'elementor-express-add-ons' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#d8d8d8'
			]
		);
		$this->add_control(
			'loader_height',
			[
				'label'      => __( 'Loader Height', 'elementor-express-add-ons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'size' ],
				'range'      => [
					'size' => [
						'min'  => 1,
						'max'  => 20,
						'step' => 1
					],
				],
				'default'    => [
					'unit' => 'size',
					'size' => 6,
				],
			]
		);
		$this->add_control(
			'loader_rounded',
			[
				'label'   => __( 'Loader Rounded', 'elementor-express-add-ons' ),
				'default' => 'yes',
				'type'    => Controls_Manager::SWITCHER,
			]
		);
		$this->end_controls_section();
	}

	protected function show_data_settings() {
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
				'label' => __( 'Data Settings', 'elementor-express-add-ons' ),
			]
		);

		$this->add_control(
			'eeao_widget_id',
			[
				'label'       => __( 'EEAO Widget ID', 'plugin-domain' ),
				'type'        => 'selectwidgetid',
				//'default' => $widget_id_default,
				'options'     => [ 1 => 1, 2 => 2, 3 => 3 ],
				'description' => __( 'If you want to add a Second widget with separate rating, create a new Widget ID', 'elementor-express-add-ons' ),
			]
		);

		$this->add_control(
			'eeao_reset_rating_hidden',
			[
				'type' => \Elementor\Controls_Manager::HIDDEN,
			]
		);
		$this->add_control(
			'eeao_reset_rating_button',
			[
				'type'        => \Elementor\Controls_Manager::BUTTON,
				'separator'   => 'before',
				'button_type' => 'default',
				'text'        => __( 'Reset Rating', 'elementor-express-add-ons' ),
				'event'       => 'reset_rating',
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
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'none' );
		global $post;
		$post_id            = $post->ID;
		$widget_id          = $settings['eeao_widget_id'] ? $settings['eeao_widget_id'] : 1;
		$rating_info        = eeao_get_average_rating( $post_id, $widget_id );
		$ratings_statistics = null;
		if ( $rating_info ) {
			$rating         = $rating_info["rating"];
			$ratings_count  = $rating_info["count"];
			$rating_message = eeao_get_rating_message( $settings['rating_string'], $rating, $ratings_count );;
			$ratings_statistics = eeao_get_rating_statistics_result( $post_id, $widget_id );
		} else {
			$rating         = 0;
			$rating_message = eeao_no_ratings_yet();
			$ratings_count  = 0;
		}
		$settings['showRatingStatistics']        = ( $settings['rating_statistics'] === 'yes' && $settings['rating_statistics_display'] === 'none' ) ? 'no' : 'yes';
		$settings['showRatingStatisticsOnHover'] = ( $settings['rating_statistics'] === 'yes' && $settings['rating_statistics_display'] === 'on_hover' ) ? 'yes' : 'no';
		$settings['showRatingStatisticsOnBelow'] = ( $settings['rating_statistics'] === 'yes' && $settings['rating_statistics_display'] === 'on_below' ) ? 'yes' : 'no';

		$settings['progressRankText']  = eeao_progress_get_rank_text( $settings );
		$settings['progressVotesText'] = eeao_progress_get_votes_text( $settings );
		// convert setting key to camel case
		$original_settings        = $settings;
		$settings                 = eeao_convert_setting_key_to_camel_case( $settings );
		$settings['fullIcon']     = isset( $settings['fullIcon'] ) ? $settings['fullIcon']['value'] : '';
		$settings['postId']       = $post_id;
		$settings['rating']       = $rating;
		$settings['ratingText']   = $rating_message;
		$settings['ratingsCount'] = $ratings_count;
		$settings['widgetType']   = 'star-rating';
		$settings['statistics']   = $ratings_statistics;
		$settings['renderClass']  = 'eeao-rating-widget-root';

		$settings_json = htmlspecialchars( json_encode( $settings ), ENT_QUOTES, 'UTF-8' );;
		echo '<div
			class="eeao-widget-data"	
          id="eeao-rating-post-' . $post_id . '-widget-' . $widget_id . '"
          data-eeao-post-id="' . $post_id . '"
		  data-eeao-widget-id="' . $widget_id . '"
          data-eeao-widget-settings="' . $settings_json . '"
        > 
        <div>' . $rating_message . '</div>
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
