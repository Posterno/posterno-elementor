<?php
/**
 * Register new settings for the options panel.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

use Carbon_Fields\Field;
use Posterno\Elementor\Cache;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register settings for the addon.
 */
add_filter(
	'pno_options_panel_settings',
	function( $settings ) {

		$settings['listings_settings'][] = Field::make( 'separator', 'cardsettings', esc_html__( 'Cards layout' ) );

		$types = pno_get_listings_types_for_association();

		if ( ! empty( $types ) && is_array( $types ) ) {
			foreach ( $types as $type_id => $label ) {
				$settings['listings_settings'][] = Field::make( 'select', "listing_type_{$type_id}_card", sprintf( __( 'Listing card layout for the "%s" type' ), esc_html( $label ) ) )
					->set_options( Cache::get_cards_layouts() );
			}
		}

		return $settings;

	}
);
