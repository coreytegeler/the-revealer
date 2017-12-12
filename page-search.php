<?php
get_header();
global $wp_query;
echo '<div class="readable">';
	echo '<div class="max">';
		echo '<div class="search_header">';
			get_template_part( 'parts/stats' );
			get_search_form();
			echo '<div id="tags">';
				echo '<div class="commas tags">';
					echo '<span class="no_comma">Checkout some popular tags:&nbsp;</span>&nbsp;';
					$tags = get_tags( array(
					  'orderby' => 'count',
					  'order' => 'desc',
					  'number' => 20,
					  'hide_empty' => 0
					) );

					$articles_page = get_page_by_path( 'articles' );
					if( $articles_page ) {
						$articles_url = get_permalink( $articles_page );
					} else {
						$articles_url = get_site_url() . '/articles/';
					}
					foreach( $tags as $tag ) {
						echo '<span>';
							$tag_url = add_query_arg( 'tag', $tag->slug, $articles_url );
							echo '<a href="' . $tag_url . '" class="tag">' . $tag->name . '</a>';
						echo '</span>';
					}
					$tag_page = get_page_by_path( 'tags' );
					$tag_page_url = get_permalink( $tag_page->ID );
					echo '<span class="none no_line no_comma">';
						echo '<a href="' . $tag_page_url . '">and more</a>.';
					echo '</span>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	echo '<div class="goldbar"><div class="solid"></div></div>';
echo '</div>';
get_footer();
?>