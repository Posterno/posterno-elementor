<?php
/**
 * Handles the visibility controls and settings for elements.
 *
 * @package     posterno-elementor
 * @copyright   Copyright (c) 2020, Sematico LTD
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace Posterno\Elementor;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Adds controls to Elementor widgets to determine their visibility.
 */
class Visibility {

	/**
	 * Class instance.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Get the class instance
	 *
	 * @return static
	 */
	public static function get_instance() {
		return null === self::$instance ? ( self::$instance = new self() ) : self::$instance;
	}

	/**
	 * Get things started.
	 */
	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'register_section' ) );
		add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'register_section' ) );
		add_action( 'elementor/element/common/posterno_visibility_section/before_section_end', array( $this, 'register_controls' ), 10, 2 );
		add_action( 'elementor/element/section/posterno_visibility_section/before_section_end', array( $this, 'register_controls' ), 10, 2 );

		add_filter( 'elementor/widget/render_content', array( $this, 'content_change' ), 999, 2 );
		add_filter( 'elementor/section/render_content', array( $this, 'content_change' ), 999, 2 );

		add_filter( 'elementor/frontend/section/should_render', array( $this, 'section_should_render' ), 10, 2 );

	}

	/**
	 * Register new settings section for elementor widgets.
	 *
	 * @param object $manager elementor manager.
	 * @return void
	 */
	public function register_section( $manager ) {

		$manager->start_controls_section(
			'posterno_visibility_section',
			array(
				'tab'   => Controls_Manager::TAB_ADVANCED,
				'label' => esc_html__( 'Visibility control', 'posterno-elementor' ),
			)
		);

		$manager->end_controls_section();

	}

	public function register_controls( $element, $args ) {

		$element->add_control(
			'posterno_visibility_enabled',
			array(
				'label'        => esc_html__( 'Enable visibility conditions', 'posterno-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'posterno-elementor' ),
				'label_off'    => esc_html__( 'No', 'posterno-elementor' ),
				'return_value' => 'yes',
			)
		);

		$element->add_control(
			'posterno_visibility_logic',
			array(
				'type'        => Controls_Manager::SELECT2,
				'label'       => esc_html__( 'Visible for:', 'posterno-elementor' ),
				'options'     => $this->get_visibility_options(),
				'default'     => array(),
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array(
					'posterno_visibility_enabled' => 'yes',
				),
			)
		);

		$element->add_control(
			'posterno_visibility_logic_listing_id',
			array(
				'type'        => Controls_Manager::NUMBER,
				'dynamic'     => array(
					'active' => true,
				),
				'label'       => esc_html__( 'Listing ID:', 'posterno-elementor' ),
				'description' => esc_html__( 'Some visibility conditions are reserved for listings. Here you can specific to which listing it should apply. Select "Post ID" from the the dynamic menu to automatically retrieve the ID number of the current listing.', 'posterno-elementor' ),
			)
		);

		$element->add_control(
			'posterno_visibility_logic_listing_type',
			array(
				'type'        => Controls_Manager::SELECT2,
				'label'       => esc_html__( 'Listing types', 'posterno-elementor' ),
				'options'     => pno_get_listings_types_for_association(),
				'default'     => array(),
				'multiple'    => true,
				'label_block' => true,
			)
		);

	}

	/**
	 * Get the list of visibility options.
	 *
	 * @return array
	 */
	private function get_visibility_options() {

		$options = array(
			'user'             => esc_html__( 'User is logged in', 'posterno-elementor' ),
			'guest'            => esc_html__( 'User is logged out', 'posterno-elementor' ),
			'listing_author'   => esc_html__( 'User has submitted listings', 'posterno-elementor' ),
			'listing_owner'    => esc_html__( 'User is owner of listing', 'posterno-elementor' ),
			'listing_featured' => esc_html__( 'Listing is featured', 'posterno-elementor' ),
			'listing_expired'  => esc_html__( 'Listing is expired', 'posterno-elementor' ),
			'listing_is_type'  => esc_html__( 'Listing is of type', 'posterno-elementor' ),
			'listing_featured_image' => esc_html__( 'Listing has featured image' ),
		);

		if ( class_exists( '\Posterno\Claims\Plugin' ) ) {
			$options['listing_is_claimed'] = esc_html__( 'Listing is claimed', 'posterno-elementor' );
		}

		if ( class_exists( '\Posterno\Favourites\Plugin' ) ) {
			$options['listing_is_fav'] = esc_html__( 'User has bookmarked listing', 'posterno-elementor' );
		}

		if ( class_exists( '\Posterno\Reviews\Plugin' ) ) {
			$options['user_reviewed_listing'] = esc_html__( 'User has reviewed listing', 'posterno-elementor' );
			$options['listing_has_reviews'] = esc_html__( 'Listing has reviews', 'posterno-elementor' );
		}

		return apply_filters( 'pno_elementor_visibility_options', $options );

	}

	/**
	 * Hide content based on selected conditions.
	 *
	 * @param string $content the content to output.
	 * @param object $widget elementor widget instance.
	 * @return string
	 */
	public function content_change( $content, $widget ) {

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return $content;
		}

		$settings = $widget->get_settings();

		if ( ! $this->should_render( $settings ) ) {
			return '';
		}

		return $content;

	}

	/**
	 * Detect whether or not a section should render.
	 *
	 * @param bool   $should_render whether or not the section should render.
	 * @param object $section elementor widget instance.
	 * @return bool
	 */
	public function section_should_render( $should_render, $section ) {

		$settings = $section->get_settings();

		if ( ! $this->should_render( $settings ) ) {
			return false;
		}

		return $should_render;

	}

	/**
	 * Determine visibility conditions specified for widgets and sections.
	 *
	 * @param array $settings settings list.
	 * @return boolean
	 */
	private function should_render( $settings ) {

		if ( $settings['posterno_visibility_enabled'] == 'yes' ) {

			if ( ! empty( $settings['posterno_visibility_logic'] ) ) {

				$visibility_methods = $settings['posterno_visibility_logic'];

				return $this->get_processed_visibility( $visibility_methods, $settings );

			}

		}

		return true;

	}

	/**
	 * Do the visibility logic based on the settings.
	 *
	 * @param array $settings visibility settings list.
	 * @param array $all_settings any other setting that has been sent through.
	 * @return bool
	 */
	private function get_processed_visibility( $settings, $all_settings ) {

		$is_logged_in = is_user_logged_in();
		$is_visible   = true;
		$listing_id   = isset( $all_settings['posterno_visibility_logic_listing_id'] ) && ! empty( $all_settings['posterno_visibility_logic_listing_id'] ) ? absint( $all_settings['posterno_visibility_logic_listing_id'] ) : false;

		if ( in_array( 'user', $settings, true ) && ! $is_logged_in ) {
			$is_visible = false;
		}

		if ( in_array( 'guest', $settings, true ) ) {
			if ( $is_logged_in ) {
				$is_visible = false;
			} else {
				$is_visible = true;
			}
		}

		if ( in_array( 'listing_featured', $settings, true ) ) {
			if ( pno_listing_is_featured( $listing_id ) ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}
		}

		if ( in_array( 'listing_author', $settings, true ) ) {
			if ( pno_user_has_submitted_listings( get_current_user_id() ) ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}
		}

		if ( in_array( 'listing_owner', $settings, true ) ) {
			if ( pno_is_user_owner_of_listing( get_current_user_id(), $listing_id ) ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}
		}

		if ( in_array( 'listing_expired', $settings, true ) ) {
			if ( pno_is_listing_expired( $listing_id ) ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}
		}

		if ( in_array( 'listing_is_type', $settings, true ) ) {

			$current_type = pno_get_listing_type( $listing_id );

			if ( ! isset( $current_type->term_id ) ) {
				return true;
			}

			$selected_types = isset( $all_settings['posterno_visibility_logic_listing_type'] ) && ! empty( $all_settings['posterno_visibility_logic_listing_type'] ) ? array_map( 'absint', $all_settings['posterno_visibility_logic_listing_type'] ) : array();

			if ( in_array( $current_type->term_id, $selected_types, true ) ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}

		}

		if ( class_exists( '\Posterno\Claims\Plugin' ) && in_array( 'listing_is_claimed', $settings, true ) ) {

			if ( pno_listing_is_claimed( $listing_id ) ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}

		}

		if ( class_exists( '\Posterno\Favourites\Plugin' ) && in_array( 'listing_is_fav', $settings, true ) ) {

			if ( $is_logged_in && \Posterno\Favourites\User::bookmarked_listing( $listing_id, get_current_user_id() ) ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}

		}

		if ( class_exists( '\Posterno\Reviews\Plugin' ) && in_array( 'user_reviewed_listing', $settings, true ) ) {

			if ( $is_logged_in && \Posterno\Reviews\User::has_reviewed_listing( get_current_user_id(), $listing_id ) ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}

		}

		if ( class_exists( '\Posterno\Reviews\Plugin' ) && in_array( 'listing_has_reviews', $settings, true ) ) {

			if ( absint( \Posterno\Reviews\Helper::get_total_reviews_for_listing( $listing_id ) ) > 0 ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}

		}

		if ( in_array( 'listing_featured_image', $settings, true ) ) {
			if ( has_post_thumbnail( $listing_id ) ) {
				$is_visible = true;
			} else {
				$is_visible = false;
			}
		}

		/**
		 * Filter: allow developers to add custom visibility logic functionality.
		 *
		 * @param bool $is_visible if the widget/section is visible or not.
		 * @param array $settings the visibility settings selected by the user.
		 * @return bool
		 */
		return apply_filters( 'pno_elementor_visibility_logic', $is_visible, $settings );

	}

}
