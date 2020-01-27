<?php
/**
 * Listing custom field dynamic tag.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace Posterno\Elementor\Tags;

use Posterno\Elementor\Cache;
use Posterno\Elementor\Helper;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Get the expiry date of a listing.
 */
class ListingCustomField extends BaseDataTag {

	/**
	 * Name of the tag.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'posterno-listing-custom-field-tag';
	}

	/**
	 * Title of the tag.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Listing custom field', 'posterno-elementor' );
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

		$fields = wp_list_pluck( Cache::get_listings_custom_fields(), 'name' );

		unset( $fields['listing_social_media_profiles'] );

		$this->add_control(
			'custom_field',
			array(
				'label'   => esc_html__( 'Select custom field', 'posterno-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'options' => $fields,
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

		$meta_key   = $this->get_settings( 'custom_field' );
		$output     = false;
		$listing_id = get_the_id();
		$field_type = Helper::find_field_type( $meta_key );
		$options    = Helper::find_field_options( $meta_key );

		if ( $field_type && $meta_key ) {

			$output = get_post_meta( $listing_id, '_' . $meta_key, true );

			if ( $meta_key === 'listing_social_media_profiles' ) {
				$output = carbon_get_post_meta( $listing_id, 'listing_social_profiles' );
			} elseif ( $meta_key === 'listing_email_address' ) {
				$output = carbon_get_post_meta( $listing_id, 'listing_email' );
			}

			if ( ! $output ) {
				return;
			}

			$output = pno_display_field_value( $field_type, $output, array( 'options' => $options ), true );

		}

		return $output;

	}

}
