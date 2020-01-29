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

		$types = pno_get_listings_types_for_association();

		if ( ! empty( $types ) && is_array( $types ) ) {

			$settings['listings_settings'][] = Field::make( 'separator', 'cardsettings', esc_html__( 'Cards layout', 'posterno-elementor' ) );

			foreach ( $types as $type_id => $label ) {
				foreach ( pno_get_listings_layout_available_options() as $layout_id => $layout_label ) {

					$settings['listings_settings'][] = Field::make( 'select', "listing_type_{$type_id}_{$layout_id}_card", sprintf( __( 'Listing card layout for the "%1$s" type [%2$s]', 'posterno-elementor' ), esc_html( $label ), esc_html( $layout_label ) ) )
						->set_width( 33.33 )
						->set_options( Cache::get_cards_layouts() );

				}
			}
		}

		return $settings;

	}
);
