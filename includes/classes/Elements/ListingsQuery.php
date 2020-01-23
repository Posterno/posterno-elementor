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
use Posterno\Elementor\Helper;
use Posterno\Elementor\Plugin;

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

		foreach ( Helper::get_registered_taxonomies() as $slug => $name ) {

			$this->add_control(
				"taxonomy_{$slug}",
				array(
					'label'       => esc_html( $name ),
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'options'     => Cache::get_cached_terms( $slug ),
					'description' => esc_html__( 'Select one or more term to adjust the query.', 'posterno-elementor' ),
				)
			);

		}

		$this->add_control(
			'show_featured',
			array(
				'label'        => __( 'Featured listings only', 'posterno-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'posterno-elementor' ),
				'label_off'    => esc_html__( 'No', 'posterno-elementor' ),
				'return_value' => 'yes',
				'default'      => false,
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Listings per page', 'posterno-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'step'    => 1,
				'default' => 10,
			)
		);

		$this->add_control(
			'limit_by_id',
			array(
				'label'        => esc_html__( 'Limit query to specific listings', 'posterno-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'posterno-elementor' ),
				'label_off'    => esc_html__( 'No', 'posterno-elementor' ),
				'return_value' => 'yes',
				'default'      => false,
			)
		);

		$this->add_control(
			'listings_ids',
			array(
				'label'       => esc_html__( 'Listings IDs', 'posterno-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Example: 55, 98', 'posterno-elementor' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'  => 'limit_by_id',
							'value' => 'yes',
						),
					),
				),
				'description' => esc_html__( 'Enter one or more listing id number separated by a comma.', 'posterno-elementor' ),
			)
		);

		$this->add_control(
			'query_authors',
			array(
				'label'       => esc_html__( 'Authors', 'posterno-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'ID numbers', 'posterno-elementor' ),
				'description' => esc_html__( 'Enter one or more users ID numbers separated by a comma to limit listings by specific authors only.', 'posterno-elementor' ),
			)
		);

		$this->add_control(
			'pagination',
			array(
				'label'    => esc_html__( 'Pagination', 'posterno-elementor' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => true,
				'options'  => array(
					'enabled'  => esc_html__( 'Enabled', 'posterno-elementor' ),
					'disabled' => esc_html__( 'Disabled', 'posterno-elementor' ),
				),
				'default'  => 'disabled',
			)
		);

		$this->add_control(
			'filter_id',
			array(
				'label'       => esc_html__( 'Query ID', 'posterno-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Optional string if you wish to filter the query arguments programmatically.', 'posterno-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_settings',
			array(
				'label' => __( 'Layout', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		/**
		 * Filter: allow definition of additional layouts for the elementor listings query widget.
		 *
		 * @param array $layouts list of registered layouts.
		 * @return array
		 */
		$layout_options = apply_filters(
			'pno_elementor_listings_query_layouts',
			array(
				'grid' => esc_html__( 'Grid', 'posterno-elementor' ),
				'list' => esc_html__( 'List', 'posterno-elementor' ),
			)
		);

		$this->add_control(
			'layout_mode',
			array(
				'label'    => esc_html__( 'Layout style', 'posterno-elementor' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => true,
				'options'  => $layout_options,
				'default'  => 'list',
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

		if ( in_array( $settings['layout_mode'], array( 'grid', 'list' ), true ) ) {

			Plugin::instance()->templates
				->set_template_data( $settings )
				->get_template_part( 'listings-query' );

		} else {

			Plugin::instance()->templates
				->set_template_data( $settings )
				->get_template_part( 'listings-query-' . $settings['layout_mode'] );

		}

	}

}
