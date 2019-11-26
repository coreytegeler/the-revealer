<?php
get_header();
// global $wp_query;
global $query_string;
// global $query_string;
echo '<div class="readable">';
	echo '<div class="seeker">';
		echo '<div class="search_header">';
			get_search_form();

			get_template_part( 'parts/goldbar' );

			echo '<h2 class="section_header">';
				echo 'Here are the results for <em>' . get_search_query() . '</em>.';
			echo '</h2>';
		echo '</div>';
	echo '</div>';
	echo '<div class="loop row articles">';
		wp_parse_str( $query_string, $search_args );
		// $search_args['posts_per_page'] = $tagged_amount;
		$search_args['posts_per_page'] = 24;
		$search_query = new WP_Query( $search_args );
		if ( $search_query->have_posts() ) {
			while ( $search_query->have_posts() ) {
				$search_query->the_post();
				set_query_var( 'col_size', 'col-12 col-sm-6 col-lg-4' );
				get_template_part( 'parts/article' );
			}
			wp_reset_query();
		}
	echo '</div>';
echo '</div>';
get_template_part( 'parts/pagination' );
get_footer();
?>