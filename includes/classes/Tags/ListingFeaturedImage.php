<?php
/**
 * Listing featured image url tag.
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
 * Get the listing featured image dynamic url.
 * Retrieve the placeholder image url if available and no featured image has been set.
 */
class ListingFeaturedImage extends BaseDataTag {

	/**
	 * Name of the tag.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'posterno-listing-featured-image-tag';
	}

	/**
	 * Title of the tag.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Listing featured image', 'posterno-elementor' );
	}

	/**
	 * Group assigned to the tag.
	 *
	 * @return string
	 */
	public function get_group() {
		return 'posterno_tags';
	}

	/**
	 * Categories to which the tag belongs to.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY );
	}

	/**
	 * Dynamically overwrite the value retrieved for the tag.
	 *
	 * @param array $options options injected.
	 * @return array
	 */
	public function get_value( array $options = array() ) {

		$listing_id          = get_the_id();
		$featured_img        = get_the_post_thumbnail_url( $listing_id, false );
		$placeholder_enabled = pno_is_listing_placeholder_image_enabled();
		$featured_img_id     = get_post_thumbnail_id( $listing_id );

		if ( $featured_img ) {

			return array(
				'id'  => absint( $featured_img_id ),
				'url' => esc_url( $featured_img ),
			);

		} else {

			return array(
				'id'  => absint( $featured_img_id ),
				'url' => esc_url( pno_get_listing_placeholder_image() ),
			);

		}

	}

}
