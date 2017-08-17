<?php
get_header();

$posts_args = array(
	'posts_per_page' => 10
);

echo '<div class="loop posts large featured">';
	$featured_args = array(
		'posts_per_page' => 1
	);
	query_posts( $featured_args );
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'snippets/article', array( 'size', 'featured' ) );
		}
	}
echo '</div>';

echo '<div class="loop posts medium grid">';
	$medium_args = array(
		'posts_per_page' => 4,
		'offset' => 1
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
		'posts_per_page' => 9,
		'offset' => 6
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