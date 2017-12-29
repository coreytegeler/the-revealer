<?php
/*
Template Name: Discover
*/
get_header();
	echo '<div id="discover" class="readable">';
		echo '<div class="inner">';
			get_template_part( 'parts/stats' );
			echo '<div class="loop discover xsmall grid">';
				// get_template_part( 'parts/discover' );
			echo '</div>';
			echo '<div class="loader"><div class="circle"></div></div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
get_footer();
?>