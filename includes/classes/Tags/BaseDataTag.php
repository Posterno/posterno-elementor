<?php
/**
 * Base data tag registration for the listings group.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace Posterno\Elementor\Tags;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use Elementor\Core\DynamicTags\Data_Tag;

/**
 * Base tags information for Posterno tags.
 */
abstract class BaseDataTag extends Data_Tag {

	/**
	 * Group to which tags belong to.
	 *
	 * @return string
	 */
	public function get_group() {
		return 'posterno_tags';
	}

}
