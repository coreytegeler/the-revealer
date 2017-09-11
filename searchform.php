<?php
global $wp_query;
echo '<div id="searchform">';
	echo '<form action="' . get_bloginfo('siteurl') . '" method="get">';
		if( $wp_query->query ) {
			$placeholder = 'Seeking something else?';
		} else {
			$placeholder = 'Seeking something?';
		}
	  echo '<input type="search" id="searchbox" name="s" placeholder="' . $placeholder . '" required>';
	echo '</form>';
echo '</div>';
?>