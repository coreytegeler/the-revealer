<?php
get_header();
global $wp_query;
echo '<div class="readable">';
	echo '<div class="seeker">';
		get_template_part( 'parts/seeker' );
	echo '</div>';
echo '</div>';
get_footer();
?>