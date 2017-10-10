<?php
global $post;
	
$rand_post_args = array(
	'post_type' => 'post',
	'posts_per_page' => 17,
	'orderby' => 'rand',
	'meta_query' => array(
		array(
			'key' => '_thumbnail_id',
			'compare' => 'EXISTS'
		),
	)
);

if( $discovered = $_POST['discovered'] ) {
	$rand_post_args = array_merge( $rand_post_args, array(
			'post__not_in' => $discovered
	) );
}

$rand_cells = get_posts( $rand_post_args );

// $rand_cells = array_merge( $rand_posts, $rand_cats );
shuffle( $rand_cells );
foreach( $rand_cells as $post ) {
	setup_postdata( $post );
	get_template_part( 'parts/cell', 'discover' );
	wp_reset_postdata();
}
?>