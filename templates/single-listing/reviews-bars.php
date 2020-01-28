<?php
/**
 * The template for displaying the listings reviews bars summary powered by elementor.
 *
 * This template can be overridden by copying it to yourtheme/posterno/elementor/single-listing/reviews-bars.php
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

$listing_id    = get_queried_object_id();
$rating        = absint( \Posterno\Reviews\Rating::get_for_listing( $listing_id ) );
$total_reviews = \Posterno\Reviews\Helper::get_total_reviews_for_listing( $listing_id );
$stats         = \Posterno\Reviews\Helper::get_reviews_ratings_stats( $listing_id );

if ( $total_reviews <= 0 ) {
	return;
}

?>

<div class="listing-review-summary mt-3 mb-5">

<?php

foreach ( array_reverse( \Posterno\Reviews\Helper::get_overall_rating_options() ) as $rating_bar ) :

	$percentage = \Posterno\Reviews\Helper::get_reviews_ratings_stats_percentage( $stats, $rating_bar );

	?>
	<div class="row no-gutters align-items-center mb-1">
		<div class="col-1">
			<span class="font-weight-bold"><?php echo absint( $rating_bar ); ?></span>
			<i class="fas fa-star"></i>
		</div>
		<div class="col-11">
			<div class="progress">
				<?php if ( $percentage > 0 ) : ?>
					<div class="progress-bar" role="progressbar" style="width: <?php echo esc_attr( $percentage ); ?>%;" aria-valuenow="<?php echo esc_attr( $percentage ); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo esc_attr( $percentage ); ?>%</div>
				<?php else : ?>
					<div class="progress-bar" role="progressbar" style="width: <?php echo esc_attr( $percentage ); ?>%;" aria-valuenow="<?php echo esc_attr( $percentage ); ?>" aria-valuemin="0" aria-valuemax="100"></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>

</div>
