<?php
$cat_param = $_GET['category'];
$year_param = $_GET['y'];
$col_param = $_GET['column'];
$tag_param = $_GET['tag'];
if( $cat_id = get_query_var( 'cat' ) ) {
	$cat_param = get_category( $cat_id, false )->slug;
}

$articles_page = get_page_by_path( 'articles' );
if( $articles_page ) {
	$page_url = get_permalink( $articles_page );
} else {
	$page_url = get_site_url() . '/articles/';
}

if( $cat_param ) {
	$page_url = add_query_arg( 'category', $cat_param, $page_url );
}
if( $year_param ) {
	$page_url = add_query_arg( 'y', $year_param, $page_url );
}
if( $col_param ) {
	$page_url = add_query_arg( 'column', $col_param, $page_url );
}
if( $tag_param ) {
	$page_url = add_query_arg( 'tag', $tag_param, $page_url );
}
echo '<div id="filters">';
	echo '<div class="links">';
		echo '<div class="link filter categories">';
			echo '<div class="label">Categories</div>';
			$categories = get_categories( array(
			  'orderby' => 'name',
			  'order'   => 'ASC'
			) );
			echo '<div class="commas categories">';
				if( sizeof( $categories ) ) {
					foreach( $categories as $category) {
						$cat_title = $category->name;
						$cat_slug = $category->slug;
						$cat_id = $category->cat_ID;
						$cat_class = $cat_slug;
						if( $cat_slug === $cat_param ) {
							$cat_url = remove_query_arg( 'category', $page_url );
							$cat_class .= ' selected';
						} else {
							$cat_url = add_query_arg( 'category', $cat_slug, $page_url );
						}
						echo '<span class="' . $cat_class . '">';
							echo '<a href="' . $cat_url . '" class="category">' . $cat_title . '</a>';
						echo '</span>';
					}
				}
			echo '</div>';
		echo '</div>';

		echo '<div class="link filter years">';
			echo '<div class="label">Years</div>';
			echo '<div class="commas years">';
				$year = date( 'Y' );
				while(  $year >= 2003 ) {
					$year_class = $year;
					if( $year == $year_param ) {
						$year_url = remove_query_arg( 'y', $page_url );
						$year_class .= ' selected';
					} else {
						$year_url = add_query_arg( 'y', $year, $page_url );
					}
					echo '<span class="' . $year_class . '">';
						echo '<a href="' . $year_url . '" class="year">' . $year . '</a>';
					echo '</span>';
					$year--;
				}
			echo '</div>';
		echo '</div>';

		echo '<div class="link filter columns">';
			echo '<div class="label">Columns</div>';
			$columns = get_terms( array(
				'taxonomy' => 'columns',
			  'orderby' => 'name',
			  'order'   => 'ASC'
			) );
			echo '<div class="commas columns">';
				if( sizeof( $columns ) ) {
					foreach( $columns as $column) {
						$col_title = $column->name;
						$col_slug = $column->slug;
						$col_id = $column->cat_ID;
						$col_class = $col_slug;
						if( $col_slug === $col_param ) {
							$col_url = remove_query_arg( 'column', $page_url );
							$col_class .= ' selected';
						} else {
							$col_url = add_query_arg( 'column', $col_slug, $page_url );
						}
						echo '<span class="' . $col_class . '">';
							echo '<a href="' . $col_url . '" class="column">' . $col_title . '</a>';
						echo '</span>';
					}
				}
			echo '</div>';
		echo '</div>';

		echo '<div class="link filter tags">';
			
			echo '<div class="label">Tags</div>';
			$tags = get_tags( array(
			  'orderby' => 'count',
			  'order' => 'desc',
			  'number' => 20,
			  'hide_empty' => 0
			) );

			$tag_param_included = false;
			echo '<div class="commas tags">';
				if( sizeof( $tags ) ) {
					foreach( $tags as $tag) {
						$tag_title = $tag->name;
						$tag_slug = $tag->slug;
						$tag_id = $tag->tag_ID;
						$tag_class = $tag_slug;
						if( $tag_slug === $tag_param ) {
							$tag_param_included = true;
							$tag_url = remove_query_arg( 'tag', $page_url );
							$tag_class .= ' selected';
						} else {
							$tag_url = add_query_arg( 'tag', $tag_slug, $page_url );
						}
						echo '<span class="' . $tag_class . '">';
							echo '<a href="' . $tag_url . '" class="tag">' . $tag_title . '</a>';
						echo '</span>';
					}

					if( !$tag_param_included ) {
						$missing_tag = get_term_by( 'slug', $tag_param, 'post_tag' );
						$tag_url = remove_query_arg( 'tag', $page_url );
						echo '<span class="' . $tag_param . ' selected">';
							echo '<a href="' . $tag_url . '" class="tag">' . $missing_tag->name . '</a>';
						echo '</span>';
					}
				}
				$tag_page = get_page_by_path( 'tags' );
				$tag_page_url = get_permalink( $tag_page->ID );
				echo '<span class="more">';
					echo '<a href="' . $tag_page_url . '">View all</a>';
				echo '</span>';
			echo '</div>';
		echo '</div>';


	echo '</div>';
echo '</div>';
?>