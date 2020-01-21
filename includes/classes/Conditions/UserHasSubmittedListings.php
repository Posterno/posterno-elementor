<?php
/**
 * Condition to verify that a user has submitted listings for the template builder in elementor pro.
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
 * Determine if a user has submitted listings.
 */
class UserHasSubmittedListings extends ThemeBuilder\Conditions\Condition_Base {

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
		return 'posterno_has_submitted_listings';
	}

	/**
	 * Condition priority.
	 *
	 * @return int
	 */
	public static function get_priority() {
		return 40;
	}

	/**
	 * Condition label for the editor.
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'User has submitted listings' );
	}

	/**
	 * Verify the condition.
	 *
	 * @param array $args arguments sent for verification.
	 * @return bool
	 */
	public function check( $args ) {
		return pno_user_has_submitted_listings( get_current_user_id() );
	}

}
