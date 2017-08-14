<?php
/*
Template Name: Category
*/
get_header();
$cat = get_the_category(); 
$cat_id = $cat[0]->cat_ID;
$posts_args = array(
	'posts_per_page' => 15,
	'cat' => $cat_id
);
query_posts( $posts_args );
get_template_part( 'snippets/loop' );
get_footer();
?>