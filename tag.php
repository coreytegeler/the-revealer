<?php
/*
Template Name: Tag
*/
get_header();
global $query_string;

echo '<div class="readable">';
	echo '<div class="max">';
		echo '<div class="search_header">';
			$term = get_queried_object()->name;
			echo '<div class="text">';
				echo '<h1>Here are articles tagged <em>' . $term . '</em>.</h1>';
			echo '</div>';
		echo '</div>';
		echo '<div class="loop posts small masonry">';
			wp_parse_str( $query_string, $search_args );
			$search_args['posts_per_page'] = $tagged_amount;
			$search_query = new WP_Query( $search_args );
			if ( $search_query->have_posts() ) {
				while ( $search_query->have_posts() ) {
					$search_query->the_post();
					get_template_part( 'parts/article' );
				}
				wp_reset_query();
			}
		echo '</div>';
	echo '</div>';
echo '</div>';
get_template_part( 'parts/pagination' );
get_footer();
?>