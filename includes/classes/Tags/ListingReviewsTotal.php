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
			'format_no_reviews',
			array(
				'label'   => __( 'No reviews format', 'posterno-elementor' ),
				'default' => __( 'No reviews', 'posterno-elementor' ),
			)
		);

		$this->add_control(
			'format_one_review',
			array(
				'label'   => __( 'One review format', 'posterno-elementor' ),
				'default' => __( 'One review', 'posterno-elementor' ),
			)
		);

		$this->add_control(
			'format_many_reviews',
			array(
				'label'   => __( 'Many reviews format', 'posterno-elementor' ),
				'default' => __( '{number} reviews', 'posterno-elementor' ),
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'   => __( 'Link', 'posterno-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''              => __( 'None', 'posterno-elementor' ),
					'comments_link' => __( 'Reviews Link', 'posterno-elementor' ),
				),
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

		$total = absint( \Posterno\Reviews\Helper::get_total_reviews_for_listing( get_the_id() ) );

		$no_reviews   = $this->get_settings( 'format_no_reviews' );
		$one_review   = $this->get_settings( 'format_one_review' );
		$many_reviews = $this->get_settings( 'format_many_reviews' );
		$link         = $this->get_settings( 'link_to' );
		$count        = false;

		if ( ! $total || $total === 0 ) {
			$count = $no_reviews;
		} elseif ( $total === 1 ) {
			$count = $one_review;
		} elseif ( $total > 1 ) {

			$count = strtr(
				$many_reviews,
				array(
					'{number}' => number_format_i18n( $total ),
				)
			);

		}

		if ( $link === 'comments_link' ) {
			$count = sprintf( '<a href="%s">%s</a>', esc_url( trailingslashit( get_permalink( get_the_id() ) ) . '#reviews' ), $count );
		}

		return $count;

	}

}
