<?php
	global $post;
	$paged = get_query_var( 'paged' );
	if(!$paged) {
		$paged = 1;
	}
	$prev = $paged - 1;
	$next = $paged + 1;
	$prev_svg_url = get_template_directory_uri() . '/assets/images/left.svg';
	$next_svg_url = get_template_directory_uri() . '/assets/images/right.svg';
	$prev_svg = file_get_contents( $prev_svg_url );
	$next_svg = file_get_contents( $next_svg_url );
	echo '<div class="pagination">';
		if( is_home() ) {
			echo '<div class="paginate prev"></div>';
			echo '<div class="paginate next">';
				$articles_page = get_page_by_path( 'articles' );
				$view_more = get_permalink( $articles_page->ID );
				echo '<a href="' . $view_more . '">';
					echo '<div class="text"><em>View more</em></div>';
					echo '<div class="circle">' . $next_svg . '</div>';
				echo '</a>';
			echo '</div>';
		} else if( is_single() ) {
			$prev_article = get_previous_post();
			$next_article = get_next_post();
			echo '<div class="paginate prev">';
				echo '<a href="' . $view_more . '">';
					echo '<div class="text"><em>' . $prev_article->post_title . '</em></div>';
					echo '<div class="circle">' . $prev_svg . '</div>';
				echo '</a>';
			echo '</div>';
			echo '<div class="paginate next">';
				echo '<a href="' . $view_more . '">';
				echo '<div class="text"><em>' . $next_article->post_title . '</em></div>';
				echo '<div class="circle">' . $next_svg . '</div>';
				echo '</a>';
			echo '</div>';
		} else {
			echo '<div class="paginate prev">';
				$prev_html = '<div class="text"><em>Previous page</em></div><div class="circle">' . $prev_svg . '</div>';
				echo get_previous_posts_link( $prev_html, $prev );
			echo '</div>';
			echo '<div class="paginate next">';
				$next_html = '<div class="text"><em>Next page</em></div><div class="circle">' . $next_svg . '</div>';
				echo get_next_posts_link( $next_html, $next );
			echo '</div>';
		}
	echo '</div>';
?>
