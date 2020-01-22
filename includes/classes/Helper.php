<?php
/**
 * Handles helper methods for the addon.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace Posterno\Elementor;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Helper methods collection.
 */
class Helper {

	/**
	 * Get the list of registered taxonomies for the control.
	 *
	 * @return array
	 */
	public static function get_registered_taxonomies() {

		$list = array();

		$taxonomies = get_object_taxonomies( 'listings', 'objects' );

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $id => $taxonomy ) {
				if ( in_array( $id, array( 'pno-review-attribute', 'pno-review-rating-label' ), true ) ) {
					continue;
				}
				$list[ $id ] = $taxonomy->label;
			}
		}

		return $list;

	}

	/**
	 * Determine and get the ID number of the custom layout assigned to a listing card.
	 *
	 * @param string|int $listing_id listing id number.
	 * @return bool|int
	 */
	public static function get_card_custom_layout( $listing_id ) {

		$has  = false;
		$type = pno_get_listing_type( $listing_id );

		if ( $type instanceof \WP_Term ) {
			$layout = pno_get_option( "listing_type_{$type->term_id}_card" );
			if ( $layout !== 'default' && ! empty( $layout ) ) {
				return absint( $layout );
			}
		}

		return $has;

	}

}
