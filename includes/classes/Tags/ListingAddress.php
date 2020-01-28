<?php
/**
 * Address of a listing.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace Posterno\Elementor\Tags;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Get the address of a listing.
 */
class ListingAddress extends BaseDataTag {

	/**
	 * Name of the tag.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'posterno-listing-address-tag';
	}

	/**
	 * Title of the tag.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Address', 'posterno-elementor' );
	}

	/**
	 * Categories to which tags belong to.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
	}

	/**
	 * Register controls for the tag.
	 *
	 * @return void
	 */
	protected function _register_controls() {

		$this->add_control(
			'address_output',
			array(
				'label'   => esc_html__( 'Output control', 'posterno-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'address',
				'options' => array(
					'address'     => esc_html__( 'Address', 'posterno-elementor' ),
					'lat'         => esc_html__( 'Latitude', 'posterno-elementor' ),
					'lng'         => esc_html__( 'Longitude', 'posterno-elementor' ),
					'coordinates' => esc_html__( 'Combined coordinates', 'posterno-elementor' ),
				),
			)
		);
	}

	/**
	 * Dynamically overwrite the value retrieved for the tag.
	 *
	 * @param array $options options injected.
	 * @return array
	 */
	public function get_value( array $options = array() ) {

		$address = pno_get_listing_address( get_the_id() );
		$output  = $this->get_settings( 'address_output' );

		if ( $output === 'address' ) {

			return isset( $address['address'] ) ? esc_html( $address['address'] ) : false;

		} elseif ( $output === 'lat' ) {

			return isset( $address['lat'] ) ? esc_html( $address['lat'] ) : false;

		} elseif ( $output === 'lng' ) {

			return isset( $address['lng'] ) ? esc_html( $address['lng'] ) : false;

		} elseif ( $output === 'coordinates' ) {

			return isset( $address['value'] ) ? esc_html( $address['value'] ) : false;

		}

		return isset( $address['address'] ) ? esc_html( $address['address'] ) : false;

	}

}
