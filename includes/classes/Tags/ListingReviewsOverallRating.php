<?php
/**
 * Overall rating number of a listing.
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
 * Get the overall rating of a listing.
 */
class ListingReviewsOverallRating extends BaseDataTag {

	/**
	 * Name of the tag.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'posterno-listing-reviews-overall-rating-tag';
	}

	/**
	 * Title of the tag.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Overall rating number', 'posterno-elementor' );
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
			'format_no_comments',
			[
				'label' => __( 'No Comments Format', 'posterno-elementor' ),
				'default' => __( 'No Responses', 'posterno-elementor' ),
			]
		);

		$this->add_control(
			'format_one_comments',
			[
				'label' => __( 'One Comment Format', 'posterno-elementor' ),
				'default' => __( 'One Response', 'posterno-elementor' ),
			]
		);

		$this->add_control(
			'format_many_comments',
			[
				'label' => __( 'Many Comment Format', 'posterno-elementor' ),
				'default' => __( '{number} Responses', 'posterno-elementor' ),
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => __( 'Link', 'posterno-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'posterno-elementor' ),
					'comments_link' => __( 'Comments Link', 'posterno-elementor' ),
				],
			]
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
		$total    = absint( \Posterno\Reviews\Rating::get_for_listing( get_the_id() ) );

		if ( $total > 0 ) {
			return $total;
		} elseif ( $total <= 0 && $fallback ) {
			return wp_kses_post( $fallback );
		} else {
			return false;
		}

	}

}
