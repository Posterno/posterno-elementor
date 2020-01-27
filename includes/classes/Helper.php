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
	 * Helper functionality to get string between two characters.
	 *
	 * @param string $string full string.
	 * @param string $start the string delimeter.
	 * @param string $end the string delimeter.
	 * @return mixed
	 */
	public static function get_string_between( $string, $start, $end) {
		$string = ' ' . $string;
		$ini    = strpos( $string, $start );
		if ($ini == 0) {
			return '';
		}
		$ini += strlen( $start );
		$len  = strpos( $string, $end, $ini ) - $ini;
		return substr( $string, $ini, $len );
	}

	/**
	 * Get the last part of a parsed field name from a dynamic tag.
	 *
	 * @param string $field field's name like field/metakey/type.
	 * @return string
	 */
	public static function get_parsed_field_type( $field ) {
		return basename( $field );
	}

	/**
	 * Get the metakey from a parsed field name from a dynamic tag.
	 *
	 * @param string $field field's name like field/metakey/type.
	 * @return string
	 */
	public static function get_parsed_field_meta( $field ) {
		return self::get_string_between( $field, '/', '/' );
	}

}
