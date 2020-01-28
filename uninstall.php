<?php
/**
 * Uninstall addon
 *
 * @package     posterno-reviews
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Load the plugin back.
require_once 'posterno.php';
require_once dirname( __FILE__ ) . '/includes/admin/admin-options.php';

$types = pno_get_listings_types_for_association();

$cards_settings = [];

if ( ! empty( $types ) && is_array( $types ) ) {
	foreach ( $types as $type_id => $label ) {
		foreach ( pno_get_listings_layout_available_options() as $layout_id => $layout_label ) {
			$cards_settings[] = "listing_type_{$type_id}_{$layout_id}_card";
		}
	}
}

foreach ( $cards_settings as $option ) {
	pno_delete_option( $option );
}

delete_option( 'pno_elementor_version' );
delete_option( 'pno_elementor_version_upgraded_from' );

forget_transient( 'pno_elementor_cached_cards_layouts' );
forget_transient( 'pno_elementor_listings_fields' );
