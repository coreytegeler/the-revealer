<?php
global $post;
$title = $post->post_title;
$thumb = get_the_post_thumbnail_url( $post );
$slug = $post->post_name;
$link = '/tag/' . $slug;
$id = $post->ID;
$date = get_the_date();
$categories = get_the_category();
$column = get_the_terms( $post, 'columns' )[0];
$column_url = add_query_arg( 'column', $column->slug, get_site_url() );
$author = get_field( 'author' );
$content = $post->post_content;
$excerpt = wp_strip_all_tags( get_the_excerpt() );
$tags = get_the_tags();
echo '<header role="contentinfo">';
	echo '<div class="info">';
		echo '<div class="title">';
			if( $column ) {
				echo '<div class="column"><h2>' . $column->name . '</h2></div> ';
			}
			echo '<h1>';
				echo $title;
			echo '</h1>';
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
					echo '<div>';
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
			echo '<div class="row time">';
				echo '<div>Reading time</div><div>' . get_read_time() . '</div>';
			echo '</div>';
			// echo '<div class="row progress">';
			// 	echo '<div>Progress</div><div class="progbar"><div class="solid"></div></div>';
			// echo '</div>';
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
	echo '<div class="images loop grid xsmall">';
		$regex = '/src="([^"]*)"/';
		preg_match_all( $regex, $content, $matches );
		$images = $matches[0];
		if( sizeof( $images ) ) {
			foreach( $images as $image ) {
				echo '<div class="image inline cell load transport">';
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
					$url = add_query_arg( 'tag', $tag->slug, $page_url );
					echo '<li>';
						echo '<a href="/' . $url . '" class="year"><em>' . $tag->name . '</em></a>';
					echo '</li>';
					$year--;
				}
			}
		echo '</ul>';
	echo '</div>';

echo '</header>';
?>