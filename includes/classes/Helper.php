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
	 * @param string     $layout the currently active layout to check for.
	 * @return bool|int
	 */
	public static function get_card_custom_layout( $listing_id, $layout ) {

		$has  = false;
		$type = pno_get_listing_type( $listing_id );

		if ( $type instanceof \WP_Term ) {
			$layout = pno_get_option( "listing_type_{$type->term_id}_{$layout}_card" );
			if ( $layout !== 'default' && ! empty( $layout ) ) {
				return absint( $layout );
			}
		}

		return $has;

	}

	/**
	 * Returns true when viewing a listing taxonomy archive.
	 *
	 * @return boolean
	 */
	public static function is_listings_taxonomy() {
		return is_tax( get_object_taxonomies( 'listings' ) );
	}

	/**
	 * Returns true when viewing the listings post type archive page.
	 *
	 * @return boolean
	 */
	public static function is_listings_archive() {
		return ( is_post_type_archive( 'listings' ) );
	}

	/**
	 * Retrieve the type of field from the cached list based on it's key.
	 *
	 * @param string $meta_key key.
	 * @return mixed
	 */
	public static function find_field_type( $meta_key ) {

		$fields = Cache::get_listings_custom_fields();

		return isset( $fields[ $meta_key ]['type'] ) && ! empty( $fields[ $meta_key ]['type'] ) ? $fields[ $meta_key ]['type'] : false;

	}

	/**
	 * Retrieve the options of field from the cached list based on it's key.
	 *
	 * @param string $meta_key key.
	 * @return mixed
	 */
	public static function find_field_options( $meta_key ) {

		$fields = Cache::get_listings_custom_fields();

		return isset( $fields[ $meta_key ]['options'] ) && ! empty( $fields[ $meta_key ]['options'] ) ? $fields[ $meta_key ]['options'] : false;

	}

}
