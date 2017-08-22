<?php
get_header();

$posts_args = array(
	'posts_per_page' => 10
);

$featured_amount = 0;
$medium_amount = 4;
$small_amount = 9;

// echo '<div class="loop posts large featured">';
// 	$featured_args = array(
// 		'posts_per_page' => $featured_amount
// 	);
// 	query_posts( $featured_args );
// 	if ( have_posts() ) {
// 		while ( have_posts() ) {
// 			the_post();
// 			get_template_part( 'snippets/article', array( 'size', 'featured' ) );
// 		}
// 	}
// echo '</div>';

echo '<div class="loop posts medium grid">';
	$medium_args = array(
		'posts_per_page' => $medium_amount,
		'offset' => $featured_amount
	);
	query_posts( $medium_args );
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'snippets/article', array( 'size', 'medium' ) );
		}
	}
echo '</div>';

echo '<div class="loop posts small grid">';
	$small_args = array(
		'posts_per_page' => $small_amount,
		'offset' => $featured_amount + $medium_amount
	);
	query_posts( $small_args );
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'snippets/article', array( 'size', 'small' ) );
		}
	}
echo '</div>';

get_footer();
?>