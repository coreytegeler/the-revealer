<?php
	$paged = get_query_var( 'paged' );
	if(!$paged) {
		$paged = 1;
	}
	$prev = $paged - 1;
	$next = $paged + 1;
	echo '<div class="pagination">';
		echo '<div class="paginate prev">';
			$left_svg = get_template_directory_uri() . '/assets/images/left.svg';
			echo previous_posts_link( file_get_contents( $left_svg ), $prev );
		echo '</div>';
		echo '<div class="paginate next">';
			$right_svg = get_template_directory_uri() . '/assets/images/right.svg';
			echo get_next_posts_link( file_get_contents( $right_svg ), $next );
		echo '</div>';
	echo '</div>';
?>
