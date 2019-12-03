<?php
/*
YARPP Template: Custom
Author: Corey Tegeler
*/
if ( have_posts() ) {
	echo '<div id="related">';
		get_template_part( 'parts/goldbar' );
		echo '<h1 class="section_header"><em>Here are some related articles....</em></h1>';
		echo '<div class="loop articles row">';
			while ( have_posts() ) {
				the_post();
				set_query_var( 'col_size', 'col-12 col-sm-6 col-lg-3' );
				get_template_part( 'parts/article' );
			}
			wp_reset_query();
		echo '</div>';
	echo '</div>';
}
?>