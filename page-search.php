<?php
get_header();
global $wp_query;
echo '<div class="readable">';
	echo '<div class="max">';
		echo '<div class="search_header">';
			get_template_part( 'parts/stats' );
			get_search_form();
		echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';
get_footer();
?>