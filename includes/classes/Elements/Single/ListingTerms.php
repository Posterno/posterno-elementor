<?php
/**
 * Registers the listing terms list element.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Elements\Single;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Posterno\Elementor\Helper;
use Posterno\Elementor\Plugin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The listing terms list element.
 */
class ListingTerms extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'single_listing_terms_list';
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
		return esc_html__( 'Listing terms list', 'posterno-elementor' );
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
		return 'fa fa-list-ul';
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
			'settings',
			array(
				'label' => __( 'Settings', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'taxonomy_id',
			array(
				'label'   => esc_html__( 'Taxonomy', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => Helper::get_registered_taxonomies(),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order by', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => array(
					'name'        => 'name',
					'slug'        => 'slug',
					'term_group'  => 'term_group',
					'term_id'     => 'term_id',
					'id'          => 'id',
					'description' => 'description',
					'parent'      => 'parent',
				),
				'default' => 'name',
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => array(
					'ASC'  => 'ASC',
					'DESC' => 'DESC',
				),
				'default' => 'ASC',
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => esc_html__( 'Hide empty terms', 'posterno-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'posterno-elementor' ),
				'label_off'    => esc_html__( 'No', 'posterno-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'number',
			array(
				'label'   => esc_html__( 'Number of terms', 'posterno-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'step'    => 1,
				'default' => 999,
			)
		);

		$this->add_control(
			'include',
			array(
				'label'       => esc_html__( 'Include', 'posterno-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Comma-separated string of term ids to include.' ),
			)
		);

		$this->add_control(
			'exclude',
			array(
				'label'       => esc_html__( 'Exclude', 'posterno-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Comma-separated string of term ids to exclude. If the "include" option is not empty, the exclude option will be ignored.' ),
			)
		);

		$this->add_control(
			'parent',
			array(
				'label'       => esc_html__( 'Parent', 'posterno-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Parent term ID to retrieve direct-child terms of.' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings_layout',
			array(
				'label' => esc_html__( 'Layout', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$layouts = apply_filters(
			'pno_elementor_single_terms_list_layouts',
			array(
				'vertical'   => esc_html__( 'Vertical' ),
				'horizontal' => esc_html__( 'Horizontal' ),
			)
		);

		$this->add_control(
			'layout_skin',
			array(
				'label'   => esc_html__( 'List layout', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $layouts,
				'default' => 'vertical',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon layout', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => array(
					'disabled' => esc_html__( 'Disabled' ),
					'custom'   => esc_html__( 'Custom icon' ),
					'database' => esc_html__( 'Term icon' ),
				),
				'default' => 'disabled',
			)
		);

		$this->add_control(
			'custom_icon',
			array(
				'label'      => esc_html__( 'Custom icon' ),
				'type'       => Controls_Manager::ICONS,
				'default'    => array(
					'value'   => 'fas fa-star',
					'library' => 'solid',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'icon',
							'operator' => 'in',
							'value'    => array(
								'custom',
							),
						),
					),
				),
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

		Plugin::instance()->templates
			->set_template_data( $settings )
			->get_template_part( 'single-listing/terms-list' );

	}

}
