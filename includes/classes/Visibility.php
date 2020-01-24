<?php
/**
 * Handles the visibility controls and settings for elements.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace Posterno\Elementor;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Adds controls to Elementor widgets to determine their visibility.
 */
class Visibility {

	/**
	 * Class instance.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Get the class instance
	 *
	 * @return static
	 */
	public static function get_instance() {
		return null === self::$instance ? ( self::$instance = new self() ) : self::$instance;
	}

	/**
	 * Get things started.
	 */
	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'register_section' ) );
		add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'register_section' ) );
		add_action( 'elementor/element/common/posterno_visibility_section/before_section_end', array( $this, 'register_controls' ), 10, 2 );
		add_action( 'elementor/element/section/posterno_visibility_section/before_section_end', array( $this, 'register_controls' ), 10, 2 );

		add_filter('elementor/widget/render_content', [ $this, 'content_change' ], 999, 2 );
		add_filter('elementor/section/render_content', [ $this, 'content_change' ], 999, 2 );

		add_filter( 'elementor/frontend/section/should_render', [ $this, 'section_should_render' ] , 10, 2 );

	}

	/**
	 * Register new settings section for elementor widgets.
	 *
	 * @param object $manager elementor manager.
	 * @return void
	 */
	public function register_section( $manager ) {

		$manager->start_controls_section(
			'posterno_visibility_section',
			array(
				'tab'   => Controls_Manager::TAB_ADVANCED,
				'label' => esc_html__( 'Visibility control', 'posterno-elementor' ),
			)
		);

		$manager->end_controls_section();

	}

	public function register_controls( $element, $args ) {

		$element->add_control(
			'posterno_visibility_enabled',
			array(
				'label'        => esc_html__( 'Enable visibility conditions', 'posterno-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'posterno-elementor' ),
				'label_off'    => esc_html__( 'No', 'posterno-elementor' ),
				'return_value' => 'yes',
			)
		);

		$element->add_control(
			'posterno_visibility_role_visible',
			array(
				'type'      => Controls_Manager::SELECT2,
				'label'     => esc_html__( 'Visible for:', 'posterno-elementor' ),
				'options'   => $this->get_visibility_options(),
				'default'   => array(),
				'multiple'  => true,
				'condition' => array(
					'posterno_visibility_enabled'     => 'yes',
					'posterno_visibility_role_hidden' => array(),
				),
			)
		);

		$element->add_control(
			'posterno_visibility_role_hidden',
			array(
				'type'      => Controls_Manager::SELECT2,
				'label'     => esc_html__( 'Hidden for:', 'posterno-elementor' ),
				'options'   => $this->get_visibility_options(),
				'default'   => array(),
				'multiple'  => true,
				'condition' => array(
					'posterno_visibility_enabled'      => 'yes',
					'posterno_visibility_role_visible' => array(),
				),
			)
		);

	}

	/**
	 * Get the list of visibility options.
	 *
	 * @return array
	 */
	private function get_visibility_options() {

		$options = [
			'user' => esc_html__( 'Logged in' ),
			'guest' => esc_html__( 'Logged out' ),
		];

		return apply_filters( 'pno_elementor_visibility_options', $options );

	}

	/**
	 * Hide content based on selected conditions.
	 *
	 * @param string $content the content to output.
	 * @param object $widget elementor widget instance.
	 * @return string
	 */
	public function content_change( $content, $widget ) {

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return $content;
		}

		$settings = $widget->get_settings();

		if ( ! $this->should_render( $settings ) ) {
			return '';
		}

		return $content;

	}

	/**
	 * Detect whether or not a section should render.
	 *
	 * @param bool $should_render whether or not the section should render.
	 * @param object $section elementor widget instance.
	 * @return bool
	 */
	public function section_should_render( $should_render, $section ) {

		$settings = $section->get_settings();

		if ( ! $this->should_render( $settings ) ) {
			return false;
		}

		return $should_render;

	}

	/**
	 * Process the visibility conditions specified for widgets and sections.
	 *
	 * @param array $settings settings list.
	 * @return boolean
	 */
	private function should_render( $settings ) {

		return true;

	}


}
