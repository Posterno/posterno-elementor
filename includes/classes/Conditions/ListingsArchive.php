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
 * Listings archives taxonomy terms conditions definition.
 */
class ListingsArchive extends ThemeBuilder\Conditions\Condition_Base {

	/**
	 * The post type to which it belongs to.
	 *
	 * @var string
	 */
	private $post_type = 'listings';

	/**
	 * List of taxonomies.
	 *
	 * @var array
	 */
	private $post_taxonomies;

	/**
	 * Get things started.
	 *
	 * @param array $data I have no idea.
	 */
	public function __construct( array $data = array() ) {
		$taxonomies            = get_object_taxonomies( $this->post_type, 'objects' );
		$this->post_taxonomies = wp_filter_object_list(
			$taxonomies,
			array(
				'public'            => true,
				'show_in_nav_menus' => true,
			)
		);

		parent::__construct( $data );
	}

	/**
	 * Type of condition.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'archive';
	}

	/**
	 * Name of the conditions.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'listings_archive';
	}

	/**
	 * Order priority.
	 *
	 * @return string
	 */
	public static function get_priority() {
		return 40;
	}

	/**
	 * Label used in the UI.
	 *
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'Listings archive', 'posterno-elementor' );
	}

	/**
	 * Label used in the UI.
	 *
	 * @return string
	 */
	public function get_all_label() {
		return esc_html__( 'All listings archives', 'posterno-elementor' );
	}

	/**
	 * Retrieve the list of terms for all the registered taxonomies.
	 *
	 * @return void
	 */
	public function register_sub_conditions() {

		foreach ( $this->post_taxonomies as $slug => $object ) {
			$condition = new ThemeBuilder\Conditions\Taxonomy(
				array(
					'object' => $object,
				)
			);
			$this->register_sub_condition( $condition );
		}
	}

	/**
	 * Determine when to trigger the conditions.
	 *
	 * @param array $args list of data sent through.
	 * @return bool
	 */
	public function check( $args ) {
		return true;
	}

}
