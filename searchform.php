<?php
global $post;
global $query_string;
if( $query_string ) {
	wp_parse_str( $query_string, $search_args );
}
echo '<div id="searchform">';
	echo '<form action="' . get_bloginfo('siteurl') . '" method="get">';
		if( isset( $search_args ) && $search_args['s']) {
			$placeholder = 'Seeking something else?';
		} else {
			$placeholder = 'Seeking something?';
		}
		echo '<label for="search">';
			echo '<em>Search</em>';
		  echo '<input type="search" id="searchbox" name="s" placeholder="' . $placeholder . '" required>';
	  echo '</label>';
	echo '</form>';
echo '</div>';
?>