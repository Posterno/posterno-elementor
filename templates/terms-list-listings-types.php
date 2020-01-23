<?php
/**
 * The template for displaying the listings taxonomy terms powered by elementor.
 *
 * This template can be overridden by copying it to yourtheme/posterno/elementor/terms-list-listings-types.php
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

$terms = get_terms(
	array(
		'taxonomy'   => 'listings-types',
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

?>

<div class="row pno-listing-types-list">

	<?php

	foreach ( $terms as $listing_type ) :

		$icon = carbon_get_term_meta( $listing_type->term_id, 'term_icon' );

		?>

		<div class="col-sm-4">
			<div class="card">
				<div class="card-body text-center">
					<?php if ( $icon ) : ?>
						<div class="term-icon rounded-circle">
							<i class="<?php echo esc_attr( $icon ); ?>"></i>
						</div>
					<?php endif; ?>

					<h5 class="card-title mt-3 mb-3"><?php echo esc_html( $listing_type->name ); ?></h5>

					<?php if ( ! empty( $listing_type->description ) ) : ?>
						<p class="card-text"><?php echo wp_kses_post( $listing_type->description ); ?></p>
					<?php endif; ?>
					<a href="<?php echo esc_url( get_term_link( $listing_type ) ); ?>" class="btn btn-secondary btn-sm"><?php esc_html_e( 'Browse listings', 'posterno-elementor' ); ?></a>
				</div>
			</div>
		</div>

	<?php endforeach; ?>

</div>
