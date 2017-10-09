<?php
$cat_param = $_GET['category'];
$year_param = $_GET['y'];
$col_param = $_GET['column'];

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
							$cat_class = $cat_slug;
							if( $cat_slug === $cat_param ) {
								$cat_url = remove_query_arg( 'category', $page_url );
								$cat_class .= ' selected';
							} else {
								$cat_url = add_query_arg( 'category', $cat_slug, $page_url );
							}
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
							$col_class = $col_slug;
							if( $col_slug === $col_param ) {
								$col_url = remove_query_arg( 'column', $page_url );
								$col_class .= ' selected';
							} else {
								$col_url = add_query_arg( 'column', $col_slug, $page_url );
							}
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
						$year_class = $year;
						if( $year == $year_param ) {
							$year_url = remove_query_arg( 'y', $page_url );
							$year_class .= ' selected';
						} else {
							$year_url = add_query_arg( 'y', $year, $page_url );
						}
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