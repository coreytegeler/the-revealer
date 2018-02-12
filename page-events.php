<?php
/*
Template Name: Events
*/
get_header();
$paged = get_query_var( 'paged' );
$events_args = array(
	'post_type' => 'event',
	'paged' => $paged,
	'orderby' => 'date',
	'order' => 'desc',
	'posts_per_page' => -1
);

echo '<div class="readable">';
	echo '<div class="loop events three_col masonry">';
		query_posts( $events_args );
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'parts/event' );
			}
		}
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_template_part( 'parts/pagination' );
get_footer();
?>