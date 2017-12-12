<?php
get_header();
	echo '<div id="404" class="readable">';
		echo '<div class="max">';
			echo '<div class="search_header">';
				get_template_part( 'parts/stats' );
				$current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$tokens = explode( '/', $current_url );
				// echo $tokens[sizeof( $tokens )-1];
				get_search_form();
			echo '</div>';
		echo '</div>';
		echo '<div id="discover">';
			echo '<div class="loop discover xsmall grid">';
				get_template_part( 'parts/discover' );
			echo '</div>';
		echo '</div>';
	echo '</div>';
get_footer();
?>