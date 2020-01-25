<?php
/**
 * Registers the search facet element for elementor.
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
 * The search facet element for elementor.
 */
class SearchFacet extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'search_facet';
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
		return esc_html__( 'Search Facet', 'posterno-elementor' );
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
		return 'fa fa-search';
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
			'facet_settings',
			array(
				'label' => __( 'Facet settings', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'facet_id',
			array(
				'label'   => esc_html__( 'Search facet', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->get_facets(),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Get list of facets.
	 *
	 * @return array
	 */
	private function get_facets() {

		$list = array();

		$facets = \Posterno\Search\Helper::get_facets();

		if ( ! empty( $facets ) && is_array( $facets ) ) {
			foreach ( $facets as $facet ) {

				$list[ absint( $facet->get_id() ) ] = esc_html( $facet->get_name() );

			}
		}

		return $list;

	}

	/**
	 * Render output on the frontend.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( ! isset( $settings['facet_id'] ) || empty( $settings['facet_id'] ) ) {
			return;
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {

			echo esc_html( $this->get_title() );

			posterno()->templates
				->set_template_data(
					array(
						'type'    => 'info',
						'message' => esc_html__( 'Output of this element is visible only when not within the Elementor Editor.', 'posterno-elementor' ),
					)
				)
				->get_template_part( 'message' );

		} else {

			echo do_shortcode( '[pno-search-facet facet="' . absint( $settings['facet_id'] ) . '"]' );

		}

	}

}
