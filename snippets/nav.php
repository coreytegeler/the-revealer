<?php
echo '<nav role="navigation">';
if( has_nav_menu( 'navigation' ) ) {
	$nav_links = array_reverse( wp_get_nav_menu_items( 'navigation' ) );
	echo '<ul class="row">';
		foreach ( (array) $nav_links as $link ) {
			$title = $link->title;
			$slug = $link->slug;
			$url = $link->url;
			echo '<li>';
				echo '<a class="conceal" href="' . $url . '" data-slug="' . $slug . '">';
					echo $title;
				echo '</a>';
			echo '</li>';
		}
	echo '</ul>';
}
if( is_archive() ) {
	$categories = get_categories( array(
    'orderby' => 'name',
    'order' => 'ASC',
		'exclude' => 1,
		'parent' => 0,
		'hide_empty' => true
	) );
	echo '<ul class="sub row">';
		foreach ( (array) $categories as $key => $cat ) {
			echo '<li>';
				$url = get_category_link( $cat->term_id );
				echo '<a class="conceal" href="' . $url . '">' . $cat->name . '</a>';
			echo '</li>';
		}
	echo '</ul>';
}
echo '</nav>';
?>