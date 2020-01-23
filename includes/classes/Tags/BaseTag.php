<?php
/**
 * Base tag registration for the listings group.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace Posterno\Elementor\Tags;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use Elementor\Core\DynamicTags\Tag;

/**
 * Definition of new tags.
 */
abstract class BaseTag extends Tag {

	/**
	 * Group to which tags belong to.
	 *
	 * @return string
	 */
	public function get_group() {
		return 'posterno_tags';
	}

	/**
	 * Categories to which tags belong to.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
	}

}
