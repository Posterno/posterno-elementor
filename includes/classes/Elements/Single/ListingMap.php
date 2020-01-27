<?php
/**
 * Registers the single listing map element.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Elements\Single;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Posterno\Elementor\Plugin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The single listing map element.
 */
class ListingMap extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'single_listing_map';
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
		return esc_html__( 'Listing Map', 'posterno-elementor' );
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
		return 'fa fa-map-marked-alt';
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
		return array( 'posterno_single' );
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
			'note',
			array(
				'label' => false,
				'type'  => Controls_Manager::RAW_HTML,
				'raw'   => esc_html__( 'This element does not have any settings. Please refer to the documentation of the search forms plugin for more information.', 'posterno-elementor' ),
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

		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {

			Plugin::instance()->templates
				->set_template_data( $settings )
				->get_template_part( 'single-listing/map' );

		}

	}

}
