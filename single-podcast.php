<?php
global $post;
get_header();
$title = $post->post_title;
$thumb = get_the_post_thumbnail_url( $post );
$slug = $post->post_name;
$id = $post->ID;
$date = get_field( 'date' );
$tags = get_the_tags();
$permalink = get_the_permalink();

$content = apply_filters( 'the_content', $post->post_content );
$content = str_replace( ']]>', ']]&gt;', $content );
$stripped_content = strip_tags( $content );

$embed = get_field( 'embed' );
$stitcher = get_field( 'stitcher' );
$apple = get_field( 'apple' );
$spotify = get_field( 'spotify' );


echo '<article class="post readable podcast">';
	echo '<div class="text">';
		echo '<div class="lead">';
			echo '<div class="header">';
				echo '<h1 class="label column">Podcast</h1>';
				echo '<h1 class="title">';
					echo $title;
				echo '</h1>';
			echo '</div>';

			echo '<div class="meta">';

				echo '<div class="row date">';
					echo 'Published on ' . $date;
				echo '</div>';

			echo '</div>';

		echo '</div>';

		echo '<div class="content">';
			echo $content;
		echo '</div>';

		echo '<div class="rows links podcast-links">';
			echo '<em>Download and listen on: </em>';
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

		echo '<div class="content embed">';
			echo $embed;
		echo '</div>';

		echo '<div class="foot">';
			echo '<div class="meta">';

				if( $tags ) {
					echo '<div class="row tags commas">';
						echo '<span class="no_comma">Tags:&nbsp;</span>';
						foreach( $tags as $tag ) {
							$tag_url = add_query_arg( 'tag', $tag->slug, $articles_url );
							echo '<span>';
								echo '<a href="' . $tag_url . '" class="tag">' . $tag->name . '</a>';
							echo '</span>';
						}
					echo '</div>';
				}

				echo '<div class="social share">';
					echo '<div class="rows links">';
						echo '<span>Share this episode on </span>';
						echo '<a href="https://www.facebook.com/sharer/sharer.php?sdk=joey&u=' . $permalink . '">' . get_svg( 'facebook' ) . '</a>';
						echo '<span> or </span>';
						// echo '<a href="https://twitter.com/share?url=' . $permalink . '">' . get_svg( 'twitter' ) . '</a>';
						echo '<a href="https://bsky.app/intent/compose?text=' . urlencode( $permalink ) . '" title="Bluesky" target="_blank">' . get_svg( 'bluesky' ) . '</a>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';

	echo '</div>';

echo '</article>';

echo '<div class="carousel" id="carousel">';
	echo '<div class="slides"></div>';
	echo '<div class="arrow left" data-direction="left">' . get_svg( 'left' ) . '</div>';
	echo '<div class="arrow right" data-direction="right">' . get_svg( 'right' ) . '</div>';
	echo '<div class="close circle">';
		echo get_svg( $x_svg );
	echo '</div>';
echo '</div>';
get_footer();
?>