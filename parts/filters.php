<?php
$cat_param = get_query_var( 'category' );
$year_param = get_query_var( 'y' );
$col_param = get_query_var( 'column' );

$page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if( $cat_param ) {
	$page_url = add_query_arg( 'category', $cat_param, $page_url );
}
if( $year_param ) {
	$page_url = add_query_arg( 'y', $year_param, $page_url );
}
if( $col_param ) {
	$page_url = add_query_arg( 'column', $col_param, $page_url );
}
echo '<div id="filters">';
	echo '<div class="links">';

		echo '<div class="link filter categories">';
			echo '<div class="toggle" role="button">Categories</div>';
			$categories = get_categories( array(
			  'orderby' => 'name',
			  'order'   => 'ASC'
			) );
			echo '<div class="list categories show open">';
				echo '<ul>';
					if( sizeof( $categories ) ) {
						foreach( $categories as $category) {
							$cat_title = $category->name;
							$cat_slug = $category->slug;
							$cat_id = $category->cat_ID;
							$cat_url = add_query_arg( 'category', $cat_slug, $page_url );
							$cat_class = ( $cat_param && $cat_param == $cat_slug ? 'selected' : '' );
							echo '<li class="' . $cat_class . '">';
								echo '<a href="' . $cat_url . '" class="category">' . $cat_title . '</a>';
							echo '</li>';
						}
					} else {
						echo '<li class="empty">Nothing to filter</li>';
					}
				echo '</ul>';
			echo '</div>';
		echo '</div>';

		echo '<div class="link filter columns">';
			echo '<div class="toggle" role="button">Columns</div>';
			$columns = get_terms( array(
				'taxonomy' => 'columns',
			  'orderby' => 'name',
			  'order'   => 'ASC'
			) );
			echo '<div class="list columns show open">';
				echo '<ul>';
					if( sizeof( $columns ) ) {
						foreach( $columns as $column) {
							$col_title = $column->name;
							$col_slug = $column->slug;
							$col_id = $column->cat_ID;
							$col_url = add_query_arg( 'column', $col_slug, $page_url );
							$col_class = ( $col_param && $col_param == $col_slug ? 'selected' : '' );
							echo '<li class="' . $col_class . '">';
								echo '<a href="' . $col_url . '" class="column">' . $col_title . '</a>';
							echo '</li>';
						}
					} else {
						echo '<li class="empty">Nothing to filter</li>';
					}
				echo '</ul>';
			echo '</div>';
		echo '</div>';

		echo '<div class="link filter years">';
			echo '<div class="toggle" role="button">Years</div>';
			echo '<div class="list years show open">';
				echo '<ul>';
					$year = date( 'Y' );
					while(  $year >= 2003 ) {
						// $year_url = query_url( 'year', $year, $page_url, false );
						$year_url = add_query_arg( 'y', $year, $page_url );
						$year_class = ( $year_param && $year_param == $year ? 'selected' : 'no' );
						echo '<li class="' . $year_class . '">';
							echo '<a href="' . $year_url . '" class="year">' . $year . '</a>';
						echo '</li>';
						$year--;
					}
				echo '</ul>';
			echo '</div>';
		echo '</div>';

	echo '</div>';
echo '</div>';
?>