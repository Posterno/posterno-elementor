<?php
/**
 * Registers custom conditions for the template builder in elementor pro.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Conditions;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use \ElementorPro\Modules\ThemeBuilder as ThemeBuilder;

/**
 * Registers listings related conditions.
 */
class Listings extends ThemeBuilder\Conditions\Condition_Base {

	/**
	 * Condition type.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'posterno';
	}

	/**
	 * Condition name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'posterno';
	}

	/**
	 * Condition label.
	 *
	 * @return int
	 */
	public function get_label() {
		return esc_html__( 'Listings' );
	}

	/**
	 * Condition "all" label.
	 *
	 * @return bool
	 */
	public function get_all_label() {
		return false;
	}

	/**
	 * Register sub conditions for the main module.
	 *
	 * @return void
	 */
	public function register_sub_conditions() {

		$this->register_sub_condition( new UserHasSubmittedListings() );

	}

	/**
	 * Trigger verification for main module.
	 *
	 * @param array $args sent for verification.
	 * @return bool
	 */
	public function check( $args ) {
		return true;
	}

}
