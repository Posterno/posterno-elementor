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

	/**
	 * Get list of cards layouts cached.
	 *
	 * @return array
	 */
	public static function get_cards_layouts() {

		$list = remember_transient(
			'pno_elementor_cached_cards_layouts',
			function() {

				$args = array(
					'post_type'              => 'elementor_library',
					'posts_per_page'         => -1,
					'meta_key'               => '_elementor_location',
					'meta_value'             => 'listing-card',
					'no_found_rows'          => true,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
				);

				$query = new \WP_Query( $args );

				$data = array();

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						$data[ absint( get_the_id() ) ] = esc_html( get_the_title() );
					}
				}

				return $data;

			}
		);

		$default = array( 'default' => esc_html__( 'Default', 'posterno-elementor' ) );

		return $default + $list;

	}

	/**
	 * Purge the cache generated for cards layouts.
	 *
	 * @return void
	 */
	public static function purge_cards_cache() {
		forget_transient( 'pno_elementor_cached_cards_layouts' );
	}

}
