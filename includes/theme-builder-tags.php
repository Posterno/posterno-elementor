<?php
/**
 * Register custom dynamic tags for Elementor Pro.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico, LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use ElementorPro\Plugin;
use Posterno\Elementor\Tags\ListingClaimed;
use Posterno\Elementor\Tags\ListingFeatured;
use Posterno\Elementor\Tags\ListingFeaturedImage;
use Posterno\Elementor\Tags\ListingReviewsListUrl;
use Posterno\Elementor\Tags\ListingReviewsOverallRating;
use Posterno\Elementor\Tags\ListingReviewsTotal;
use Posterno\Elementor\Tags\LoginUrl;
use Posterno\Elementor\Tags\LogoutUrl;
use Posterno\Elementor\Tags\RegistrationUrl;

/**
 * Register a new group for listings dynamic tags.
 */
add_action(
	'elementor/dynamic_tags/register_tags',
	function() {

		$module = Plugin::elementor()->dynamic_tags;

		$module->register_group(
			'posterno_tags',
			array(
				'title' => esc_html__( 'Listings', 'posterno-elementor' ),
			)
		);

	}
);

/**
 * Register all custom tags.
 */
add_action(
	'elementor/dynamic_tags/register_tags',
	function( $dynamic_tags ) {

		$dynamic_tags->register_tag( new ListingFeaturedImage() );
		$dynamic_tags->register_tag( new ListingFeatured() );

		$dynamic_tags->register_tag( new LoginUrl() );
		$dynamic_tags->register_tag( new LogoutUrl() );
		$dynamic_tags->register_tag( new RegistrationUrl() );

		if ( class_exists( '\Posterno\Reviews\Plugin' ) ) {
			$dynamic_tags->register_tag( new ListingReviewsTotal() );
			$dynamic_tags->register_tag( new ListingReviewsListUrl() );
			$dynamic_tags->register_tag( new ListingReviewsOverallRating() );
		}

		if ( class_exists( '\Posterno\Claims\Plugin' ) ) {
			$dynamic_tags->register_tag( new ListingClaimed() );
		}

	}
);
