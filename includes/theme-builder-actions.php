<?php
/**
 * Elementor pro specific actions.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

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
