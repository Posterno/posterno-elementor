<?php
/**
 * The template for displaying the listings taxonomy terms powered by elementor.
 *
 * This template can be overridden by copying it to yourtheme/posterno/elementor/single-listing/map.php
 *
 * HOWEVER, on occasion PNO will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.0.0
 * @package posterno-elementor
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$listing_id  = get_queried_object_id();
$address_lat = get_post_meta( $listing_id, '_listing_location_lat', true );
$address_lng = get_post_meta( $listing_id, '_listing_location_lng', true );

?>

<?php if ( pno_get_option( 'map_gdpr', false ) && ! pno_map_was_given_consent() ) : ?>

	<?php posterno()->templates->get_template_part( 'maps/consent-message' ); ?>

<?php else : ?>

	<?php if ( $address_lat && $address_lng ) : ?>
		<div class="pno-single-listing-map" data-lat="<?php echo esc_attr( $address_lat ); ?>" data-lng="<?php echo esc_attr( $address_lng ); ?>" data-zoom="12"></div>
	<?php endif; ?>

<?php endif; ?>
