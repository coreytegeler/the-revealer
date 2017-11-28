<?php
/*
YARPP Template: Custom
Author: Corey Tegeler
*/
if ( have_posts() ) {
	echo '<div id="related">';
		echo '<h1 class="section_header"><em>Read more related articles.</em></h1>';
		echo '<div class="loop articles four_col grid">';
			while ( have_posts() ) {
				the_post();
				get_template_part( 'parts/article' );
			}
			wp_reset_query();
		echo '</div>';
	echo '</div>';
}
?>