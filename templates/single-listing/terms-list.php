<?php
/**
 * The template for displaying the listing's single terms list powered by elementor.
 *
 * This template can be overridden by copying it to yourtheme/posterno/elementor/single-listing/terms-list.php
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

$args = array(
	'hide_empty' => isset( $data->hide_empty ) && $data->hide_empty === 'yes' ? true : false,
	'parent'     => 0,
	'number'     => isset( $data->number ) && ! empty( $data->number ) ? absint( $data->number ) : 999,
	'orderby'    => isset( $data->orderby ) && ! empty( $data->orderby ) ? esc_attr( $data->orderby ) : 'name',
	'order'      => isset( $data->order ) && ! empty( $data->order ) ? esc_attr( $data->order ) : 'ASC',
	'exclude'    => isset( $data->exclude ) && ! empty( $data->exclude ) ? array_filter( explode( ',', trim( $data->exclude ) ) ) : false,
	'include'    => isset( $data->include ) && ! empty( $data->include ) ? array_filter( explode( ',', trim( $data->include ) ) ) : false,
	'parent'     => isset( $data->parent ) && ! empty( $data->parent ) ? absint( $data->parent ) : false,
);

$taxonomy_id = isset( $data->taxonomy_id ) && ! empty( $data->taxonomy_id ) ? esc_attr( $data->taxonomy_id ) : false;
$terms       = wp_get_post_terms( get_queried_object_id(), $taxonomy_id, $args );

$i = 0;

if ( ! is_array( $terms ) || empty( $terms ) ) {
	return;
}

$icon_layout = isset( $data->icon ) && ! empty( $data->icon ) ? $data->icon : 'disabled';
$icon        = isset( $data->custom_icon ) && ! empty( $data->custom_icon ) ? $data->custom_icon : false;
$style       = isset( $data->layout_skin ) && ! empty( $data->layout_skin ) ? $data->layout_skin : 'vertical';

?>

<?php if ( $style === 'vertical' ) : ?>

	<ul class="list-group list-group-flush m-0 p-0">

		<?php foreach ( $terms as $term ) : ?>
			<li class="list-group-item pl-0">

				<?php if ( $icon_layout !== 'disabled' ) : ?>

					<?php if ( $icon_layout === 'custom' ) : ?>
						<i class="<?php echo esc_attr( $icon['value'] ); ?> text-info mx-auto"></i>
					<?php else : ?>
						<i class="<?php echo esc_attr( carbon_get_term_meta( $term->term_id, 'term_icon' ) ); ?> text-info mx-auto"></i>
					<?php endif; ?>

				<?php endif; ?>

				<span class="ml-1"><?php echo esc_html( $term->name ); ?></span>
			</li>
		<?php endforeach; ?>

	</ul>

<?php elseif ( $style === 'horizontal' ) : ?>

	<ul class="list-inline m-0 p-0">

		<?php foreach ( $terms as $term ) : ?>
			<li class="list-inline-item pl-0">

				<?php if ( $icon_layout !== 'disabled' ) : ?>

					<?php if ( $icon_layout === 'custom' ) : ?>
						<i class="<?php echo esc_attr( $icon['value'] ); ?> text-info mx-auto"></i>
					<?php else : ?>
						<i class="<?php echo esc_attr( carbon_get_term_meta( $term->term_id, 'term_icon' ) ); ?> text-info mx-auto"></i>
					<?php endif; ?>

				<?php endif; ?>

				<span class="ml-1"><?php echo esc_html( $term->name ); ?></span>
			</li>
		<?php endforeach; ?>

	</ul>

<?php endif; ?>
