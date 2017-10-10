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
echo '<div class="loop categories medium masonry">';
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'parts/article', array( 'size' => 'medium' ) );
		}
	}
echo '</div>';
get_footer();
?>