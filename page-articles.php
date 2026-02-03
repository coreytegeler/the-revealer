<?php
get_header();
$cat_param = isset( $_GET['category'] ) ? $_GET['category'] : null;
$year_param = isset( $_GET['y'] ) ? $_GET['y'] : null;
$column_param = isset( $_GET['column'] ) ? $_GET['column'] : null;
$tag_param = isset( $_GET['tag'] ) ? $_GET['tag'] : null;
$paged = get_query_var( 'paged' );
if( !$paged ) {
	$paged = 1;
}
$articles_args = array(
	'post_type' => 'post',
	'paged' => $paged,
	'posts_per_page' => 24
);

$tax_query = array();
if( $cat_param ) {
	$articles_args['category_name'] = $cat_param;
	$category = get_category_by_slug( $cat_param );
	$filter_name = $category ? $category->name : $cat_param;
}
if( $year_param ) {
	$articles_args['year'] = $year_param;
	$filter_name = $year_param;
}
if( $column_param ) {
	$tax_query[] = array(
		'taxonomy' => 'columns',
		'field' => 'slug',
		'terms' => $column_param,
  );
  $column = get_term_by( 'slug', $column_param, 'columns' );
	$filter_name = $column ? $column->name : $column_param;
}
if( $tag_param ) {
	$articles_args['tag'] = $tag_param;
	$tag = get_term_by( 'slug', $tag_param, 'post_tag' );
	$filter_name = $tag ? $tag->name : $tag_param;
}

if( $tax_query ) {
	$articles_args['tax_query'] = $tax_query;
}

query_posts( $articles_args );
echo '<div class="readable">';
	echo '<h1 class="sr-only">' . $post->post_title . '</h1>';
	if ( $cat_param || $year_param || $column_param || $tag_param ) {
		echo '<h2 class="page-title">' . 'Showing articles in "' . $filter_name . '"' . '</h2>';
	} else {
		echo '<div class="page-title-spacer"></div>';
	}
	echo '<div class="loop articles row">';
		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ) {
				the_post();
				set_query_var( 'col_size', 'col-12 col-sm-6 col-md-12 col-lg-4' );
				set_query_var( 'article', $post );
				get_template_part( 'parts/article' );
			}
		} else {
			echo '<div id="empty">';
				echo 'Sorry, there are no articles for this query.';
			echo '</div>';
		}
	echo '</div>';
echo '</div>';
// set_query_var( 'post', $post );
get_template_part( 'parts/pagination' );
wp_reset_query();
get_footer();
?>