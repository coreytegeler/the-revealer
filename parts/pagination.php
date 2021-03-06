<?php
global $post;
$paged = get_query_var( 'paged' );
if(!$paged) {
	$paged = 1;
}
$prev_svg_url = get_template_directory_uri() . '/assets/images/left.svg';
$next_svg_url = get_template_directory_uri() . '/assets/images/right.svg';
$prev_svg = file_get_contents( $prev_svg_url );
$next_svg = file_get_contents( $next_svg_url );
echo '<div class="pagination">';
	if( $post->post_name == 'home' ) {
		echo '<div class="paginate prev"></div>';
		echo '<div class="paginate next">';
			$articles_page = get_page_by_path( 'articles' );
			$view_more = get_permalink( $articles_page->ID );
			echo '<a href="' . $view_more . '">';
				echo '<div class="link_text"><em>View more articles</em></div>';
				echo '<div class="circle">' . $next_svg . '</div>';
			echo '</a>';
		echo '</div>';
	} else if( is_single() ) {
		$prev_article = get_previous_post();
		$next_article = get_next_post();
		echo '<div class="paginate prev">';
			echo '<a href="' . get_permalink( $prev_article ) . '">';
				echo '<div class="link_text"><em>' . $prev_article->post_title . '</em></div>';
				echo '<div class="circle">' . $prev_svg . '</div>';
			echo '</a>';
		echo '</div>';
		echo '<div class="paginate next">';
			echo '<a href="' . get_permalink( $next_article ) . '">';
			echo '<div class="link_text"><em>' . $next_article->post_title . '</em></div>';
			echo '<div class="circle">' . $next_svg . '</div>';
			echo '</a>';
		echo '</div>';
	} else {
		echo '<div class="paginate prev">';
			$prev_html = '<div class="link_text"><em>Previous page</em></div><div class="circle">' . $prev_svg . '</div>';
			echo previous_posts_link( $prev_html );
		echo '</div>';
		echo '<div class="paginate next">';
			$next_html = '<div class="link_text"><em>Next page</em></div><div class="circle">' . $next_svg . '</div>';
			echo next_posts_link( $next_html );
		echo '</div>';
	}
echo '</div>';
?>
