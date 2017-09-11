<?php
$title = $post->post_title;
$thumb_id = get_post_thumbnail_id();
$thumb = wp_get_attachment_image_src( $thumb_id, 'thumb' );
$thumb_url = $thumb[0];
$thumb_width = $thumb[1];
$thumb_height = $thumb[2];
$permalink = get_the_permalink();
$excerpt = wp_strip_all_tags( get_the_excerpt() );
$date = get_the_date();
$author = get_field( 'author' );
$i = $wp_query->current_post;
$missing_url = get_template_directory_uri() . '/assets/images/question.svg';
$missing_svg = file_get_contents( $missing_url );
$column = get_the_terms( $post, 'columns' )[0];
echo '<article class="cell" role="article" style="' . $style . '">';
	echo '<div class="wrap">';
		echo '<a class="link_wrap" href="' . $permalink . '">';
			echo '<div class="' . ( $thumb ? 'image load' : 'missing') . '">';
				if ( $thumb ) {
					echo '<img data-src="'.$thumb_url.'" data-width="'.$thumb_width.'" data-height="'.$thumb_height.'"/>';
				}
			echo '</div>';
			echo '<div class="title">';
				if( $column ) {
					echo '<div class="column"><h3>' . $column->name . '</h3></div> ';
				}
				echo '<h2>' . $title . '</h2>';
			echo '</div>';
		echo '</a>';
		echo '<div class="excerpt"><em>';
			echo $excerpt;
		echo '</em></div>';
		echo '<div class="meta">';
			if( $author ) {
				echo '<span><a class="author" href="/' . $author . '">' . $author . '</a></span>';
			}
			echo '<span>' . $date . '</span>';
		echo '</div>';
	echo '</div>';
echo '</article>';
?>