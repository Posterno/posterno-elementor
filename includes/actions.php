<?php
/**
 * Actions for the addon.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

use Posterno\Elementor\Elements\ListingsQuery;

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
			[
				'title' => esc_html__( 'Listings', 'posterno-elementor' ),
				'icon'  => 'fa fa-plug',
			]
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

	}
);
