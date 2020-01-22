<?php
/**
 * Elementor pro specific actions.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

use Posterno\Elementor\Cache;
use Posterno\Elementor\Helper;

/**
 * Display the custom theme builder sections for the dashboard pages.
 */
$dashboard_sections = wp_list_pluck( pno_get_dashboard_navigation_items(), 'name' );

foreach ( $dashboard_sections as $page_key => $page_name ) {
	if ( $page_key === 'logout' ) {
		continue;
	}

	add_action(
		"pno_dashboard_tab_content_{$page_key}",
		function() use( $page_key ) {
			if ( elementor_theme_do_location( "dashboard-before-{$page_key}" ) ) {
				elementor_theme_do_location( "dashboard-before-{$page_key}" );
			}
		},
		9
	);

	add_action(
		"pno_dashboard_tab_content_{$page_key}",
		function() use( $page_key ) {
			if ( elementor_theme_do_location( "dashboard-after-{$page_key}" ) ) {
				elementor_theme_do_location( "dashboard-after-{$page_key}" );
			}
		},
		11
	);

}

/**
 * Automatically purge cache of cards templates list when creating a new card.
 */
add_action(
	'save_post',
	function( $post_id, $post, $update ) {

		if ( $update ) {
			return;
		}

		if ( 'elementor_library' !== $post->post_type ) {
			return;
		}

		Cache::purge_cards_cache();

	},
	10,
	3
);

/**
 * Determine whether or not default cards layout should not output when
 * a custom card layout has been added through Elementor Pro.
 */
add_filter(
	'pno_bypass_card_layout',
	function( $bypass, $layout ) {

		$listing_id = get_the_id();

		if ( $layout === 'list' && Helper::get_card_custom_layout( $listing_id ) ) {
			return true;
		}

		return $bypass;

	},
	10,
	2
);

/**
 * Output custom card layout when assigned through Elementor Pro.
 */
add_action(
	'pno_before_listing_in_loop',
	function() {

		$active_layout = pno_get_listings_results_active_layout();
		$listing_id    = get_the_id();

		if ( $active_layout === 'list' && Helper::get_card_custom_layout( $listing_id ) ) {
			echo do_shortcode( '[elementor-template id="' . absint( Helper::get_card_custom_layout( $listing_id ) ) . '"]' );
		}

	}
);
