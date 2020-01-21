<?php
/**
 * The template for displaying the listings query powered by elementor.
 *
 * This template can be overridden by copying it to yourtheme/posterno/elementor/listings-query.php
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

use Posterno\Elementor\Helper;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$posts_per_page = isset( $data->posts_per_page ) ? absint( $data->posts_per_page ) : absint( pno_get_listings_results_per_page_options() );
$featured       = isset( $data->show_featured ) && $data->show_featured === 'yes';
$pagination     = isset( $data->pagination ) && $data->pagination === 'enabled';
$layout         = isset( $_GET['layout'] ) ? pno_get_listings_results_active_layout() : ( isset( $data->layout_mode ) && array_key_exists( $data->layout_mode, pno_get_listings_layout_options() ) ? $data->layout_mode : pno_get_listings_results_active_layout() );
$author_ids     = isset( $data->query_authors ) && ! empty( $data->query_authors ) ? array_filter( explode( ',', trim( $data->query_authors ) ) ) : false;

$args = [
	'post_type'         => 'listings',
	'pno_search'        => true,
	'is_listings_query' => true,
	'posts_per_page'    => $posts_per_page,
];

// Add pagination support.
if ( $pagination ) {
	$args['paged'] = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
}

// Add featured listings only support to the query.
if ( $featured ) {
	$args['meta_query'] = [
		[
			'key'   => '_listing_is_featured',
			'value' => 'yes',
		],
	];
}

$taxonomies      = Helper::get_registered_taxonomies();
$object_vars     = get_object_vars( $data );
$taxonomy_filter = [];

foreach ( $taxonomies as $tax_slug => $tax_name ) {
	if ( isset( $object_vars[ "taxonomy_{$tax_slug}" ] ) && ! empty( $object_vars[ "taxonomy_{$tax_slug}" ] ) ) {
		$taxonomy_terms    = $object_vars[ "taxonomy_{$tax_slug}" ];
		$taxonomy_filter[] = [
			'taxonomy' => $tax_slug,
			'field'    => 'term_id',
			'terms'    => $taxonomy_terms,
		];
	}
}

if ( ! empty( $taxonomy_filter ) ) {
	$args['tax_query'] = $taxonomy_filter;
}

// Add specific author support to the query.
if ( $author_ids ) {
	$args['author__in'] = $author_ids;
}

$i = '';

/**
 * Filter: allow developers to modify the WP_Query arguments for listings
 * generated through the elementor block.
 *
 * @param array $args WP_Query arguments list.
 * @param object $data attributes sent through the block.
 * @param string $filter_id optional id provided by the widget.
 * @return array
 */
$args = apply_filters( 'pno_listings_elementor_query', $args, $data, $data->filter_id );

$elementor_query = new WP_Query( $args );

?>

<div class="pno-block-listings-wrapper posterno-template">
	<?php

	if ( $elementor_query->have_posts() ) {

		// Start opening the grid's container.
		if ( $layout === 'grid' ) {
			echo '<div class="card-deck">';
		}

		while ( $elementor_query->have_posts() ) {

			$elementor_query->the_post();

			posterno()->templates->get_template_part( 'listings/card', $layout );

			// Continue the loop of grids containers.
			if ( $layout === 'grid' ) {
				$i++;
				if ( $i % 3 == 0 ) {
					echo '</div><div class="card-deck">';
				}
			}
		}

		// Close grid's container.
		if ( $layout === 'grid' ) {
			echo '</div>';
		}

		if ( $pagination ) {
			posterno()->templates
				->set_template_data(
					[
						'query' => $elementor_query,
					]
				)
				->get_template_part( 'listings/results', 'footer' );
		}

	} else {

		posterno()->templates->get_template_part( 'listings/not-found' );

	}

	wp_reset_postdata();

	?>
</div>
