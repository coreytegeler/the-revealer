<?php
get_header();
global $wp_query;
echo '<div class="readable">';
	echo '<div class="max">';
		echo '<div class="search_header">';
			echo '<div class="text">';
				echo '<h1>Here are the results for <em>' . get_search_query() . '</em>.</h1>';
			echo '</div>';
			get_search_form();
		echo '</div>';
		echo '</div>';
		echo '<div class="loop posts small grid">';
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'parts/post' );
				}
			}
		echo '</div>';
	echo '</div>';
echo '</div>';
get_footer();
?>