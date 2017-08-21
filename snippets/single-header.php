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
$content = $post->post_content;
$excerpt = wp_strip_all_tags( get_the_excerpt() );
echo '<header role="contentinfo">';
	echo '<div class="info">';
		echo '<div class="title">';
			echo '<h1>' . $title . '</h1>';
		echo '</div>';
		echo '<div class="meta">';
			if( $author ) {
				echo '<div class="row author">';
					echo '<div>Author</div>';
					echo '<div><a href="/' . $author . '">' . $author . '</a></div>';
				echo '</div>';
			}
			echo '<div class="row date">';
				echo '<div>Published</div><div>' . $date . '</div>';
			echo '</div>';
			if( $categories && $cat_length = sizeof( $categories ) ) {
				echo '<div class="row category">';
					echo '<div>Category</div>';
					foreach ($categories as $i => $category) {
						$cat_url = get_category_link( $category->cat_ID );
						echo '<div><a href="' . $cat_url . '">' . $category->name . '</a></div>';
						if( $cat_length > 1 && $i == $cat_length - 1 ) {
							echo ', ';
						}
					}
				echo '</div>';
			}
			echo '<div class="row time">';
				echo '<div>Reading time</div><div>' . get_read_time() . '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="excerpt">';
			echo $excerpt;
		echo '</div>';
	echo '</div>';
	echo '<div class="images grid">';
		$regex = '/src="([^"]*)"/';
		preg_match_all( $regex, $content, $matches );
		$images = $matches[0];
		foreach( $images as $image ):
			// echo $image;
			echo '<div class="image cell load">';
				echo '<img '.$image.'" alt="'.$title.'" title="'.$title.'"/>';
		   echo '</div>';
		endforeach;
	echo '</div>';
echo '</header>';
?>