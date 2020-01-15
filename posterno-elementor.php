<?php
/**
 * Plugin Name:     Posterno Elementor
 * Plugin URI:      https://posterno.com/extensions/elementor
 * Description:     Visually build your listings directory website with Posterno and Elementor.
 * Author:          Posterno
 * Author URI:      https://posterno.com
 * Text Domain:     posterno-elementor
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * Posterno Elementor is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Posterno Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PosternoElementor. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package posterno-elementor
 * @author Sematico LTD
 */

namespace Posterno\Elementor;

defined( 'ABSPATH' ) || exit;

if ( ! did_action( 'elementor/loaded' ) ) {
	return;
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * Activate the plugin only when requirements are met.
 */
add_action(
	'plugins_loaded',
	function() {

		$requirements_check = new \PosternoRequirements\Check(
			array(
				'title' => 'Posterno Elementor',
				'file'  => __FILE__,
				'pno'   => '1.2.4',
			)
		);

		if ( $requirements_check->passes() ) {

			$addon = Plugin::instance( __FILE__ );
			add_action( 'plugins_loaded', array( $addon, 'textdomain' ), 11 );

		}
		unset( $requirements_check );

	}
);
