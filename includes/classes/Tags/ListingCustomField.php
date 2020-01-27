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

		$this->add_control(
			'custom_field',
			array(
				'label'   => esc_html__( 'Select custom field', 'posterno-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'options' => Cache::get_listings_custom_fields(),
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

		$field  = $this->get_settings( 'custom_field' );
		$output = false;

		$field_type = Helper::get_parsed_field_type( $field );
		$metakey    = Helper::get_parsed_field_meta( $field );

		return $output;

	}

}
