<?php
/**
 * The template for displaying the listings taxonomy terms powered by elementor.
 *
 * This template can be overridden by copying it to yourtheme/posterno/elementor/terms-list-default.php
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
		'taxonomy'   => isset( $data->taxonomy_id ) && ! empty( $data->taxonomy_id ) ? esc_attr( $data->taxonomy_id ) : false,
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

<div class="pno-listings-terms-list">

	<?php foreach ( $terms as $term ) : ?>

		<a href="<?php echo esc_url( get_term_link( $listing_type ) ); ?>" class="badge badge-secondary pt-2 pr-3 pl-3 pb-2 mb-1 ml-1 text-white font-weight-normal"><?php echo esc_html( $term->name ); ?></a>

	<?php endforeach; ?>

</div>
