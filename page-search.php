<?php
get_header();
global $wp_query;
echo '<div class="readable">';
	echo '<div class="search_header">';
		get_template_part( 'parts/stats' );
		get_search_form();

		$discover_url = get_permalink( get_page_by_path( 'discover' ) );
		echo '<div class="header">';
			echo '<div class="wrap">';
				echo '<div class="shape"></div>';
				echo '<div class="shape"></div>';
				echo '<a class="stats" href="' . $discover_url . '">';
					echo '<h1><div class="animation glisten bounce">' . wrap_words( 'discover more' ) . '</div></h1>';
				echo '</a>';
			echo '</div>';
		echo '</div>';

		echo '<div id="discover" class="bottom">';
			echo '<div class="inner">';
				echo '<div class="loop discover xsmall grid">';
					get_template_part( 'parts/discover' );
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
echo '</div>';
get_footer();
?>