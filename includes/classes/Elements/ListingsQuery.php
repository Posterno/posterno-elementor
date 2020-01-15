<?php
/**
 * Registers the listings query element for elementor.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Elements;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Posterno\Elementor\Cache;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The listings query block for elementor.
 */
class ListingsQuery extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'listings_query';
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
		return esc_html__( 'Listings query', 'posterno-elementor' );
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
		return 'fa fa-list';
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
			'query_settings',
			array(
				'label' => __( 'Query settings', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		foreach ( $this->get_registered_taxonomies() as $slug => $name ) {

			$this->add_control(
				"taxonomy_{$slug}",
				[
					'label'       => esc_html( $name ),
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'options'     => Cache::get_cached_terms( $slug ),
					'description' => esc_html__( 'Select one or more term to adjust the query.' ),
				]
			);

		}

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

		print_r( $settings );

	}

	/**
	 * Get the list of registered taxonomies for the control.
	 *
	 * @return array
	 */
	private function get_registered_taxonomies() {

		$list = [];

		$taxonomies = get_object_taxonomies( 'listings', 'objects' );

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $id => $taxonomy ) {
				if ( in_array( $id, [ 'pno-review-attribute', 'pno-review-rating-label' ], true ) ) {
					continue;
				}
				$list[ $id ] = $taxonomy->label;
			}
		}

		return $list;

	}

}
