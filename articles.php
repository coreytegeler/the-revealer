<?php
/*
Template Name: Articles
*/
get_header();
$cat_param = $_GET['category'];
$year_param = $_GET['y'];
$column_param = $_GET['column'];
$paged = $_GET['paged'];
$articles_args = array(
	'post_type' => 'post',
	'paged' => $paged,
	'posts_per_page' => 25
);

$tax_query = array();
if( sizeof( $cat_param ) ) {
	$articles_args['category_name'] = $cat_param;
}
if( sizeof( $year_param ) ) {
	$articles_args['year'] = $year_param;
}
if( sizeof( $column_param ) ) {
	$tax_query[] = array(
		'taxonomy' => 'columns',
		'field' => 'slug',
	  'terms' => $column_param,
  );
}

if( sizeof( $tax_query ) ) {
	$articles_args['tax_query'] = $tax_query;
}


echo '<div class="readable">';
	echo '<div class="loop articles medium masonry">';
		query_posts( $articles_args );
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'parts/article' );
			}
		} else {
			echo '<div id="empty">';
				echo 'Sorry, this are no articles.';
			echo '</div>';
		}
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_template_part( 'parts/pagination' );
get_footer();
?>