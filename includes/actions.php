<?php
/**
 * Actions for the addon.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

use Posterno\Elementor\Cache;
use Posterno\Elementor\Elements\ListingAddToFavouritesButton;
use Posterno\Elementor\Elements\ListingCard;
use Posterno\Elementor\Elements\ListingClaimButton;
use Posterno\Elementor\Elements\ListingsQuery;
use Posterno\Elementor\Elements\SocialLoginButtons;
use Posterno\Elementor\Elements\TermsList;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register a new widgets category for our elements.
 */
add_action(
	'elementor/elements/categories_registered',
	function( $elements_manager ) {

		$elements_manager->add_category(
			'posterno',
			array(
				'title' => esc_html__( 'Listings', 'posterno-elementor' ),
				'icon'  => 'fa fa-plug',
			)
		);

		$elements_manager->add_category(
			'posterno_single',
			array(
				'title' => esc_html__( 'Single listing', 'posterno-elementor' ),
				'icon'  => 'fa fa-plug',
			)
		);

	}
);

/**
 * Register the elementor widgets.
 */
add_action(
	'elementor/widgets/widgets_registered',
	function() {

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ListingsQuery() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new TermsList() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ListingCard() );

		if ( class_exists( '\Posterno\Search\Plugin' ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Posterno\Elementor\Elements\SearchFacet() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Posterno\Elementor\Elements\FacetPagination() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Posterno\Elementor\Elements\FacetAmount() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Posterno\Elementor\Elements\FacetSorter() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Posterno\Elementor\Elements\FacetResultsNumber() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Posterno\Elementor\Elements\FacetSearchSubmit() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Posterno\Elementor\Elements\FacetFakeQuery() );
		}

		// Register elementor pro only widgets.
		if ( class_exists( '\ElementorPro\Plugin' ) ) {

			if ( class_exists( '\Posterno\Reviews\Plugin' ) ) {
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Posterno\Elementor\Elements\RatingStars() );
			}

		}

		if ( class_exists( '\Posterno\SocialLogin\Plugin' ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new SocialLoginButtons() );
		}

		if ( class_exists( '\Posterno\Claims\Plugin' ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ListingClaimButton() );
		}

		if ( class_exists( '\Posterno\Favourites\Plugin' ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ListingAddToFavouritesButton() );
		}

	}
);

/**
 * Purge the terms cache when updating, creating or deleting terms assigned to the listings post type.
 *
 * @param string $term_id id of the term.
 * @param string $tt_id id of the term.
 * @param string $taxonomy taxonomy of the term.
 * @return void
 */
function pno_elementor_purge_terms_cache( $term_id, $tt_id, $taxonomy ) {
	if ( array_key_exists( $taxonomy, get_object_taxonomies( 'listings', 'objects' ) ) ) {
		Cache::purge_taxonomy_cache( $taxonomy );
	}
}
add_action( 'edited_term', 'pno_elementor_purge_terms_cache', 10, 3 );
add_action( 'create_term', 'pno_elementor_purge_terms_cache', 10, 3 );
add_action( 'delete_term', 'pno_elementor_purge_terms_cache', 10, 3 );

/**
 * Register custom conditions for the elementor pro theme builder.
 *
 * @param object $manager builder manager.
 * @return void
 */
function pno_elementor_register_builder_conditions( $manager ) {

	$manager->get_condition( 'general' )->register_sub_condition( new \Posterno\Elementor\Conditions\Listings() );

}
add_action( 'elementor/theme/register_conditions', 'pno_elementor_register_builder_conditions' );
