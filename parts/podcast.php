<?php
global $post;
$title = $post->post_title;
$link = get_the_permalink();
$date = get_field( 'date' );
$i = $wp_query->current_post;

$stitcher = get_field( 'stitcher' );
$apple = get_field( 'apple' );
$spotify = get_field( 'spotify' );

echo '<article class="podcast">';
	echo '<div class="row">';
		echo '<div class="col col-12 col-sm-6">';
			echo '<a class="link_wrap" href="' . $link . '">';
				echo '<div class="title">';
					echo '<h2>' . $title . '</h2>';
					echo '<em>Read more</em>';
				echo '</div>';
			echo '</a>';
		echo '</div>';
		echo '<div class="col col-12 col-sm-6">';
			if( $date ) {
				echo '<div class="meta"><span class="date">' . $date . '</span></div>';
			}
			echo '<div class="podcast-links">';
				echo '<em>Download and listen on:</em>';
				if( $stitcher ) {
					echo '<a href="' . $stitcher . '" target="_blank">Stitcher</a>';
				}
				if( $apple ) {
					echo '<a href="' . $apple . '" target="_blank">Apple</a>';
				}
				if( $spotify ) {
					echo '<a href="' . $spotify . '" target="_blank">Spotify</a>';
				}
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</article>';
?>