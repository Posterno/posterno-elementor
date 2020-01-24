<?php
/**
 * Claimed listing status text tag.
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
 * Displays the label used for claimed listings.
 */
class ListingClaimed extends BaseTag {

	/**
	 * Name of the tag.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'posterno-listing-claimed-tag';
	}

	/**
	 * Title of the tag.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Claimed status', 'posterno-elementor' );
	}

	/**
	 * Register controls for the tag.
	 *
	 * @return void
	 */
	protected function _register_controls() {
		$this->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Text', 'posterno-elementor' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Verified', 'posterno-elementor' ),
			)
		);
	}

	/**
	 * Dynamically overwrite the value retrieved for the tag.
	 *
	 * @return void
	 */
	public function render() {

		$value = '';

		if ( pno_listing_is_claimed( get_the_id() ) ) {
			$value = $this->get_settings( 'text' );
		}

		echo wp_kses_post( $value );

	}

}
