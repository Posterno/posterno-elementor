<?php
/**
 * Registration page url.
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
 * Get the registration page url powered by posterno.
 */
class RegistrationUrl extends BaseDataTag {

	/**
	 * Name of the tag.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'posterno-registration-url';
	}

	/**
	 * Title of the tag.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Registration URL', 'posterno-elementor' );
	}

	/**
	 * Categories to which tags belong to.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::URL_CATEGORY );
	}

	/**
	 * Register controls for the tag.
	 *
	 * @return void
	 */
	protected function _register_controls() {

		$this->add_control(
			'redirect',
			array(
				'label'       => esc_html__( 'Redirect url', 'posterno-elementor' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'description' => esc_html__( 'Leave blank to use the default settings set in Posterno.', 'posterno-elementor' ),
				'dynamic'     => array(
					'active' => true,
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

		$registration = get_permalink( pno_get_registration_page_id() );
		$redirect     = $this->get_settings( 'redirect' );

		if ( isset( $redirect['url'] ) && ! empty( $redirect['url'] ) ) {
			$registration = add_query_arg( array( 'redirect_to' => esc_url( $redirect['url'] ) ), $registration );
		}

		return esc_url( $registration );

	}

}
