<?php
/**
 * Registers the terms list element for elementor.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Elements;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Posterno\Elementor\Helper;
use Posterno\Elementor\Plugin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The terms list block for elementor.
 */
class TermsList extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'terms_list';
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
		return esc_html__( 'Terms List', 'posterno-elementor' );
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
		return 'fa fa-clipboard-list';
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
			'taxonomy_settings',
			array(
				'label' => __( 'Taxonomy settings', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'taxonomy_id',
			[
				'label'   => esc_html__( 'Taxonomy', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => Helper::get_registered_taxonomies(),
			]
		);

		$this->add_control(
			'show_subcategories',
			[
				'label'        => esc_html__( 'Show subcategories', 'posterno-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'posterno-elementor' ),
				'label_off'    => esc_html__( 'No', 'posterno-elementor' ),
				'return_value' => 'yes',
				'default'      => false,
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'taxonomy_id',
							'operator' => 'in',
							'value'    => [
								'listings-categories',
								'listings-locations',
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'        => esc_html__( 'Hide empty terms', 'posterno-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'posterno-elementor' ),
				'label_off'    => esc_html__( 'No', 'posterno-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'number',
			[
				'label'   => esc_html__( 'Number of terms', 'posterno-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'step'    => 1,
				'default' => 999,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order by', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => [
					'name'        => 'name',
					'slug'        => 'slug',
					'term_group'  => 'term_group',
					'term_id'     => 'term_id',
					'id'          => 'id',
					'description' => 'description',
					'parent'      => 'parent',
				],
				'default' => 'name',
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => [
					'ASC'  => 'ASC',
					'DESC' => 'DESC',
				],
				'default' => 'ASC',
			]
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
		 * Filter: allow definition of additional layouts for the elementor terms list widget.
		 *
		 * @param array $layouts list of registered layouts.
		 * @return array
		 */
		$layout_options = apply_filters(
			'pno_elementor_terms_list_layouts',
			[
				'default' => esc_html__( 'Default', 'posterno-elementor' ),
			]
		);

		$this->add_control(
			'layout_mode',
			[
				'label'   => esc_html__( 'Layout style', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $layout_options,
				'default' => 'default',
			]
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

		$taxonomy = sanitize_text_field( $settings['taxonomy_id'] );
		$layout   = sanitize_text_field( $settings['layout_mode'] );

		if ( $layout === 'default' ) {

			$template_name = false;

			switch ( $taxonomy ) {
				case 'listings-categories':
					$template_name = 'terms-list-listings-categories';
					break;
				case 'listings-locations':
					$template_name = 'terms-list-listings-locations';
					break;
				case 'listings-types':
					$template_name = 'terms-list-listings-types';
					break;
				default:
					$template_name = 'terms-list-default';
					break;
			}

			Plugin::instance()->templates
				->set_template_data( $settings )
				->get_template_part( $template_name );

		} else {

			$template_name = "terms-list-{$layout}";

			Plugin::instance()->templates
				->set_template_data( $settings )
				->get_template_part( $template_name );

		}

	}

}
