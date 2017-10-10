<?php
global $post;
$title = $post->post_title;
$thumb = get_the_post_thumbnail_url( $post );
$slug = $post->post_name;
$id = $post->ID;
$date = get_the_date();
$categories = get_the_category();
$column = get_the_terms( $post, 'columns' )[0];
$column_url = add_query_arg( 'column', $column->slug, get_site_url() );
$author = get_field( 'author' );
$content = $post->post_content;
$excerpt = wp_strip_all_tags( get_the_excerpt() );
$tags = get_the_tags();
$images_regex = '/src="([^"]*)"/';
preg_match_all( $images_regex, $content, $matches );
$images = $matches[0];
echo '<div id="about" role="contentinfo">';
	echo '<div class="info">';
		echo '<div class="title">';
			if( $column ) {
				echo '<h2 class="label column">' . $column->name . '</h2>';
			}
			echo '<h1>';
				echo $title;
			echo '</h1>';
		echo '</div>';
		echo '<div class="meta">';
			if( $author ) {
				echo '<div class="row author">';
					echo '<div class="label">Author</div>';
					echo '<div class="value"><a href="/' . $author . '">' . $author . '</a></div>';
				echo '</div>';
			}
			echo '<div class="row date">';
				echo '<div class="label">Published</div>';
				echo '<div class="value">' . $date . '</div>';
			echo '</div>';
			if( $categories && $cat_length = sizeof( $categories ) ) {
				echo '<div class="row category">';
					echo '<div class="label">Category</div>';
					echo '<div class="value">';
						foreach ($categories as $i => $category) {
							$cat_url = get_category_link( $category->cat_ID );
							echo '<a href="' . $cat_url . '">' . $category->name . '</a>';
							if( $cat_length > 1 && $i != $cat_length - 1 ) {
								echo '<span>, </span>';
							}
						}
					echo '</div>';
				echo '</div>';
			}
		echo '</div>';
		// echo '<div class="excerpt">';
		// 	echo '<p>' . $excerpt . '</p>';
		// echo '</div>';
		if( $column ) {
			echo '<div class="more">';
				echo 'Posted in <em>' . $column->name . '</em>. ';
				echo '<a href="' . $column_url . '">';
					echo 'Read more';
				echo '</a>';
				echo ' from this column.';
			echo '</div>';
		}
	echo '</div>';
	echo '<div class="images loop masonry xsmall">';
		if( sizeof( $images ) ) {
			foreach( $images as $image ) {
				echo '<div class="cell transport">';
					echo '<div class="image load">';
						echo '<img '.$image.'" alt="'.$title.'" title="'.$title.'"/>';
					echo '</div>';
		  	echo '</div>';
			}
		}
	echo '</div>';
	echo '<div class="tags list">';
		echo '<ul>';
			if( $tags ) {
				foreach ( $tags as $tag ) {
					$tag_name = $tag->name;
					if( $tag_name ) {
						$tag_name_url = urlencode( $tag_name );
						$url = add_query_arg( 's', $tag_name_url, $page_url );
						echo '<li class="tag">';
							echo '<a href="/' . $url . '">' . $tag->name . '</a>';
						echo '</li>';
					}
				}
			}
		echo '</ul>';
	echo '</div>';

echo '</header>';
?>