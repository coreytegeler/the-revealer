<?php
/*
Template Name: Tags
*/
get_header();
echo '<div class="readable">';
	echo '<div id="tags">';
		echo '<div class="commas">';
			$articles_url = get_articles_page();
			$paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
			$per_page = -1;
			$offset = ( $paged - 1 ) * $per_page;
			$tags = get_tags( array(
			  'hide_empty' => 1
			) );
			shuffle( $tags );
			foreach( $tags as $tag ) {
				$tag_url = add_query_arg( 'tag', $tag->slug, $articles_url );
				echo '<span>';
					echo '<a href="' . $tag_url . '" class="tag">' . $tag->name . '</a>';
				echo '</span>';
			}
			wp_reset_query();
		echo '</div>';
	echo '</div>';
echo '</div>';
get_template_part( 'parts/pagination' );
get_footer();
?>