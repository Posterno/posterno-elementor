<?php
/**
 * Registers the faceted pagination element for elementor.
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
 * The faceted pagination element for elementor.
 */
class FacetPagination extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'faceted_pagination';
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
		return esc_html__( 'Faceted Pagination', 'posterno-elementor' );
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
		return 'fa fa-ellipsis-h';
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
				'label' => __( 'Pagination settings', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'note',
			array(
				'label' => false,
				'type'  => Controls_Manager::RAW_HTML,
				'raw'   => esc_html__( 'This element does not have any settings.', 'plugin-name' ),
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

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {

			echo esc_html( $this->get_title() );

			posterno()->templates
				->set_template_data(
					array(
						'type'    => 'info',
						'message' => esc_html__( 'Output of this element is visible only when not within the Elementor Editor.' ),
					)
				)
				->get_template_part( 'message' );

		} else {

			echo do_shortcode( '[pno-search-pagination]' );

		}

	}

}
