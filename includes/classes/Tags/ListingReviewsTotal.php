<?php
/**
 * Total number of submitted reviews for a listing.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace Posterno\Elementor\Tags;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Get the total number of submitted reviews for a listing.
 */
class ListingReviewsTotal extends BaseDataTag {

	/**
	 * Name of the tag.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'posterno-listing-reviews-total-tag';
	}

	/**
	 * Title of the tag.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Total number of reviews', 'posterno-elementor' );
	}

	/**
	 * Categories to which tags belong to.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array(
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
		);
	}

	/**
	 * Register controls for the tag.
	 *
	 * @return void
	 */
	protected function _register_controls() {

		$this->add_control(
			'fallback_text',
			array(
				'label' => esc_html__( 'Fallback text', 'posterno-elementor' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			)
		);
	}

	/**
	 * Dynamically overwrite the value retrieved for the tag.
	 *
	 * @param array $options options injected.
	 * @return array
	 */
	public function get_value( array $options = array() ) {

		$fallback = $this->get_settings( 'fallback_text' );
		$total    = absint( \Posterno\Reviews\Helper::get_total_reviews_for_listing( get_the_id() ) );

		if ( $total > 0 ) {
			return $total;
		} elseif ( $total <= 0 && $fallback ) {
			return wp_kses_post( $fallback );
		} else {
			return false;
		}

	}

}
