<?php
/**
 * Registers the single listing custom field element.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

namespace Posterno\Elementor\Elements\Single;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Posterno\Elementor\Cache;
use Posterno\Elementor\Helper;
use Posterno\Elementor\Plugin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The single listing custom field element.
 */
class ListingCustomField extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'single_listing_custom_field';
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
		return esc_html__( 'Listing custom field', 'posterno-elementor' );
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
		return 'fa fa-database';
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
		return array( 'posterno_single' );
	}

	/**
	 * Register controls for the widget.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'faceted_settings',
			array(
				'label' => __( 'Settings', 'posterno-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$fields = wp_list_pluck( Cache::get_listings_custom_fields(), 'name' );

		$this->add_control(
			'custom_field',
			array(
				'label'   => esc_html__( 'Select custom field', 'posterno-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'options' => $fields,
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

		$meta_key   = $this->get_settings( 'custom_field' );
		$output     = false;
		$listing_id = get_the_id();
		$field_type = Helper::find_field_type( $meta_key );
		$options    = Helper::find_field_options( $meta_key );

		if ( $field_type && $meta_key ) {

			$output = get_post_meta( $listing_id, '_' . $meta_key, true );

			if ( $meta_key === 'listing_social_media_profiles' ) {
				$output = carbon_get_post_meta( $listing_id, 'listing_social_profiles' );
			} elseif ( $meta_key === 'listing_email_address' ) {
				$output = carbon_get_post_meta( $listing_id, 'listing_email' );
			}

			if ( ! $output ) {
				return;
			}

			if ( $meta_key === 'listing_social_media_profiles' ) {

				posterno()->templates
					->set_template_data(
						[
							'networks' => $output,
						]
					)
					->get_template_part( 'fields-output/social-networks-field' );

			} else {

				pno_display_field_value( $field_type, $output, array( 'options' => $options ) );

			}

		}

	}

}
