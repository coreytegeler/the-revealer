<?php
get_header();
	echo '<div id="discover" class="readable">';
		echo '<div class="inner">';
			get_template_part( 'parts/stats' );
			// 	echo '<div class="wrap">';
			// 		echo '<h1 class="glisten">Discover&nbsp;more...</h1>';
			// 	echo '</div>';
			// 	echo '<div class="circle"></div>';
			echo '<div class="loop discover xsmall grid">';
				get_template_part( 'parts/discover' );
			echo '</div>';
			// get_search_form();
			echo '</div>';
		echo '</div>';
	echo '</div>';
get_footer();
?>