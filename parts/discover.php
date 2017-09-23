<?php
global $post;
// $rand_cat_args = array(
//   'number' => 3,
//   'orderby' => 'rand'
// );
// $rand_cats = get_categories(  $rand_cat_args );

print_r( $already_used );

$rand_post_args = array(
	'post_type' => 'post',
	'posts_per_page' => 15,
	'orderby' => 'rand',
	'meta_query' => array(
		array(
			'key' => '_thumbnail_id',
			'compare' => 'EXISTS'
		),
	)
);
$rand_cells = get_posts( $rand_post_args );

// $rand_cells = array_merge( $rand_posts, $rand_cats );
shuffle( $rand_cells );
foreach( $rand_cells as $post ) {
	setup_postdata( $post );
	get_template_part( 'parts/cell', 'discover' );
	wp_reset_postdata();
}
?>