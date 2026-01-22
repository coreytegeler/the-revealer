<?php
/*
Template Name: Events
*/
get_header();
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$events_args = array(
	'post_type' => 'event',
	'paged' => $paged,
	'posts_per_page' => 12,
	'order' => 'DESC',
	'orderby' => 'meta_value',
	'meta_key' => 'event_date'
);

echo '<div class="readable">';
	echo '<div class="loop row events">';
		query_posts( $events_args );
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				set_query_var( 'col_size', 'col-12 col-sm-6 col-md-4 col-lg-3' );
				get_template_part( 'parts/event' );
			}
		}
		get_template_part( 'parts/pagination' );
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_footer();
?>