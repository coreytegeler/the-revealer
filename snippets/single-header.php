<?php
global $post;
$title = $post->post_title;
$thumb = get_the_post_thumbnail_url( $post );
$slug = $post->post_name;
$link = '/tag/' . $slug;
$id = $post->ID;
$date = get_the_date();
$categories = get_the_category();
$author = get_field( 'author' );
$excerpt = wp_strip_all_tags( get_the_excerpt() );
echo '<header role="contentinfo">';
	echo '<div class="info">';
		echo '<div class="title">';
			echo '<h1>' . $title . '</h1>';
		echo '</div>';
		echo '<a href="/' . $author . '" class="author">';
			echo '<h3>' . $author . '</h3>';
		echo '</a>';
		echo '<div class="meta">';
			echo '<div>Published on ' . $date . '</div>';
			if( $categories && $cat_length = sizeof( $categories ) ) {
				echo '<div>Filed under ';
					foreach ($categories as $i => $category) {
						$cat_url = get_category_link( $category->cat_ID );
						echo '<a href="' . $cat_url . '">' . $category->name . '</a>';
						if( $cat_length > 1 && $i == $cat_length - 1 ) {
							echo ', ';
						}
					}
				echo '</div>';
			}
		echo '</div>';
		echo '<div class="excerpt">';
			echo $excerpt;
		echo '</div>';
	echo '</div>';
	echo '<div class="images grid">';

	echo '</div>';
echo '</header>';
?>