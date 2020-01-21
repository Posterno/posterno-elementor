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

}
