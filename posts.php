<?php
get_header();
$cat_param = get_query_var( 'category' );
$year_param = get_query_var( 'year' );
$column_param = get_query_var( 'column' );
$posts_args = array(
	'post_type' => 'post',
	'posts_per_page' => 25
);

if( isset( $cat_param ) ) {
	$posts_args['category_name'] = $cat_param;
}
if( isset( $year_param ) ) {
	$posts_args['year'] = $year_param;
}
if( isset( $column_param ) ) {
	$posts_args['column'] = $column_param;
}

echo '<div class="readable">';
	echo '<div class="loop posts small grid">';
		query_posts( $posts_args );
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