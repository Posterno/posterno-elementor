<?php
/**
 * Registers the claim button element.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Elements;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The listing claim button element.
 */
class ListingClaimButton extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'listing_claim_button';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Listing claim button', 'posterno-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-bullhorn';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'posterno' );
	}

	/**
	 * Register controls for the widget.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'settings',
			array(
				'label' => __( 'Settings', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'listing_id',
			array(
				'label'       => esc_html__( 'Specific listing ID', 'posterno-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'step'        => 1,
				'default'     => false,
				'description' => esc_html__( 'Enter the ID number of the listing for which you wish to display the button. Leave empty to automatically detect the current listing. Leaving it empty may not work in all situations.', 'posterno-elementor' ),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render output on the frontend.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		\Posterno\Claims\Plugin::instance()->templates
			->set_template_data(
				array(
					'listing_id' => isset( $settings['listing_id'] ) && ! empty( $settings['listing_id'] ) ? absint( $settings['listing_id'] ) : get_the_id(),
				)
			)
			->get_template_part( 'claim-button' );

	}

}
