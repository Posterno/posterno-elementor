<?php
/**
 * Registers the faceted submit button element for elementor.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Elements;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The faceted submit button element for elementor.
 */
class FacetSearchSubmit extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'faceted_submit';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Faceted Search Submit', 'posterno-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-search-plus';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'posterno' );
	}

	/**
	 * Register controls for the widget.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'faceted_settings',
			array(
				'label' => __( 'Settings', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_label',
			array(
				'label'   => esc_html__( 'Button label', 'posterno-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Search', 'posterno-elementor' ),
			)
		);

		$this->add_control(
			'redirect_url',
			array(
				'label'       => esc_html__( 'Redirect URL', 'posterno-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'https://example.com/page', 'posterno-elementor' ),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render output on the frontend.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$label = isset( $settings['button_label'] ) && ! empty( $settings['button_label'] ) ? esc_html( $settings['button_label'] ) : esc_html__( 'Search', 'posterno-elementor' );

		$redirect_url = isset( $settings['redirect_url'] ) && ! empty( $settings['redirect_url'] ) ? esc_url( $settings['redirect_url'] ) : false;

		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {

			if ( $redirect_url ) {

				echo do_shortcode( '[pno-search-submit label="' . $label . '" submit="' . $redirect_url . '"]' );

			} else {

				posterno()->templates
					->set_template_data(
						array(
							'type'    => 'info',
							'message' => esc_html__( 'Please enter a redirect url.', 'posterno-elementor' ),
						)
					)
					->get_template_part( 'message' );

			}

		}

	}

}
