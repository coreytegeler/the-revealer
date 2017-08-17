<?php
$title = $post->post_title;
$thumbId = get_post_thumbnail_id();
$thumb = wp_get_attachment_image_src( $thumbId, 'full' );
$thumbUrl = $thumb[0];
$thumbWidth = $thumb[1];
$thumbHeight = $thumb[2];
$permalink = get_the_permalink();
$excerpt = wp_strip_all_tags( get_the_excerpt() );
$date = get_the_date();
$author = get_field( 'author' );
$i = $wp_query->current_post;
$missingUrl = get_template_directory_uri() . '/assets/images/question.svg';
$missingSvg = file_get_contents( $missingUrl );
echo '<article class="cell" role="article">';
	// if( $size == 'featured ') {
		echo '<div class="' . ( $thumb ? 'image load' : 'missing') . '">';
			echo '<a href="' . $permalink . '">';
				if ( $thumb ) {
					echo '<img data-src="'.$thumbUrl.'" data-width="'.$thumbWidth.'" data-height="'.$thumbHeight.'"/>';
				} else {
					echo $missingSvg;
				}
			echo '</a>';
		echo '</div>';
	// }
	echo '<div class="info">';
		echo '<div class="title">';
			echo '<a href="' . $permalink . '">';
				if( isset( $column ) ) {
					$title =  '<span>' . $column . ':</span> ' . $title;
				}
				echo '<h2>' . $title . '</h2>';
			echo '</a>';
			echo '<a href="/' . $author . '">';
				echo '<h3>' . $author . '</h3>';
			echo '</a>';
		echo '</div>';
	echo '</div>';
	echo '<div class="excerpt">';
		echo $excerpt;
	echo '</div>';
	echo '<div class="meta">';
		echo '<span>' . $date . '</span>';
	echo '</div>';
echo '</article>';
?>