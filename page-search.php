<?php
get_header();
global $wp_query;
echo '<div class="readable">';
	echo '<div class="max">';
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
			// echo '<div id="tags">';
				// echo '<div class="commas tags">';
					// echo '<span class="no_comma">Checkout some popular tags:&nbsp;</span>&nbsp;';
					// $tags = get_tags( array(
					//   'orderby' => 'count',
					//   'order' => 'desc',
					//   'number' => 20,
					//   'hide_empty' => 0
					// ) );

					// $articles_page = get_page_by_path( 'articles' );
					// if( $articles_page ) {
					// 	$articles_url = get_permalink( $articles_page );
					// } else {
					// 	$articles_url = get_site_url() . '/articles/';
					// }
					// foreach( $tags as $tag ) {
					// 	echo '<span>';
					// 		$tag_url = add_query_arg( 'tag', $tag->slug, $articles_url );
					// 		echo '<a href="' . $tag_url . '" class="tag">' . $tag->name . '</a>';
					// 	echo '</span>';
					// }
					// $tag_page = get_page_by_path( 'tags' );
					// $tag_page_url = get_permalink( $tag_page->ID );
					// echo '<span class="none no_line no_comma">';
					// 	echo '<a href="' . $tag_page_url . '">and more</a>.';
					// echo '</span>';
				// echo '</div>';
			// echo '</div>';
		echo '</div>';
	echo '</div>';
	// get_template_part( 'parts/goldbar' );
echo '</div>';
get_footer();
?>