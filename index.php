<?php
get_header();
$cat_param = get_query_var( 'category' );
$year_param = get_query_var( 'year' );
$column_param = get_query_var( 'column' );
$tag_param = get_query_var( 'tag' );
$posts_args = array(
	'post_type' => 'post'
);

if( isset( $cat_param ) ) {
	$posts_args['category_name'] = $cat_param;
}
if( isset( $year_param ) ) {
	$posts_args['y'] = $year_param;
}
if( isset( $column_param ) ) {
	$posts_args['column'] = $column_param;
}
if( isset( $tag_param ) ) {
	$posts_args['tag'] = $tag_param;
}

$featured_amount = 0;
$medium_amount = 4;
$small_amount = 15;
echo '<div class="readable">';

	// echo '<div class="loop posts large featured">';
	// 	$featured_args = array(
	// 		'posts_per_page' => $featured_amount
	// 	);
	// 	query_posts( $featured_args );
	// 	if ( have_posts() ) {
	// 		while ( have_posts() ) {
	// 			the_post();
	// 			get_template_part( 'parts/post', array( 'size', 'featured' ) );
	// 		}
	// 	}
	// echo '</div>';

	// echo '<div class="loop posts medium grid">';
	// 	$medium_args = array_merge( $posts_args, array(
	// 			'posts_per_page' => $medium_amount,
	// 			'offset' => $featured_amount
	// 	) );
	// 	query_posts( $medium_args );
	// 	if ( have_posts() ) {
	// 		while ( have_posts() ) {
	// 			the_post();
	// 			get_template_part( 'parts/post' );
	// 		}
	// 	}
	// echo '</div>';

	echo '<div class="loop posts small grid">';
		$small_args = array_merge( $posts_args, array(
				'posts_per_page' => $small_amount
				// 'offset' => $featured_amount + $medium_amount
		) );
		query_posts( $small_args );
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'parts/post' );
			}
		}
	echo '</div>';
echo '</div>';
get_footer();
?>