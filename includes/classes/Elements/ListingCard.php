<?php
/**
 * Registers the single listing card element for elementor.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Elements;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Posterno\Elementor\Plugin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The listing card block for elementor.
 */
class ListingCard extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'listing_card';
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
		return esc_html__( 'Listing card', 'posterno-elementor' );
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
		return 'fa fa-list-alt';
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
			'listing_settings',
			array(
				'label' => __( 'Listing settings', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'listing_id',
			[
				'label' => esc_html__( 'Listing ID Number', 'posterno-elementor' ),
				'type'  => Controls_Manager::NUMBER,
				'step'  => 1,
			]
		);

		/**
		 * Filter: allow definition of additional layouts for the elementor listing card widget.
		 *
		 * @param array $layouts list of registered layouts.
		 * @return array
		 */
		$layout_options = apply_filters(
			'pno_elementor_listing_card_layouts',
			[
				'list' => esc_html__( 'List', 'posterno-elementor' ),
				'grid' => esc_html__( 'Grid', 'posterno-elementor' ),
			]
		);

		$this->add_control(
			'layout_mode',
			[
				'label'   => esc_html__( 'Layout style', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $layout_options,
				'default' => 'list',
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

		$listing_id = isset( $settings['listing_id'] ) && ! empty( $settings['listing_id'] ) ? absint( $settings['listing_id'] ) : false;

		if ( ! $listing_id ) {
			return;
		}

		if ( in_array( $settings['layout_mode'], [ 'grid', 'list' ], true ) ) {

			$query = new \WP_Query(
				[
					'p'         => $listing_id,
					'post_type' => 'listings',
				]
			);

			if ( $query->have_posts() ) {

				// Start opening the grid's container.
				if ( $settings['layout_mode'] === 'grid' ) {
					echo '<div class="card-deck">';
				}

				while ( $query->have_posts() ) :

					$query->the_post();

					posterno()->templates->get_template_part( 'listings/card', $settings['layout_mode'] );

				endwhile;

				// Close grid's container.
				if ( $settings['layout_mode'] === 'grid' ) {
					echo '</div>';
				}

			} else {

				posterno()->templates
					->set_template_data(
						[
							'type'    => 'info',
							'message' => esc_html__( 'No listing was found with that ID.' ),
						]
					)
					->get_template_part( 'message' );

			}


		} else {

		}

	}

}
