<?php
/**
 * Registers the faceted amount modifier element for elementor.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Skins;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Custom skin for the archive posts element powered by Elementor Pro
 */
class PosternoSkin extends \ElementorPro\Modules\Posts\Skins\Skin_Base {

	protected function _register_controls_actions() {
		add_action( 'elementor/element/archive-posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
	}

	/**
	 * Skin ID
	 *
	 * @return string
	 */
	public function get_id() {
		return 'posterno-skin';
	}

	/**
	 * Skin title
	 *
	 * @return void
	 */
	public function get_title() {
		return esc_html__( 'Listing card', 'posterno-elementor' );
	}

	/**
	 * Register settings for the listing card
	 *
	 * @param Widget_Base $widget
	 * @return void
	 */
	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		/**
		 * Filter: allow definition of additional layouts for the elementor listing card widget.
		 *
		 * @param array $layouts list of registered layouts.
		 * @return array
		 */
		$layout_options = apply_filters(
			'pno_elementor_listing_card_layouts',
			array(
				'list' => esc_html__( 'List', 'posterno-elementor' ),
				'grid' => esc_html__( 'Grid', 'posterno-elementor' ),
			)
		);

		$this->add_control(
			'layout_mode',
			array(
				'label'   => esc_html__( 'Listing card style', 'posterno-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $layout_options,
				'default' => 'list',
			)
		);

		$this->add_control(
			'pagination',
			array(
				'label'    => esc_html__( 'Listings pagination', 'posterno-elementor' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => true,
				'options'  => array(
					'enabled'  => esc_html__( 'Enabled', 'posterno-elementor' ),
					'disabled' => esc_html__( 'Disabled', 'posterno-elementor' ),
				),
				'default'  => 'disabled',
			)
		);
	}

	/**
	 * Render listing card
	 *
	 * @return void
	 */
	protected function render_post() {

		$settings = $this->parent->get_settings();

		if ( isset( $settings[ 'posterno_skin_layout_mode' ] ) ) {

			posterno()->templates->get_template_part( 'listings/card', $settings[ 'posterno_skin_layout_mode' ] );

		} else {
			echo 'Something went wrong. Please select the layout you wish to use for the card.';
		}
	}

	/**
	 * Render query
	 *
	 * @return void
	 */
	public function render() {

		$this->parent->query_posts();

		$i = '';

		/** @var \WP_Query $query */
		$query = $this->parent->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		$settings = $this->parent->get_settings();

		?>
		<div class="pno-block-listings-wrapper posterno-template">
		<?php

			// Start opening the grid's container.
			if ( $settings[ 'posterno_skin_layout_mode' ] === 'grid' ) {
				echo '<div class="card-deck">';
			}

			if ( $query->in_the_loop ) {
				$this->current_permalink = get_permalink();

				$this->render_post();
				// Continue the loop of grids containers.
				if ( $settings[ 'posterno_skin_layout_mode' ] === 'grid' ) {
					$i++;
					if ( $i % 3 == 0 ) {
						echo '</div><div class="card-deck">';
					}
				}
			} else {
				while ( $query->have_posts() ) {
					$query->the_post();
					$this->current_permalink = get_permalink();
					$this->render_post();

					// Continue the loop of grids containers.
					if ( $settings[ 'posterno_skin_layout_mode' ] === 'grid' ) {
						$i++;
						if ( $i % 3 == 0 ) {
							echo '</div><div class="card-deck">';
						}
					}
				}
			}

			// Close grid's container.
			if ( $settings[ 'posterno_skin_layout_mode' ] === 'grid' ) {
				echo '</div>';
			}

			if ( $settings[ 'posterno_skin_pagination' ] === 'enabled' ) {
				posterno()->templates
					->set_template_data(
						array(
							'query' => $query,
						)
					)
					->get_template_part( 'listings/results', 'footer' );
			}

			wp_reset_postdata();
		?>
		</div>
		<?php
	}

}
