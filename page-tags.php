<?php
/*
Template Name: Tags
*/
get_header();
echo '<div class="readable">';
	echo '<div id="tags">';
		echo '<div class="commas">';
			$articles_page = get_page_by_path( 'articles' );
			if( $articles_page ) {
				$articles_url = get_permalink( $articles_page );
			} else {
				$articles_url = get_site_url() . '/articles/';
			}
			$paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
			$per_page = -1;
			$offset = ( $paged - 1 ) * $per_page;
			$tags = get_tags( array(
			  'orderby' => 'count',
			  'order' => 'desc',
			  'hide_empty' => 0
			) );
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