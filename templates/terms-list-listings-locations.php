<?php
/**
 * The template for displaying the content of the listings categories list shortcode.
 *
 * This template can be overridden by copying it to yourtheme/posterno/shortcodes/listings-locations.php
 *
 * HOWEVER, on occasion PNO will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.0.0
 * @package posterno
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$terms = get_terms(
	array(
		'taxonomy'   => 'listings-locations',
		'hide_empty' => isset( $data->hide_empty ) && $data->hide_empty === 'yes' ? true : false,
		'parent'     => 0,
		'number'     => isset( $data->number ) && ! empty( $data->number ) ? absint( $data->number ) : 999,
		'orderby'    => isset( $data->orderby ) && ! empty( $data->orderby ) ? esc_attr( $data->orderby ) : 'name',
		'order'      => isset( $data->order ) && ! empty( $data->order ) ? esc_attr( $data->order ) : 'ASC',
	)
);

$i = 0;

if ( ! is_array( $terms ) || empty( $terms ) ) {
	return;
}

$show_sublocations = isset( $data->show_subcategories ) && $data->show_subcategories === 'yes' ? true : false;

?>

<div class="pno-listings-terms-list">

	<div class="row">

	<?php

	foreach ( $terms as $listing_location ) :

		$image = carbon_get_term_meta( $listing_location->term_id, 'term_image' );

		?>

		<div class="col-md-4">

			<?php

			if ( $image ) {

				posterno()->templates
					->set_template_data(
						array(
							'listing_location'  => $listing_location,
							'show_sublocations' => $show_sublocations,
							'image'             => $image,
						)
					)
					->get_template_part( 'shortcodes/locations/image-list' );

			} else {

				posterno()->templates
					->set_template_data(
						array(
							'listing_location'  => $listing_location,
							'show_sublocations' => $show_sublocations,
						)
					)
					->get_template_part( 'shortcodes/locations/simple-list' );

			}

			?>

		</div>

	<?php endforeach; ?>

	</div>

</div>

