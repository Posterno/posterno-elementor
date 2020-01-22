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
