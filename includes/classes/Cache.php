<?php
/**
 * Handles cache related functionalities for the addon.
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
 * Handles cache related functionalities for the addon.
 */
class Cache {

	/**
	 * Get the list of terms cached.
	 *
	 * @param string $taxonomy the taxonomy to retrieve.
	 * @return array
	 */
	public static function get_cached_terms( $taxonomy ) {

		$types = array();

		if ( $taxonomy === 'listings-types' ) {
			return pno_get_listings_types_for_association();
		}

		$terms = remember_transient(
			'pno_elementor_cached_terms_' . $taxonomy,
			function() use ( $taxonomy ) {
				return get_terms(
					$taxonomy,
					array(
						'hide_empty' => false,
						'number'     => 999,
						'orderby'    => 'name',
						'order'      => 'ASC',
					)
				);
			}
		);

		if ( ! empty( $terms ) && is_array( $terms ) ) {
			foreach ( $terms as $listing_type ) {
				$types[ absint( $listing_type->term_id ) ] = esc_html( $listing_type->name );
			}
		}

		return $types;

	}

	/**
	 * Purge the list of cached terms for a specific taxonomy.
	 *
	 * @param string $taxonomy the taxonomy to purge.
	 * @return void
	 */
	public static function purge_taxonomy_cache( $taxonomy ) {
		forget_transient( 'pno_elementor_cached_terms_' . $taxonomy );
	}

}
